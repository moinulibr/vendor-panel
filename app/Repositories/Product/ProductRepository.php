<?php

namespace App\Repositories\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator
    {
        $query = Product::query()
            ->where('is_new', 0)
            ->where('status', 1)
            ->where('is_ecom', 1);

        if (!empty($filters['category_ids'])) {
            $categoryIds = is_array($filters['category_ids']) ? $filters['category_ids'] : explode(',', $filters['category_ids']);
            $query->whereIn('category_id', $categoryIds);
        }

        if (!empty($filters['sub_category_ids'])) {
            $subCategoryIds = is_array($filters['sub_category_ids']) ? $filters['sub_category_ids'] : explode(',', $filters['sub_category_ids']);
            $query->whereIn('sub_category_id', $subCategoryIds);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query->whereBetween('min_price', [$filters['min_price'], $filters['max_price']]);
        }

        if (!empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "{$search}%")
                    ->orWhere('sku', $search);
            });
        }

        $sortBy = $filters['sort_by'] ?? 'latest';
        match ($sortBy) {
            'price_low'  => $query->orderBy('min_price', 'asc'),
            'price_high' => $query->orderBy('min_price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            default      => $query->orderBy('id', 'desc'),
        };

        return $query->select([
            'id',
            'name',
            'slug',
            'sku',
            'image',
            'category_id',
            'brand_id',
            'sell_price',
            'purchase_price',
            'min_price',
            'max_price',
            'type',
            'status'
        ])->simplePaginate($perPage);
    }

    public function getCategories()
    {
        return Category::select('id', 'name', 'slug', 'image', 'parent_id')
            ->where('is_new', 0)
            ->whereNull('parent_id')
            //->with('children:id,name,slug,parent_id')
            ->get();
    }

    public function getBrands()
    {
        return Brand::select('id', 'name', 'image')
            ->where('is_new', 0)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function findBySlugOrId(string $identifier)
    {
        return Product::with(['variations', 'category:id,name', 'brand:id,name'])
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
    }
}