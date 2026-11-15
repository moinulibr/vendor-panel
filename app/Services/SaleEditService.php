<?php

namespace App\Services;

use App\Models\Sale;
use App\Repositories\Sale\Interface\SaleEditRepositoryInterface;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Utils\Util;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleEditService
{
    public function __construct(
        protected SaleRepositoryInterface $saleRepository,
        protected SaleEditRepositoryInterface $saleEditRepository,
        protected BatchService $batchService,
        protected InventoryService $inventoryService
    ) {}

    public function editSale(int $saleId, int $userId, array $newItems): Sale
    {
        return DB::transaction(function () use ($saleId, $userId, $newItems) {
            // sale find
            $baseSale = $this->saleRepository->findByIdAndUser($saleId, $userId);
            if (!$baseSale) throw new Exception("Sale not found.");

            // root sale id
            $rootId = $baseSale->parent_id ?? $baseSale->id;

            // latest version sale find
            $oldSale = $this->saleRepository->findCurrentActiveSale($rootId, $userId);
            if (!$oldSale) throw new Exception("Active sale not found.");

            if (!$oldSale->is_editable_for_customer) {
                throw new Exception("You are not allowed to edit this sale.");
            }
            if (in_array($oldSale->status, ['cancelled', 'returned', 'refunded'])) {
                throw new Exception("Cannot edit a cancelled or returned sale.");
            }

            // old sale stock, batch allocation restore
            if ($oldSale->status !== 'quotation') {
                foreach ($oldSale->items as $oldItem) {
                    if ($oldItem->product->track_batch) {
                        foreach ($oldItem->batchAllocations as $alloc) {
                            $this->batchService->restoreStock($alloc->batch_id, $alloc->qty_used);
                            $this->batchService->reverseBatchSummary($alloc->batch_id, $alloc->qty_used, $alloc->unit_cost, $alloc->unit_sale_price);
                        }
                    }
                    $this->inventoryService->restoreStock($oldItem->product_id, $oldItem->variation_id, $oldItem->qty);
                }
            }

            $this->saleEditRepository->updateOldSaleToHistory($oldSale);

            $subTotal = 0;
            $taxTotal = 0;
            $processedLines = [];

            // new item list process
            foreach ($newItems as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                $variation = isset($item['variation_id']) ? \App\Models\Variation::find($item['variation_id']) : null;

                $qty = $item['quantity'];
                if ($qty <= 0) continue; // Qty 0 means removed, skip it

                $existingItem = $oldSale->items->where('product_id', $item['product_id'])->where('variation_id', $item['variation_id'] ?? null)->first();

                // 🟢 Snapshot Lockdown
                if ($existingItem) {
                    $unitPrice = $existingItem->unit_price_snapshot;
                    $taxType = $existingItem->tax_type_snapshot;
                    $taxRate = $existingItem->tax_rate_snapshot;
                    $vcType = $existingItem->vendor_commission_type_snapshot;
                    $vcValue = $existingItem->vendor_commission_value_snapshot;
                } else {
                    $unitPrice = $item['unit_price'];
                    $taxType = $variation->tax_type ?? $product->tax_type ?? 'exempt';
                    $taxRate = $variation->tax_rate ?? $product->tax_rate ?? 0;
                    $vcType = $variation->vendor_commission_type ?? $product->vendor_commission_type;
                    $vcValue = $variation->vendor_commission_value ?? $product->vendor_commission_value ?? 0;
                }

                $lineSubTotal = $qty * $unitPrice;
                $taxAmount = 0;

                if (Util::IS_TAX_APPLICABLE_FOR_MOBILE_APP) {
                    if ($taxType === 'exclusive') {
                        $taxAmount = round($lineSubTotal * $taxRate / 100, 2);
                    } elseif ($taxType === 'inclusive') {
                        $taxAmount = round($lineSubTotal - ($lineSubTotal / (1 + $taxRate / 100)), 2);
                    }
                }

                $vcAmount = ($vcType === 'percentage') ? round($lineSubTotal * $vcValue / 100, 2) : $vcValue;
                $vendorPayable = $lineSubTotal - $vcAmount;

                $variantLable = null;
                if ($product->has_variants) {
                    $variantLable = $existingItem ? $existingItem->variant_label_snapshot : $variation?->name;
                } else {
                    $variantLable = $existingItem ? $existingItem->variant_label_snapshot : null;
                }

                $processedLines[] = [
                    'product_id' => $product->id,
                    'variation_id' => $variation?->id,
                    'vendor_id' => $product->vendor_id,
                    'product_name_snapshot' => $existingItem ? $existingItem->product_name_snapshot : $product->name,
                    'variant_label_snapshot' => $variantLable,
                    'image_snapshot' => $existingItem ? $existingItem->image_snapshot : ($variation->image ?? $product->image),
                    'unit_price_snapshot' => $unitPrice,
                    'qty' => $qty,
                    'line_subtotal' => $lineSubTotal,
                    'line_discount' => $existingItem ? $existingItem->line_discount : 0,
                    'taxable_amount' => $lineSubTotal,
                    'tax_type_snapshot' => $taxType,
                    'tax_rate_snapshot' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineSubTotal + ($taxType === 'exclusive' ? $taxAmount : 0),
                    'vendor_commission_type_snapshot' => $vcType,
                    'vendor_commission_value_snapshot' => $vcValue,
                    'vendor_commission_amount' => $vcAmount,
                    'vendor_payable_amount' => $vendorPayable,
                    'track_batch' => $product->track_batch,
                    'is_combo' => false,
                ];

                $subTotal += $lineSubTotal;
                $taxTotal += $taxAmount;
            }

            // new sale create
            $finalAmount = round($subTotal + $taxTotal + $oldSale->shipping_charge, 2);
            $dueAmount = $finalAmount - $oldSale->paid_amount;

            $newSaleData = $oldSale->toArray();
            unset($newSaleData['id'], $newSaleData['created_at'], $newSaleData['updated_at'], $newSaleData['items']);

            $newSaleData['version'] = $oldSale->version + 1;
            $baseInvoiceNo = preg_replace('/-V\d+$/', '', $oldSale->invoice_no);
            $newSaleData['invoice_no'] = $baseInvoiceNo . '-V' . $newSaleData['version'];

            $newSaleData['sub_total'] = $subTotal;
            $newSaleData['tax_amount'] = $taxTotal;
            $newSaleData['final_amount'] = $finalAmount;
            $newSaleData['due_amount'] = $dueAmount;
            $newSaleData['payment_status'] = $dueAmount <= 0 ? 'paid' : ($oldSale->paid_amount > 0 ? 'partially_paid' : 'unpaid');

            $newSale = $this->saleEditRepository->createNewVersion($oldSale, $newSaleData);

            // new item and stock deduct
            foreach ($processedLines as $line) {
                $saleItem = $this->saleRepository->createSaleItem(array_merge($line, ['sale_id' => $newSale->id, 'status' => 'active']));

                if ($newSale->status === 'confirmed') {
                    if ($line['track_batch']) {
                        $allocations = $this->batchService->allocateFIFO($line['product_id'], $line['variation_id'], $newSale->warehouse_id, $line['qty']);
                        foreach ($allocations as $alloc) {
                            $this->saleRepository->createSaleItemBatch([
                                'sale_id' => $newSale->id,
                                'sale_item_id' => $saleItem->id,
                                'batch_id' => $alloc['batch']->id,
                                'qty_used' => $alloc['qty_used'],
                                'unit_cost' => $alloc['unit_cost'],
                                'cost_total' => $alloc['qty_used'] * $alloc['unit_cost'],
                                'unit_sale_price' => $line['unit_price_snapshot'],
                                'total_sale_amount' => $alloc['qty_used'] * $line['unit_price_snapshot'],
                                'total_profit' => ($alloc['qty_used'] * $line['unit_price_snapshot']) - ($alloc['qty_used'] * $alloc['unit_cost'])
                            ]);
                            $this->batchService->updateBatchSummary($alloc['batch']->id, $alloc['qty_used'], $alloc['unit_cost'], $line['unit_price_snapshot']);
                        }
                    }
                    $this->inventoryService->deductStock($line['product_id'], $line['variation_id'], $line['qty']);
                }
            }

            // 🟢 edit history tracking (Fixed: Added old_price, new_price, old_discount, new_discount)
            foreach ($oldSale->items as $oldItem) {
                $newItem = collect($processedLines)->firstWhere('product_id', $oldItem->product_id);

                if (!$newItem) {
                    // Removed Item
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => $oldItem->id,
                        'change_type' => 'removed',
                        'old_qty' => $oldItem->qty,
                        'new_qty' => 0,
                        'old_price' => $oldItem->unit_price_snapshot,
                        'new_price' => 0,
                        'old_discount' => $oldItem->line_discount,
                        'new_discount' => 0
                    ]);
                } elseif ($newItem['qty'] != $oldItem->qty) {
                    // Qty Changed Item
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => $oldItem->id,
                        'change_type' => $newItem['qty'] > $oldItem->qty ? 'qty_increase' : 'qty_decrease',
                        'old_qty' => $oldItem->qty,
                        'new_qty' => $newItem['qty'],
                        'old_price' => $oldItem->unit_price_snapshot,
                        'new_price' => $newItem['unit_price_snapshot'],
                        'old_discount' => $oldItem->line_discount,
                        'new_discount' => $newItem['line_discount']
                    ]);
                }
            }

            foreach ($processedLines as $newItem) {
                if (!$oldSale->items->where('product_id', $newItem['product_id'])->first()) {
                    // Added Item
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => null,
                        'change_type' => 'added',
                        'old_qty' => 0,
                        'new_qty' => $newItem['qty'],
                        'old_price' => 0,
                        'new_price' => $newItem['unit_price_snapshot'],
                        'old_discount' => 0,
                        'new_discount' => $newItem['line_discount']
                    ]);
                }
            }

            $this->saleEditRepository->createEditHistory([
                'sale_id' => $oldSale->id,
                'version_from' => $oldSale->version,
                'version_to' => $newSale->version,
                'change_type' => 'combined',
                'delta_amount' => $finalAmount - $oldSale->final_amount,
                'reason' => 'User updated items',
                'changed_by' => auth()->id(),
            ]);

            return $newSale->load('items');
        });
    }
}
