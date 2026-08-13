<?php

namespace App\Services;

use App\Repositories\Product\Interface\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getProductList(array $filters)
    {
        $perPage = $filters['per_page'] ?? 20;
        return $this->productRepository->getFilteredProducts($filters, $perPage);
    }

    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }

    public function getBrands()
    {
        return $this->productRepository->getBrands();
    }

    public function getProductDetails(string $identifier)
    {
        return $this->productRepository->findBySlugOrId($identifier);
    }

}