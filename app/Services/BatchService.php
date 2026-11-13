<?php

namespace App\Services;

use App\Repositories\Batch\Interface\BatchRepositoryInterface;
use App\Models\BatchSummary;
use RuntimeException;

class BatchService
{
    public function __construct(
        protected BatchRepositoryInterface $batchRepository
    ) {}

    public function allocateFIFO(int $productId, ?int $variationId, int $warehouseId, int $qtyNeeded): array
    {
        $batches = $this->batchRepository->getFIFOBatches($productId, $variationId, $warehouseId);

        $allocations = [];
        $remaining = $qtyNeeded;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $consume = min($batch->qty_available, $remaining);
            $this->batchRepository->decrementBatchQty($batch, $consume);

            $allocations[] = [
                'batch' => $batch,
                'qty_used' => $consume,
                'unit_cost' => (float) $batch->cost_price,
            ];

            $remaining -= $consume;
        }

        if ($remaining > 0) {
            throw new RuntimeException("Insufficient stock for product ID {$productId}. Short by {$remaining} unit(s).");
        }

        return $allocations;
    }

    public function restoreStock(int $batchId, int $qty): void
    {
        $batch = \App\Models\ProductBatch::find($batchId);
        if ($batch) {
            $batch->increment('qty_available', $qty);
            if ($batch->status === 'depleted' && $batch->qty_available > 0) {
                $batch->update(['status' => 'active']);
            }
        }
    }

    
    public function updateBatchSummary(int $batchId, int $qtyUsed, float $unitCost, float $unitSalePrice): void
    {
        $summary = BatchSummary::where('product_batch_id', $batchId)->first();

        if ($summary) {
            $summary->increment('total_sold', $qtyUsed);
            $summary->increment('total_cogs', $qtyUsed * $unitCost);
            $summary->increment('total_revenue', $qtyUsed * $unitSalePrice);
            $summary->increment('total_profit', ($qtyUsed * $unitSalePrice) - ($qtyUsed * $unitCost));
            $summary->decrement('total_available', $qtyUsed);

            if ($summary->total_revenue > 0) {
                $summary->update([
                    'profit_margin_percent' => ($summary->total_profit / $summary->total_revenue) * 100
                ]);
            }
        }
    }
}