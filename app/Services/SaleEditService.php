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
            $baseSale = $this->saleRepository->findByIdAndUser($saleId, $userId);
            if (!$baseSale) throw new Exception("Sale not found.");

            $rootId = $baseSale->parent_id ?? $baseSale->id;
            $oldSale = $this->saleRepository->findCurrentActiveSale($rootId, $userId);
            if (!$oldSale) throw new Exception("Active sale not found.");

            if (!$oldSale->is_editable_for_customer) {
                throw new Exception("You are not allowed to edit this sale.");
            }
            if (in_array($oldSale->status, ['cancelled', 'returned', 'refunded'])) {
                throw new Exception("Cannot edit a cancelled or returned sale.");
            }

            // ১. পুরনো সেলের স্টক ফেরত
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
            $itemTotalDiscount = 0;
            $taxTotal = 0;
            $processedLines = [];

            // ২. নতুন আইটেম লিস্ট প্রসেস
            foreach ($newItems as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                $variation = isset($item['variation_id']) ? \App\Models\Variation::find($item['variation_id']) : null;

                $qty = $item['quantity'];
                if ($qty <= 0) continue;

                $existingItem = $oldSale->items->where('product_id', $item['product_id'])->where('variation_id', $item['variation_id'] ?? null)->first();

                // Snapshot Lockdown
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

                // 🟢 ১. লাইন ডিসকাউন্ট প্রসেসিং
                $lineSubTotal = $qty * $unitPrice;
                $lineDiscount = $item['discount'] ?? ($existingItem ? $existingItem->line_discount : 0);
                $taxableAmount = $lineSubTotal - $lineDiscount;

                $taxAmount = 0;
                if (Util::IS_TAX_APPLICABLE_FOR_MOBILE_APP) {
                    if ($taxType === 'exclusive') {
                        $taxAmount = round($taxableAmount * $taxRate / 100, 2);
                    } elseif ($taxType === 'inclusive') {
                        $taxAmount = round($taxableAmount - ($taxableAmount / (1 + $taxRate / 100)), 2);
                    }
                }

                $vcAmount = ($vcType === 'percentage') ? round($taxableAmount * $vcValue / 100, 2) : $vcValue;
                $vendorPayable = $taxableAmount - $vcAmount;

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
                    'line_discount' => $lineDiscount,
                    'taxable_amount' => $taxableAmount,
                    'tax_type_snapshot' => $taxType,
                    'tax_rate_snapshot' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'line_total' => $taxableAmount + ($taxType === 'exclusive' ? $taxAmount : 0),
                    'vendor_commission_type_snapshot' => $vcType,
                    'vendor_commission_value_snapshot' => $vcValue,
                    'vendor_commission_amount' => $vcAmount,
                    'vendor_payable_amount' => $vendorPayable,
                    'track_batch' => $product->track_batch,
                    'is_combo' => false,
                ];

                $subTotal += $lineSubTotal;
                $itemTotalDiscount += $lineDiscount;
                $taxTotal += $taxAmount;
            }

            // ৩. 🟢 ইনভয়েস ডিসকাউন্ট অ্যালোকেশন (Proportional)
            $grossTotal = $subTotal - $itemTotalDiscount;
            $invoiceDiscountAmount = $oldSale->discount_amount; // Assuming invoice discount remains same
            if ($invoiceDiscountAmount > 0 && $grossTotal > 0) {
                $allocated = 0;
                $lastIndex = count($processedLines) - 1;
                foreach ($processedLines as $i => &$line) {
                    $share = ($i === $lastIndex) ? round($invoiceDiscountAmount - $allocated, 2) : round(($line['taxable_amount'] / $grossTotal) * $invoiceDiscountAmount, 2);
                    $line['allocated_inv_disc'] = $share;
                    $line['line_total'] = round($line['taxable_amount'] - $share + $line['tax_amount'], 2);
                    $allocated += $share;
                }
            } else {
                foreach ($processedLines as &$line) {
                    $line['allocated_inv_disc'] = 0;
                }
            }

            // ৪. নতুন সেল তৈরি
            $finalAmount = round(($grossTotal - $invoiceDiscountAmount) + $oldSale->shipping_charge + $taxTotal, 2);
            $dueAmount = $finalAmount - $oldSale->paid_amount;

            $newSaleData = $oldSale->toArray();
            unset($newSaleData['id'], $newSaleData['created_at'], $newSaleData['updated_at'], $newSaleData['items']);

            $newSaleData['version'] = $oldSale->version + 1;
            $baseInvoiceNo = preg_replace('/-V\d+$/', '', $oldSale->invoice_no);
            $newSaleData['invoice_no'] = $baseInvoiceNo . '-V' . $newSaleData['version'];

            $newSaleData['sub_total'] = $subTotal;
            $newSaleData['item_total_discount'] = $itemTotalDiscount;
            $newSaleData['gross_total'] = $grossTotal;
            $newSaleData['tax_amount'] = $taxTotal;
            $newSaleData['final_amount'] = $finalAmount;
            $newSaleData['due_amount'] = $dueAmount;
            $newSaleData['payment_status'] = $dueAmount <= 0 ? 'paid' : ($oldSale->paid_amount > 0 ? 'partially_paid' : 'unpaid');

            $newSale = $this->saleEditRepository->createNewVersion($oldSale, $newSaleData);

            // ৫. নতুন আইটেম এবং স্টক ডিডাক্ট
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

            // ৬. 🟢 পেমেন্ট লেজার আপডেট (Delta)
            $this->updateLedgerDelta($oldSale, $newSale, $processedLines);

            // ৭. এডিট হিস্ট্রি ট্র্যাকিং
            foreach ($oldSale->items as $oldItem) {
                $newItem = collect($processedLines)->firstWhere('product_id', $oldItem->product_id);

                if (!$newItem) {
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

    // নতুন মেথড: লেজারে ডেল্টা এন্ট্রি দেওয়া
    private function updateLedgerDelta(Sale $oldSale, Sale $newSale, array $processedLines): void
    {
        $now = now();
        $entryDate = $now->toDateString();
        $deltaAmount = $newSale->final_amount - $oldSale->final_amount;

        // ১. কাস্টমার লেজার আপডেট
        if ($deltaAmount != 0 && $newSale->customer_id) {
            $prev = $this->saleRepository->getLatestLedgerBalance('customer', $newSale->customer_id);
            $this->saleRepository->insertLedgerEntry([
                'party_type' => 'customer',
                'party_id' => $newSale->customer_id,
                'entry_date' => $entryDate,
                'entry_at' => $now,
                'entry_type' => 'sale_edit',
                'reference_type' => 'Sale',
                'reference_id' => $newSale->id,
                'reference_no' => $newSale->invoice_no,
                'debit' => $deltaAmount > 0 ? $deltaAmount : 0,
                'credit' => $deltaAmount < 0 ? abs($deltaAmount) : 0,
                'running_balance' => $prev + $deltaAmount,
                'currency' => 'BDT',
                'exchange_rate' => 1,
                'description' => "Sale Edit Delta for {$newSale->invoice_no}",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ২. ভেন্ডর লেজার আপডেট
        $oldVendorTotals = $oldSale->items->groupBy('vendor_id')->map(fn($items) => $items->sum('vendor_payable_amount'));
        $newVendorTotals = collect($processedLines)->groupBy('vendor_id')->map(fn($items) => $items->sum('vendor_payable_amount'));

        $vendorIds = $oldVendorTotals->keys()->merge($newVendorTotals->keys())->unique();
        foreach ($vendorIds as $vendorId) {
            if (!$vendorId) continue;
            $oldTotal = $oldVendorTotals[$vendorId] ?? 0;
            $newTotal = $newVendorTotals[$vendorId] ?? 0;
            $vendorDelta = $newTotal - $oldTotal;

            if ($vendorDelta != 0) {
                $prev = $this->saleRepository->getLatestLedgerBalance('vendor', $vendorId);
                $this->saleRepository->insertLedgerEntry([
                    'party_type' => 'vendor',
                    'party_id' => $vendorId,
                    'entry_date' => $entryDate,
                    'entry_at' => $now,
                    'entry_type' => 'sale_edit',
                    'reference_type' => 'Sale',
                    'reference_id' => $newSale->id,
                    'reference_no' => $newSale->invoice_no,
                    'debit' => $vendorDelta < 0 ? abs($vendorDelta) : 0,
                    'credit' => $vendorDelta > 0 ? $vendorDelta : 0,
                    'running_balance' => $prev + $vendorDelta,
                    'currency' => 'BDT',
                    'exchange_rate' => 1,
                    'description' => "Vendor Payable Delta for {$newSale->invoice_no}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
