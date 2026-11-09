<?php

namespace App\Repositories\Batch\Interface;

use App\Models\ProductBatch;
use Illuminate\Support\Collection;

interface BatchRepositoryInterface
{
    public function getFIFOBatches(int $productId, ?int $variationId, int $warehouseId): Collection;
    public function createBatch(array $data): ProductBatch;
    public function decrementBatchQty(ProductBatch $batch, int $qty): void;
    public function incrementBatchQty(ProductBatch $batch, int $qty): void;
}
