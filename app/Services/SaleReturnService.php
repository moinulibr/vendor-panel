<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Repositories\Batch\Interface\BatchRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    public function __construct(
        protected BatchService $batchService,
        protected SaleRepositoryInterface $saleRepository,
        protected BatchRepositoryInterface $batchRepository
    ) {}

    public function createReturn(int $saleId, array $data): SaleReturn
    {
        return DB::transaction(function () use ($saleId, $data) {
            $sale = Sale::with('items')->where('is_current', true)->findOrFail($saleId);

            $totalRefund = 0;

            // 1. Create SaleReturn Record
            $saleReturn = SaleReturn::create([
                'return_no' => 'RET-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'warehouse_id' => $sale->warehouse_id,
                'return_type' => $data['return_type'] ?? 'partial',
                'settlement_type' => $data['settlement_type'] ?? 'original_method',
                'status' => 'completed',
                'reason' => $data['reason'] ?? 'Customer requested',
                'created_by' => auth()->id(),
            ]);

            // 2. Process each item to return
            foreach ($data['items'] as $returnItem) {
                $saleItem = $sale->items()->findOrFail($returnItem['sale_item_id']);

                $qtyReturned = $returnItem['qty_returned'];
                $refundPerItem = ($saleItem->line_total / $saleItem->qty) * $qtyReturned;
                $totalRefund += $refundPerItem;

                // Create SaleReturnItem
                \App\Models\SaleReturnItem::create([
                    'return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'variation_id' => $saleItem->variation_id,
                    'qty_returned' => $qtyReturned,
                    'unit_price_snapshot' => $saleItem->unit_price_snapshot,
                    'line_refund' => $saleItem->line_total,
                    'net_refund' => $refundPerItem,
                    'restock' => $returnItem['restock'] ?? true,
                    'condition' => $returnItem['condition'] ?? 'good',
                ]);

                // 3. Restock Batch (if applicable)
                if (($returnItem['restock'] ?? true) && $saleItem->variation_id) {
                    // Find batch allocation for this item
                    $allocations = DB::table('sale_item_batches')->where('sale_item_id', $saleItem->id)->get();
                    foreach ($allocations as $alloc) {
                        if ($qtyReturned <= 0) break;
                        $restockQty = min($alloc->qty_used, $qtyReturned);

                        $batch = \App\Models\ProductBatch::find($alloc->batch_id);
                        if ($batch) {
                            $this->batchRepository->incrementBatchQty($batch, $restockQty);
                        }
                        $qtyReturned -= $restockQty;
                    }
                }

                // 4. Reverse Salesperson Commission
                if ($sale->sales_person_id) {
                    DB::table('salesperson_commissions')
                        ->where('sale_item_id', $saleItem->id)
                        ->increment('reversed_amount', $refundPerItem * 0.05); // Example logic
                }
            }

            // 5. Update SaleReturn totals
            $saleReturn->update(['total_refund' => $totalRefund]);

            // 6. Update Original Sale payment status
            $newPaidAmount = max(0, $sale->paid_amount - $totalRefund);
            $newDueAmount = max(0, $sale->final_amount - $newPaidAmount);

            $this->saleRepository->updateSaleStatus(
                $sale,
                $newDueAmount == 0 ? 'paid' : 'partially_paid',
                $data['return_type'] === 'full' ? 'returned' : 'confirmed'
            );

            // 7. Create Refund Record (if cash)
            if (($data['settlement_type'] ?? '') === 'cash') {
                \App\Models\SaleRefund::create([
                    'refund_no' => 'REF-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                    'return_id' => $saleReturn->id,
                    'customer_id' => $sale->customer_id,
                    'amount' => $totalRefund,
                    'method' => 'cash',
                    'status' => 'success',
                    'refunded_at' => now(),
                    'refunded_by' => auth()->id(),
                ]);
            }

            // 8. Update Ledger (Credit Customer)
            $prevBalance = $this->saleRepository->getLatestLedgerBalance('customer', $sale->customer_id);
            $this->saleRepository->insertLedgerEntry([
                'party_type' => 'customer',
                'party_id' => $sale->customer_id,
                'entry_date' => now()->toDateString(),
                'entry_at' => now(),
                'entry_type' => 'refund_paid',
                'reference_type' => 'SaleReturn',
                'reference_id' => $saleReturn->id,
                'reference_no' => $saleReturn->return_no,
                'debit' => 0,
                'credit' => $totalRefund,
                'running_balance' => $prevBalance - $totalRefund,
                'description' => "Refund for return {$saleReturn->return_no}",
            ]);

            return $saleReturn;
        });
    }
}
