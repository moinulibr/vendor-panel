<?php

namespace App\Services;

use App\Models\Sale;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Repositories\Sale\Interface\SaleEditRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleEditService
{
    public function __construct(
        protected BatchService $batchService,
        protected SaleRepositoryInterface $saleRepository,
        protected SaleEditRepositoryInterface $saleEditRepository
    ) {}

    /**
     * Update a Pending Quotation
     */
    public function updateQuotation(int $saleId, int $userId, array $data): Sale
    {
        return DB::transaction(function () use ($saleId, $userId, $data) {
            $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);

            if (!$sale) {
                throw new Exception("Quotation not found.");
            }

            if ($sale->status !== 'quotation') {
                throw new Exception("Only pending quotations can be updated.");
            }

            if (isset($data['note'])) {
                $this->saleRepository->updateSale($sale, ['notes' => $data['note']]);
            }

            if (!empty($data['items'])) {

                $sale->items()->delete();

                $subTotal = 0;
                $taxTotal = 0;

                foreach ($data['items'] as $itemData) {
                    $lineSubTotal = $itemData['quantity'] * $itemData['unit_price'];
                    $lineDiscount = $itemData['discount'] ?? 0;

                    $product = \App\Models\Product::find($itemData['product_id']);
                    $variation = isset($itemData['variation_id']) ? \App\Models\Variation::find($itemData['variation_id']) : null;

                    $taxType = $variation->tax_type ?? $product->tax_type ?? 'exempt';
                    $taxRate = $variation->tax_rate ?? $product->tax_rate ?? 0;
                    $taxAmount = ($taxType === 'exclusive') ? round(($lineSubTotal - $lineDiscount) * $taxRate / 100, 2) : 0;

                    $this->saleRepository->createSaleItem([
                        'sale_id' => $sale->id,
                        'product_id' => $itemData['product_id'],
                        'variation_id' => $itemData['variation_id'] ?? null,
                        'vendor_id' => $product->vendor_id,
                        'product_name_snapshot' => $product->name,
                        'variant_label_snapshot' => $variation->name ?? null,
                        'unit_price_snapshot' => $itemData['unit_price'],
                        'qty' => $itemData['quantity'],
                        'line_subtotal' => $lineSubTotal,
                        'line_discount' => $lineDiscount,
                        'taxable_amount' => $lineSubTotal - $lineDiscount,
                        'tax_type_snapshot' => $taxType,
                        'tax_rate_snapshot' => $taxRate,
                        'tax_amount' => $taxAmount,
                        'line_total' => ($lineSubTotal - $lineDiscount) + $taxAmount,
                        'status' => 'active'
                    ]);

                    $subTotal += $lineSubTotal;
                    $taxTotal += $taxAmount;
                }

                
                $grossTotal = $subTotal - ($data['discount_value'] ?? 0);
                $finalAmount = $grossTotal + $sale->shipping_charge + $taxTotal;

                $this->saleRepository->updateSale($sale, [
                    'sub_total' => $subTotal,
                    'gross_total' => $grossTotal,
                    'tax_amount' => $taxTotal,
                    'final_amount' => $finalAmount,
                    'due_amount' => $finalAmount,
                ]);
            }

            return $sale->fresh('items');
        });
    }
    
    public function editSale(int $saleId, array $data): Sale
    {
        return DB::transaction(function () use ($saleId, $data) {
            $oldSale = Sale::with('items')->where('is_current', true)->findOrFail($saleId);

            if (!$oldSale->is_editable_for_admin) {
                throw new Exception("This sale is not editable.");
            }

            // 1. Mark old sale as history
            $this->saleEditRepository->updateOldSaleToHistory($oldSale);

            // 2. Prepare new items data (handling price lockdown)
            $newItems = $this->processItemsForEdit($oldSale, $data['items']);

            // 3. Calculate new totals (Re-using logic or simplified version here)
            $subTotal = collect($newItems)->sum('line_subtotal');
            $taxAmount = collect($newItems)->sum('tax_amount');
            $finalAmount = $subTotal + $taxAmount + ($data['shipping_charge'] ?? 0);

            // 4. Create New Version
            $newSaleData = $oldSale->toArray();
            unset($newSaleData['id'], $newSaleData['created_at'], $newSaleData['updated_at']);

            $newSaleData['sub_total'] = $subTotal;
            $newSaleData['final_amount'] = $finalAmount;
            $newSaleData['due_amount'] = $finalAmount - $oldSale->paid_amount;
            $newSaleData['notes'] = $data['reason'] ?? $oldSale->notes;
            $newSaleData['payment_status'] = $oldSale->paid_amount >= $finalAmount ? 'paid' : ($oldSale->paid_amount > 0 ? 'partially_paid' : 'unpaid');

            $newSale = $this->saleEditRepository->createNewVersion($oldSale, $newSaleData);

            // 5. Insert New Items & Adjust Batches
            foreach ($newItems as $item) {
                // Re-create SaleItem
                $newSaleItem = $this->saleRepository->createSaleItem(array_merge($item, ['sale_id' => $newSale->id]));

                // Handle Batch delta (Complex logic: reverse old, allocate new)
                // For simplicity, we restore old batch and allocate new
                if ($item['track_batch']) {
                    $this->allocateBatchesForEdit($newSale, $newSaleItem, $item);
                }
            }

            // 6. Record Edit History
            $deltaAmount = $newSale->final_amount - $oldSale->final_amount;
            $this->saleEditRepository->createEditHistory([
                'sale_id' => $oldSale->id,
                'version_from' => $oldSale->version,
                'version_to' => $newSale->version,
                'change_type' => 'combined',
                'delta_amount' => $deltaAmount,
                'reason' => $data['reason'] ?? 'Sale edited',
                'changed_by' => auth()->id(),
            ]);

            // 7. Update Payment Ledger (Delta)
            if ($deltaAmount != 0) {
                $prevBalance = $this->saleRepository->getLatestLedgerBalance('customer', $newSale->customer_id);
                $this->saleRepository->insertLedgerEntry([
                    'party_type' => 'customer',
                    'party_id' => $newSale->customer_id,
                    'entry_date' => now()->toDateString(),
                    'entry_at' => now(),
                    'entry_type' => 'sale_edit',
                    'reference_type' => 'Sale',
                    'reference_id' => $newSale->id,
                    'reference_no' => $newSale->invoice_no,
                    'debit' => $deltaAmount > 0 ? $deltaAmount : 0,
                    'credit' => $deltaAmount < 0 ? abs($deltaAmount) : 0,
                    'running_balance' => $prevBalance + $deltaAmount,
                    'description' => "Adjustment for edited sale {$newSale->invoice_no}",
                ]);
            }

            return $newSale;
        });
    }

    private function processItemsForEdit(Sale $oldSale, array $newItems): array
    {
        $processed = [];
        $oldItems = $oldSale->items->keyBy('product_id'); // Basic mapping

        foreach ($newItems as $item) {
            $isExisting = isset($oldItems[$item['product_id']]);

            // RULE: If existing, keep old price snapshot. If new, use provided price.
            $unitPrice = $isExisting ? $oldItems[$item['product_id']]->unit_price_snapshot : $item['unit_price'];

            $lineSubtotal = $item['qty'] * $unitPrice;

            $processed[] = [
                'product_id' => $item['product_id'],
                'variation_id' => $item['variation_id'] ?? null,
                'qty' => $item['qty'],
                'unit_price_snapshot' => $unitPrice,
                'line_subtotal' => $lineSubtotal,
                'tax_amount' => 0, // Calculate based on product tax
                'track_batch' => true, // Get from product
                'status' => 'active',
                'line_note' => $isExisting ? 'edited (price locked)' : 'added during edit',
                // Add other required snapshots here...
            ];
        }
        return $processed;
    }

    private function allocateBatchesForEdit($sale, $saleItem, $item)
    {
        // Simplify: Just allocate FIFO for the new quantity. 
        // In a real scenario, you calculate the delta between old and new qty.
        $allocations = $this->batchService->allocateFIFO(
            $item['product_id'],
            $item['variation_id'],
            $sale->warehouse_id,
            $item['qty']
        );

        foreach ($allocations as $alloc) {
            $this->saleRepository->createSaleItemBatch([
                'sale_id' => $sale->id,
                'sale_item_id' => $saleItem->id,
                'batch_id' => $alloc['batch']->id,
                'qty_used' => $alloc['qty_used'],
                'unit_cost' => $alloc['unit_cost'],
                'cost_total' => $alloc['qty_used'] * $alloc['unit_cost'],
                'unit_sale_price' => $item['unit_price_snapshot'],
                'total_sale_amount' => $alloc['qty_used'] * $item['unit_price_snapshot'],
                'total_profit' => ($alloc['qty_used'] * $item['unit_price_snapshot']) - ($alloc['qty_used'] * $alloc['unit_cost']),
            ]);
        }
    }
}
