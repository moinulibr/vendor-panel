<?php

namespace App\Repositories\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variation;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Highly Scalable Product Filtering Query (Handles 2M+ Records)
     */
    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator
    {
        $search = !empty($filters['q']) ? trim($filters['q']) : null;
        $locationId = $filters['location_id'] ?? null;

        $query = Product::query()
            ->where('is_new', 0)
            ->where('status', 1)
            ->where('is_ecom', 1);

        // 1. Base Category & Brand Filters
        if (!empty($filters['category_ids'])) {
            $categoryIds = is_array($filters['category_ids']) ? $filters['category_ids'] : explode(',', $filters['category_ids']);
            $query->whereIn('category_id', $categoryIds);
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

        // 2. High-Performance Search Logic
        if ($search) {
            // Check if product name or SKU matches directly
            $isProductMatched = Product::query()
                ->where('sku', 'LIKE', "{$search}%")
                ->orWhere('name', 'LIKE', "{$search}%")
                ->orWhere('name_bangla', 'LIKE', "{$search}%")
                ->exists();

            if ($isProductMatched) {
                $query->where(function ($q) use ($search) {
                    $q->where('sku', 'LIKE', "{$search}%")
                        ->orWhere('name', 'LIKE', "{$search}%")
                        ->orWhere('name_bangla', 'LIKE', "{$search}%");
                })
                    ->with(['variations' => function ($v) use ($locationId) {
                        $this->applyStockRelation($v, $locationId);
                    }]);
            } else {
                $query->whereHas('variations', function ($v) use ($search) {
                    $v->where('sub_sku', 'LIKE', "{$search}%")
                        ->orWhere('name', 'LIKE', "{$search}%");
                })
                ->with(['variations' => function ($v) use ($search, $locationId) {
                    $v->where(function ($sub) use ($search) {
                        $sub->where('sub_sku', 'LIKE', "{$search}%")
                            ->orWhere('name', 'LIKE', "{$search}%");
                    });
                    $this->applyStockRelation($v, $locationId);
                }]);
            }
        } else {
            $query->with(['variations' => function ($v) use ($locationId) {
                $this->applyStockRelation($v, $locationId);
            }]);
        }

        // 3. Eager Load Common Relations
        $query->with([
            'category:id,name,slug,image',
            'brand:id,name,image',
            'images:id,product_id,image',
        ]);

        // 4. Sorting
        $sortBy = $filters['sort_by'] ?? 'latest';
        match ($sortBy) {
            'name_asc'  => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default     => $query->orderBy('id', 'desc'),
        };

        return $query->select([
            'id',
            'name',
            'name_bangla',
            'slug',
            'sku',
            'image',
            'category_id',
            'brand_id',
            'sell_price',
            'type',
            'status',
            'is_ecom',
            'is_feature',
            'variants'
        ])->simplePaginate($perPage);
    }

    /**
     * Helper to apply stock relation based on location
     */
    private function applyStockRelation($query, ?int $locationId)
    {
        if ($locationId) {
            $query->with(['stocks' => function ($sq) use ($locationId) {
                $sq->where('location_id', $locationId)
                    ->select(['id', 'product_id', 'variation_id', 'location_id', 'qty_available']);
            }]);
        } else {
            $query->with('stocks:id,product_id,variation_id,location_id,qty_available');
        }
    }

    /**
     * Fetch Product Details by Identifier
     */
    public function findBySlugOrId(string|int $identifier, ?int $locationId = null, ?string $type = null): ?Product
    {
        $selectedVariationId = null;
        $product = null;

        if ($type === 'variable') {
            $product = $this->fetchByVariationIdentifier($identifier, $selectedVariationId);
        } elseif ($type === 'single') {
            $product = $this->fetchByProductIdentifier($identifier);
        } else {
            $product = $this->fetchByProductIdentifier($identifier);
            if (!$product) {
                $product = $this->fetchByVariationIdentifier($identifier, $selectedVariationId);
            }
        }

        if ($product) {
            $product->load([
                'category:id,name,slug,image',
                'brand:id,name,image',
                'unit:id,name',
                'images:id,product_id,image',
                'variations' => function ($q) use ($locationId) {
                    $q->select([
                        'id',
                        'product_id',
                        'name',
                        'sub_sku',
                        'purchase_price',
                        'sell_price',
                        'created_at'
                    ]);

                    if ($locationId) {
                        $q->with(['stocks' => function ($sq) use ($locationId) {
                            $sq->where('location_id', $locationId)
                                ->select(['id', 'product_id', 'variation_id', 'location_id', 'qty_available']);
                        }]);
                    } else {
                        $q->with('stocks:id,product_id,variation_id,location_id,qty_available');
                    }
                }
            ]);

            $product->selected_variation_id = $selectedVariationId;
        }

        return $product;
    }

    private function fetchByProductIdentifier(string|int $identifier): ?Product
    {
        return Product::query()
            ->where('status', 1)
            ->where('is_ecom', 1)
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('slug', $identifier)
                        ->orWhere('sku', $identifier);
                }
            })
            ->first();
    }

    private function fetchByVariationIdentifier(string|int $identifier, ?int &$selectedVariationId): ?Product
    {
        $variation = Variation::select('id', 'product_id', 'sub_sku')
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('sub_sku', $identifier);
                }
            })
            ->first();

        if ($variation) {
            $selectedVariationId = $variation->id;

            return Product::query()
                ->where('status', 1)
                ->where('is_ecom', 1)
                ->where('id', $variation->product_id)
                ->first();
        }

        return null;
    }

    public function getCategories()
    {
        return Category::select('id', 'name', 'bd_name', 'slug', 'image', 'parent_id')
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
}