<?php

namespace App\Services;

use App\Repositories\Product\Interface\ProductRepositoryInterface;

class InventoryService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function deductStock(int $productId, ?int $variationId, int $qty): void
    {
        $this->productRepository->deductStock($productId, $variationId, $qty);
    }

    public function restoreStock(int $productId, ?int $variationId, int $qty): void
    {
        $this->productRepository->restoreStock($productId, $variationId, $qty);
    }
}
