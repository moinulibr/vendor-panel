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
        protected BatchService $batchService
    ) {}

    public function editSale(int $saleId, int $userId, array $newItems): Sale
    {
        return DB::transaction(function () use ($saleId, $userId, $newItems) {
            $oldSale = $this->saleRepository->findByIdAndUser($saleId, $userId);

            if (!$oldSale) {
                throw new Exception("Sale not found.");
            }

            $user = auth()->user();
            if ( !$oldSale->is_editable_for_customer) { //$user->type === 'customer' &&
                throw new Exception("You are not allowed to edit this sale.");
            }
            /* if ($user->type === 'staff' && !$oldSale->is_editable_for_staff) {
                throw new Exception("Staff cannot edit this sale.");
            }
            if ($user->type === 'admin' && !$oldSale->is_editable_for_admin) {
                throw new Exception("Admin cannot edit this sale.");
            } */

            if (in_array($oldSale->status, ['cancelled', 'returned', 'refunded'])) {
                throw new Exception("Cannot edit a cancelled or returned sale.");
            }


            $this->saleEditRepository->updateOldSaleToHistory($oldSale);

            $subTotal = 0;
            $taxTotal = 0;
            $processedLines = [];

            foreach ($newItems as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                $variation = isset($item['variation_id']) ? \App\Models\Variation::find($item['variation_id']) : null;

                $qty = $item['quantity'];
                $existingItem = $oldSale->items->where('product_id', $item['product_id'])->where('variation_id', $item['variation_id'] ?? null)->first();

                // Price Lockdown
                $unitPrice = $existingItem ? $existingItem->unit_price_snapshot : $item['unit_price'];

                $lineSubTotal = $qty * $unitPrice;
                $taxType = $variation->tax_type ?? $product->tax_type ?? 'exempt';
                $taxRate = $variation->tax_rate ?? $product->tax_rate ?? 0;
                $taxAmount = 0;

                if (Util::IS_TAX_APPLICABLE_FOR_MOBILE_APP) {
                    if ($taxType === 'exclusive') $taxAmount = round($lineSubTotal * $taxRate / 100, 2);
                }

                $processedLines[] = [
                    'product_id' => $product->id,
                    'variation_id' => $variation?->id,
                    'vendor_id' => $product->vendor_id,
                    'product_name_snapshot' => $product->name,
                    'variant_label_snapshot' => $variation?->name,
                    'unit_price_snapshot' => $unitPrice,
                    'qty' => $qty,
                    'line_subtotal' => $lineSubTotal,
                    'tax_type_snapshot' => $taxType,
                    'tax_rate_snapshot' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineSubTotal + $taxAmount,
                    'track_batch' => $product->track_batch,
                ];

                $subTotal += $lineSubTotal;
                $taxTotal += $taxAmount;
            }

            $finalAmount = round($subTotal + $taxTotal + $oldSale->shipping_charge, 2);
            $dueAmount = $finalAmount - $oldSale->paid_amount;

            $newSaleData = $oldSale->toArray();
            unset($newSaleData['id'], $newSaleData['created_at'], $newSaleData['updated_at'], $newSaleData['items']);

            $newSaleData['sub_total'] = $subTotal;
            $newSaleData['tax_amount'] = $taxTotal;
            $newSaleData['final_amount'] = $finalAmount;
            $newSaleData['due_amount'] = $dueAmount;
            $newSaleData['payment_status'] = $dueAmount <= 0 ? 'paid' : ($oldSale->paid_amount > 0 ? 'partially_paid' : 'unpaid');

            $newSale = $this->saleEditRepository->createNewVersion($oldSale, $newSaleData);

            foreach ($processedLines as $line) {
                $saleItem = $this->saleRepository->createSaleItem(array_merge($line, [
                    'sale_id' => $newSale->id,
                    'status' => 'active'
                ]));

                if ($line['track_batch'] && $newSale->status === 'confirmed') {
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
                    }
                }
            }

            // 🟢 Line Item level Edit Tracking
            foreach ($oldSale->items as $oldItem) {
                $newItem = collect($processedLines)->firstWhere('product_id', $oldItem->product_id);

                if (!$newItem) {
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => $oldItem->id,
                        'change_type' => 'removed',
                        'old_qty' => $oldItem->qty,
                        'new_qty' => 0,
                    ]);
                } elseif ($newItem['qty'] != $oldItem->qty) {
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => $oldItem->id,
                        'change_type' => $newItem['qty'] > $oldItem->qty ? 'qty_increase' : 'qty_decrease',
                        'old_qty' => $oldItem->qty,
                        'new_qty' => $newItem['qty'],
                    ]);
                }
            }

            // 🟢 New items tracking
            foreach ($processedLines as $newItem) {
                if (!$oldSale->items->where('product_id', $newItem['product_id'])->first()) {
                    $this->saleEditRepository->createEditItem([
                        'sale_id' => $oldSale->id,
                        'sale_item_id' => null,
                        'change_type' => 'added',
                        'old_qty' => 0,
                        'new_qty' => $newItem['qty'],
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
