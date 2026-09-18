<?php

namespace App\Repositories\Product\Interface;

//use Illuminate\Pagination\Paginator;

use App\Models\Product;
use App\Models\Variation;
use Illuminate\Contracts\Pagination\Paginator;

interface ProductRepositoryInterface
{
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator;
    public function getCategories();
    public function getBrands();
    public function findBySlugOrId(string|int $identifier, ?int $locationId = null): ?Product;
    public function findVariationBySlugOrId(string|int $identifier, ?int $locationId = null): ?Variation;
}