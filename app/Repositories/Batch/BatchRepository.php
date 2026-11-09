<?php

namespace App\Repositories\Batch;

use App\Models\ProductBatch;
use App\Repositories\Batch\Interface\BatchRepositoryInterface;
use Illuminate\Support\Collection;

class BatchRepository implements BatchRepositoryInterface
{
    public function getFIFOBatches(int $productId, ?int $variationId, int $warehouseId): Collection
    {
        return ProductBatch::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('status', 'active')
            ->where('qty_available', '>', 0)
            ->when($variationId !== null, fn($q) => $q->where('variation_id', $variationId))
            ->orderByRaw('CASE WHEN expiry_date IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('expiry_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();
    }

    public function createBatch(array $data): ProductBatch
    {
        return ProductBatch::create($data);
    }

    public function decrementBatchQty(ProductBatch $batch, int $qty): void
    {
        $batch->decrement('qty_available', $qty);
        if ($batch->qty_available <= 0) {
            $batch->update(['status' => 'depleted']);
        }
    }

    public function incrementBatchQty(ProductBatch $batch, int $qty): void
    {
        $batch->increment('qty_available', $qty);
        if ($batch->qty_available > 0 && $batch->status === 'depleted') {
            $batch->update(['status' => 'active']);
        }
    }
}
