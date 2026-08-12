<?php

namespace App\Repositories\Product\Interface;

use Illuminate\Contracts\Pagination\Paginator;

interface ProductRepositoryInterface
{
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator;
    public function getCategories();
    public function getBrands();
    public function findBySlugOrId(string $identifier);
    public function createOrUpdate(array $data, $id = null);
}