<?php

namespace App\Repositories\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variation;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    protected $variableStatus = 1;
    protected $isEnableForMobileApp = 1;

    public function getFilteredProducts(array $filters, int $perPage = 20): Paginator
    {
        $variableStatus = $this->variableStatus;
        $search = !empty($filters['q']) ? trim($filters['q']) : null;
        $locationId = $filters['location_id'] ?? null;

        $query = Product::query()
            ->where('is_new', 0)
            ->where('status', 1)
            ->where('is_mobile_app', $this->isEnableForMobileApp);

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

        if (isset($filters['min_price'], $filters['max_price'])) {
            $query->whereBetween('min_price', [$filters['min_price'], $filters['max_price']]);
        }

        if ($search) {
            $query->where(function (Builder $mainQuery) use ($search, $variableStatus) {
                $mainQuery->where('sku', 'LIKE', "{$search}%")
                    ->orWhere('name', 'LIKE', "{$search}%")
                    ->orWhere('name_bangla', 'LIKE', "{$search}%")
                    ->orWhereExists(function ($subQuery) use ($search, $variableStatus) {
                        $subQuery->selectRaw(1)
                            ->from('variations as pv')
                            ->whereColumn('pv.product_id', 'products.id')
                            ->where('pv.status', $variableStatus)
                            ->whereNull('pv.deleted_at')
                            ->where(function ($q) use ($search) {
                                $q->where('pv.sub_sku', 'LIKE', "{$search}%")
                                    ->orWhere('pv.barcode', 'LIKE', "{$search}%")
                                    ->orWhere('pv.name', 'LIKE', "{$search}%");
                            });
                    });
            });

            $query->with(['variations' => function ($v) use ($search, $locationId, $variableStatus) {
                $v->where('status', $variableStatus)->whereNull('deleted_at');
                $v->where(function ($q) use ($search) {
                    $q->where('sub_sku', 'LIKE', "{$search}%")
                        ->orWhere('barcode', 'LIKE', "{$search}%")
                        ->orWhere('name', 'LIKE', "{$search}%")
                        ->orWhereHas('product', function ($p) use ($search) {
                            $p->where('sku', 'LIKE', "{$search}%")
                                ->orWhere('name', 'LIKE', "{$search}%")
                                ->orWhere('name_bangla', 'LIKE', "{$search}%");
                        });
                });
                $this->applyStockRelation($v, $locationId);
            }]);
        } else {
            $query->with(['variations' => function ($v) use ($locationId, $variableStatus) {
                $v->where('status', $variableStatus)->whereNull('deleted_at');
                $this->applyStockRelation($v, $locationId);
            }]);
        }

        $query->with([
            'category:id,name,slug,image',
            'brand:id,name,image',
            'images:id,product_id,variation_id,image',
        ]);

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
            'mrp',
            'dealer_price',
            'wholesale_price',
            'retail_price',
            'type',
            'status',
            'is_feature',
            'user_id',
            'vendor_id',
            'has_variants',
        ])->simplePaginate($perPage);
    }

    private function applyStockRelation($query, ?int $locationId)
    {
        if ($locationId) {
            $query->with(['stocks' => function ($sq) use ($locationId) {
                $sq->where('location_id', $locationId)->select(['id', 'product_id', 'variation_id', 'location_id', 'qty_available']);
            }]);
        } else {
            $query->with('stocks:id,product_id,variation_id,location_id,qty_available');
        }
    }

    public function findBySlugOrId(string|int $identifier, ?int $locationId = null): ?Product
    {
        $selectedVariationId = null;
        $product = $this->fetchByVariationIdentifier($identifier, $selectedVariationId);

        if ($product) {
            $product->load([
                'category:id,name,slug,image',
                'brand:id,name,image',
                'unit:id,name',
                'images:id,product_id,variation_id,image',
                'variations' => function ($q) use ($locationId) {
                    $q->select(['id', 'product_id', 'name', 'sub_sku', 'purchase_price', 'sell_price', 'wholesale_price', 'dealer_price', 'mrp', 'image', 'barcode', 'custom_code', 'created_at']);
                    if ($locationId) {
                        $q->with(['stocks' => function ($sq) use ($locationId) {
                            $sq->where('location_id', $locationId)->select(['id', 'product_id', 'variation_id', 'location_id', 'qty_available']);
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

    public function findVariationBySlugOrId(string|int $identifier, ?int $locationId = null): ?Variation
    {
        return Variation::select('id', 'product_id', 'sub_sku', 'mrp', 'wholesale_price', 'dealer_price', 'retail_price', 'sell_price', 'purchase_price', 'name')
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('sub_sku', $identifier);
                }
            })->first();
    }

    private function fetchByVariationIdentifier(string|int $identifier, ?int &$selectedVariationId): ?Product
    {
        $variation = Variation::select('id', 'product_id', 'sub_sku', 'mrp', 'wholesale_price', 'dealer_price', 'retail_price', 'sell_price', 'name')
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('sub_sku', $identifier);
                }
            })->first();

        if ($variation) {
            $selectedVariationId = $variation->id;
            return Product::query()
                ->where('status', 1)
                ->where('is_mobile_app', $this->isEnableForMobileApp)
                ->where('id', $variation->product_id)
                ->first();
        }

        return null;
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
                    $q->where('slug', $identifier)->orWhere('sku', $identifier);
                }
            })->first();
    }

    public function getCategories()
    {
        return Category::select('id', 'name', 'bd_name', 'slug', 'image', 'parent_id')
            ->where('is_new', 0)->whereNull('parent_id')->get();
    }

    public function getBrands()
    {
        return Brand::select('id', 'name', 'bd_name', 'image')
            ->where('is_new', 0)->orderBy('name', 'asc')->get();
    }

    // =========================================================================
    // 🟢 NEW METHODS FOR INVENTORY (Moved from Service to Repository)
    // =========================================================================

    public function deductStock(int $productId, ?int $variationId, int $qty): void
    {
        $product = Product::find($productId);
        if ($product && ($product->track_stock || $product->stock_manage)) {
            $product->decrement('stock_quantity', $qty);
            $product->increment('total_sold', $qty);
            $product->update(['last_sale_at' => now()]);
        }

        if ($variationId) {
            $variation = Variation::find($variationId);
            if ($variation) {
                $variation->decrement('stock_quantity', $qty);
                $variation->increment('total_sold', $qty);
                $variation->update(['last_sale_at' => now()]);
            }
        }
    }

    public function restoreStock(int $productId, ?int $variationId, int $qty): void
    {
        $product = Product::find($productId);
        if ($product && ($product->track_stock || $product->stock_manage)) {
            $product->increment('stock_quantity', $qty);
            $product->decrement('total_sold', $qty);
        }

        if ($variationId) {
            $variation = Variation::find($variationId);
            if ($variation) {
                $variation->increment('stock_quantity', $qty);
                $variation->decrement('total_sold', $qty);
            }
        }
    }
}
