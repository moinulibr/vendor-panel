<?php

namespace App\Services;

use App\Models\Sale;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Repositories\Sale\Interface\SaleReturnRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    public function __construct(
        protected SaleRepositoryInterface $saleRepository,
        protected SaleReturnRepositoryInterface $returnRepository,
        protected BatchService $batchService
    ) {}

    public function createReturn(int $saleId, int $userId, array $returnItems, string $reason): Sale
    {
        return DB::transaction(function () use ($saleId, $userId, $returnItems, $reason) {
            $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);

            if (!$sale || !in_array($sale->status, ['confirmed', 'processing', 'completed'])) {
                throw new Exception("This sale is not eligible for return.");
            }

            $totalRefund = 0;

            // ১. রিটার্ন হেডার তৈরি
            $saleReturn = $this->returnRepository->createSaleReturn([
                'return_no' => $this->generateReturnNo(),
                'sale_id' => $sale->id,
                'customer_id' => $userId,
                'warehouse_id' => $sale->warehouse_id,
                'return_type' => count($returnItems) === $sale->items->count() ? 'full' : 'partial',
                'settlement_type' => 'original_method',
                'status' => 'completed',
                'reason' => $reason,
                'created_by' => auth()->id(),
            ]);

            // ২. আইটেম প্রসেসিং
            foreach ($returnItems as $reqItem) {
                $saleItem = $sale->items()->find($reqItem['sale_item_id']);
                if (!$saleItem || $reqItem['qty_returned'] > $saleItem->qty) {
                    throw new Exception("Invalid return quantity for item ID {$reqItem['sale_item_id']}.");
                }

                // Proportional Refund Calculation
                $ratio = $reqItem['qty_returned'] / $saleItem->qty;
                $lineRefund = $saleItem->line_subtotal * $ratio;
                $taxReversal = $saleItem->tax_amount * $ratio;
                $netRefund = $lineRefund + $taxReversal;
                $totalRefund += $netRefund;

                $this->returnRepository->createSaleReturnItem([
                    'return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'variation_id' => $saleItem->variation_id,
                    'unit_price_snapshot' => $saleItem->unit_price_snapshot,
                    'qty_returned' => $reqItem['qty_returned'],
                    'line_refund' => $lineRefund,
                    'tax_reversal' => $taxReversal,
                    'net_refund' => $netRefund,
                    'restock' => true,
                    'condition' => 'good',
                ]);

                // 🟢 স্টক ফেরত (Restock)
                if ($saleItem->track_batch ?? $saleItem->product->track_batch) {
                    $batchAllocs = $saleItem->batchAllocations;
                    $qtyToRestore = $reqItem['qty_returned'];

                    foreach ($batchAllocs as $alloc) {
                        if ($qtyToRestore <= 0) break;
                        $restoreQty = min($alloc->qty_used, $qtyToRestore);
                        $this->batchService->restoreStock($alloc->batch_id, $restoreQty);
                        $qtyToRestore -= $restoreQty;
                    }
                }
            }

            // ৩. সেল স্ট্যাটাস আপডেট
            $newStatus = $saleReturn->return_type === 'full' ? 'returned' : 'processing'; // partial return
            $newDueAmount = max(0, $sale->due_amount - $totalRefund);

            $this->saleRepository->updateSale($sale, [
                'status' => $newStatus,
                'due_amount' => $newDueAmount,
                'payment_status' => $newDueAmount <= 0 ? 'paid' : 'partially_paid'
            ]);

            // ৪. রিফান্ড এন্ট্রি
            $this->returnRepository->createSaleRefund([
                'refund_no' => $this->generateRefundNo(),
                'return_id' => $saleReturn->id,
                'customer_id' => $userId,
                'amount' => $totalRefund,
                'method' => 'original_method',
                'status' => 'success',
                'refunded_by' => auth()->id(),
                'refunded_at' => now(),
            ]);

            // ৫. লেজার এন্ট্রি (Customer Credit)
            $prevBalance = $this->saleRepository->getLatestLedgerBalance('customer', $userId);
            $this->saleRepository->insertLedgerEntry([
                'party_type' => 'customer',
                'party_id' => $userId,
                'entry_date' => now()->toDateString(),
                'entry_at' => now(),
                'entry_type' => 'refund_paid',
                'reference_type' => 'SaleReturn',
                'reference_id' => $saleReturn->id,
                'reference_no' => $saleReturn->return_no,
                'debit' => 0,
                'credit' => $totalRefund,
                'running_balance' => $prevBalance - $totalRefund,
                'currency' => 'BDT',
                'exchange_rate' => 1,
                'description' => "Refund for Return {$saleReturn->return_no}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $sale;
        });
    }

    private function generateReturnNo(): string
    {
        $prefix = "RET-" . now()->format('Y') . "-";
        $latest = $this->returnRepository->getLatestReturnNo($prefix);
        $next = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private function generateRefundNo(): string
    {
        $prefix = "REF-" . now()->format('Y') . "-";
        $latest = $this->returnRepository->getLatestRefundNo($prefix);
        $next = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
