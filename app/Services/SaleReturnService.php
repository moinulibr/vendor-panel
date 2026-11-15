<?php

namespace App\Services;

use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Repositories\Sale\Interface\SaleReturnRepositoryInterface;
use App\Models\SaleReturn;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    public function __construct(
        protected SaleRepositoryInterface $saleRepository,
        protected SaleReturnRepositoryInterface $returnRepository,
        protected BatchService $batchService,
        protected InventoryService $inventoryService
    ) {}

    public function createReturn(int $saleId, int $userId, array $returnItems, string $reason): SaleReturn
    {
        return DB::transaction(function () use ($saleId, $userId, $returnItems, $reason) {
            $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);

            if (!$sale || !in_array($sale->status, ['confirmed', 'processing', 'completed'])) {
                throw new Exception("This sale is not eligible for return.");
            }

            $totalRefund = 0;
            $isFullReturn = true; // ধরে নিচ্ছি সব রিটার্ন হচ্ছে, পরে চেক করব

            $saleReturn = $this->returnRepository->createSaleReturn([
                'return_no' => $this->generateReturnNo(),
                'sale_id' => $sale->id,
                'customer_id' => $userId,
                'warehouse_id' => $sale->warehouse_id,
                'return_type' => 'partial', // Initial, will update if full
                'settlement_type' => 'original_method',
                'status' => 'completed',
                'reason' => $reason,
                'created_by' => auth()->id(),
            ]);

            foreach ($returnItems as $reqItem) {
                $saleItem = $sale->items()->find($reqItem['sale_item_id']);
                if (!$saleItem) throw new Exception("Item ID {$reqItem['sale_item_id']} not found in this sale.");

                // 🟢 ১. ডাবল রিটার্ন চেক
                $alreadyReturned = $this->returnRepository->getReturnedQtyForItem($saleItem->id);
                $availableToReturn = $saleItem->qty - $alreadyReturned;

                if ($reqItem['qty_returned'] > $availableToReturn) {
                    throw new Exception("Cannot return {$reqItem['qty_returned']} qty. Only {$availableToReturn} available (Already returned: {$alreadyReturned}).");
                }

                // 🟢 ২. পারফেক্ট রিফান্ড ক্যালকুলেশন (line_total এর রেশিও)
                // line_total-এ ট্যাক্স, ডিসকাউন্ট সব ধরা থাকে।
                $ratio = $reqItem['qty_returned'] / $saleItem->qty;
                $netRefund = round($saleItem->line_total * $ratio, 2);

                $lineRefund = round($saleItem->line_subtotal * $ratio, 2);
                $taxReversal = round($saleItem->tax_amount * $ratio, 2);

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

                // স্টক ফেরত
                if ($saleItem->product->track_batch) {
                    $batchAllocs = $saleItem->batchAllocations;
                    $qtyToRestore = $reqItem['qty_returned'];

                    foreach ($batchAllocs as $alloc) {
                        if ($qtyToRestore <= 0) break;
                        $restoreQty = min($alloc->qty_used, $qtyToRestore);

                        $this->batchService->restoreStock($alloc->batch_id, $restoreQty);
                        $this->batchService->reverseBatchSummary($alloc->batch_id, $restoreQty, $alloc->unit_cost, $alloc->unit_sale_price);

                        $qtyToRestore -= $restoreQty;
                    }
                }
                $this->inventoryService->restoreStock($saleItem->product_id, $saleItem->variation_id, $reqItem['qty_returned']);

                // 🟢 ৩. ফুল রিটার্ন চেক
                if ($alreadyReturned + $reqItem['qty_returned'] < $saleItem->qty) {
                    $isFullReturn = false;
                }
            }

            // 🟢 ৪. সেল স্ট্যাটাস আপডেট
            // যদি সব আইটেম ফুল রিটার্ন হয়, তবে 'returned', নাহলে 'processing' বা 'completed'
            $newStatus = $isFullReturn ? 'returned' : 'processing';
            $newDueAmount = max(0, $sale->due_amount - $totalRefund);

            $this->saleRepository->updateSale($sale, [
                'status' => $newStatus,
                'due_amount' => $newDueAmount,
                'payment_status' => $newDueAmount <= 0 ? 'paid' : 'partially_paid'
            ]);

            // 🟢 ৫. রিফান্ড এন্ট্রি এবং লেজার
            if ($totalRefund > 0) {
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

                // কাস্টমার লেজার
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

                // 🟢 ৬. ভেন্ডর লেজার রিভার্স
                $vendorPayableReversed = 0;
                foreach ($returnItems as $reqItem) {
                    $saleItem = $sale->items()->find($reqItem['sale_item_id']);
                    $ratio = $reqItem['qty_returned'] / $saleItem->qty;
                    $vendorPayableReversed += ($saleItem->vendor_payable_amount * $ratio);
                }

                if ($vendorPayableReversed > 0 && $saleItem->vendor_id) {
                    $prevVendorBal = $this->saleRepository->getLatestLedgerBalance('vendor', $saleItem->vendor_id);
                    $this->saleRepository->insertLedgerEntry([
                        'party_type' => 'vendor',
                        'party_id' => $saleItem->vendor_id,
                        'entry_date' => now()->toDateString(),
                        'entry_at' => now(),
                        'entry_type' => 'return_credit',
                        'reference_type' => 'SaleReturn',
                        'reference_id' => $saleReturn->id,
                        'reference_no' => $saleReturn->return_no,
                        'debit' => $vendorPayableReversed,
                        'credit' => 0,
                        'running_balance' => $prevVendorBal - $vendorPayableReversed,
                        'currency' => 'BDT',
                        'exchange_rate' => 1,
                        'description' => "Vendor payable reversed for Return {$saleReturn->return_no}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return $saleReturn; // 🟢 SaleReturn রিটার্ন করছি
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
