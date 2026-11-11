<?php

namespace App\Services;

use App\Repositories\Batch\Interface\BatchRepositoryInterface;
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
}
