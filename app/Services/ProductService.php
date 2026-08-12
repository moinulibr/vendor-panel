<?php

namespace App\Services;

use App\Repositories\Product\Interface\ProductRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

    public function saveProduct(array $data, $id = null)
    {
        return DB::transaction(function () use ($data, $id) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            }

            // Sync denormalized prices for performance
            if ($data['type'] === 'variable' && !empty($data['variations'])) {
                $prices = array_column($data['variations'], 'sell_price');
                $data['min_price'] = min($prices);
                $data['max_price'] = max($prices);
            } else {
                $data['min_price'] = $data['sell_price'] ?? 0;
                $data['max_price'] = $data['sell_price'] ?? 0;
            }

            $product = $this->productRepository->createOrUpdate($data, $id);

            if (!empty($data['variations'])) {
                $variationIds = [];
                foreach ($data['variations'] as $variation) {
                    $item = $product->variations()->updateOrCreate(
                        ['sub_sku' => $variation['sub_sku'] ?? $product->sku . '-' . Str::random(3)],
                        $variation
                    );
                    $variationIds[] = $item->id;
                }
                $product->variations()->whereNotIn('id', $variationIds)->delete();
            }

            return $product;
        });
    }
}