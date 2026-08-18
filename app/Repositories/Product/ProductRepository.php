<?php

namespace App\Repositories\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface
{

    /*
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator
    {
        $query = Product::query()
            ->with(['images', 'variations'])
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
            'name_bangla',
            'description',
            'specification',
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
            'status',
            'is_ecom',
            'is_reco',
            'is_feature',
            'warranty_available',
            'warranty_days',
            'warranty_note',
            'return_available',
            'return_days',
            'return_note',
            'estimate_delivery_day',
            'stock_manage'
            //'stock_alert',
        ])->simplePaginate($perPage);
    }
    */
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator
    {
        $search = !empty($filters['q']) ? trim($filters['q']) : null;
        $locationId = !empty($filters['location_id']) ? trim($filters['location_id']) : null;
        $matchedProductIdsFromVariation = [];

        // Step 1: Step-by-Step Optimized Variation Search
        if ($search) {
            $matchedProductIdsFromVariation = \DB::table('variations')
                ->where('name', '!=', "dummy")
                ->where('sub_sku', 'LIKE', "{$search}%")
                ->limit(100)
                ->pluck('product_id')
                ->toArray();
        }

        // Step 2: Main Query Execution
        $query = Product::query()
            ->with([
                'images',
                'variations' => function ($q) use ($search) {
                    if ($search) {
                        // Grouped OR Clause to avoid extra variant leaking
                        $q->where(function ($sub) use ($search) {
                            $sub->where('sub_sku', 'LIKE', "{$search}%")
                                ->orWhere('name', 'LIKE', "{$search}%");
                        });
                    }
                },
                'variations.stocks' => function ($q) use ($locationId) {
                    if ($locationId) {
                        $q->where('location_id', $locationId);
                    }
                }
            ])
            ->where('is_new', 0)
            ->where('status', 1)
            ->where('is_ecom', 1);

        // Filters
        if (!empty($filters['category_ids'])) {
            $categoryIds = is_array($filters['category_ids']) ? $filters['category_ids'] : explode(',', $filters['category_ids']);
            $query->whereIn('category_id', $categoryIds);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query->whereBetween('min_price', [$filters['min_price'], $filters['max_price']]);
        }

        // Step 3: Main Product Search Matching
        if ($search) {
            $query->where(function ($q) use ($search, $matchedProductIdsFromVariation) {
                $q->where('name', 'LIKE', "{$search}%")
                    ->orWhere('sku', 'LIKE', "{$search}%");

                if (!empty($matchedProductIdsFromVariation)) {
                    $q->orWhereIn('id', $matchedProductIdsFromVariation);
                }
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'latest';
        match ($sortBy) {
            //'price_low' => $query->orderBy('min_price', 'asc'),
            //'price_high' => $query->orderBy('min_price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        return $query->select([
            'id',
            'name',
            'name_bangla',
            'description',
            'specification',
            'slug',
            'sku',
            'image',
            'category_id',
            'brand_id',
            'sell_price',
            'purchase_price',
            //'min_price',
            //'max_price',
            'type',
            'status',
            'is_ecom',
            'is_reco',
            'is_feature',
            'warranty_available',
            'warranty_days',
            'warranty_note',
            'return_available',
            'return_days',
            'return_note',
            'estimate_delivery_day',
            'stock_manage'
        ])->simplePaginate($perPage);
    }

    public function getCategories()
    {
        return Category::select('id', 'name', 'bd_name','slug', 'image', 'parent_id')
            ->where('is_new', 0)
            ->whereNull('parent_id')
            //->with('children:id,name,slug,parent_id')
            ->get();
    }

    public function getBrands()
    {
        return Brand::select('id', 'name', 'bd_name', 'image')
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