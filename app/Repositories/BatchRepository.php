<?php

namespace App\Repositories;

use App\Models\ProductBatch;
use App\Repositories\Interface\BatchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BatchRepository implements BatchRepositoryInterface
{
    public function getFIFOBatches(int $productId, ?int $variationId, int $warehouseId): Collection
    {
        return ProductBatch::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('status', 'active')
            ->where('qty_available', '>', 0)
            ->when($variationId !== null, fn ($q) => $q->where('variation_id', $variationId))
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
}
