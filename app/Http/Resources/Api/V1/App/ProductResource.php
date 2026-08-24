<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;

class ProductResource extends JsonResource
{
    /**
     * Override collection method to flatten variable products into individual items.
     */
    public static function collection($resource)
    {
        $collection = $resource->getCollection()->flatMap(function ($product) {

            if ($product->type === 'variable' && $product->relationLoaded('variations') && $product->variations->isNotEmpty()) {

                $rawAttributes = is_string($product->variants) ? json_decode($product->variants, true) : ($product->variants ?? []);

                return $product->variations->map(function ($variant) use ($product, $rawAttributes) {
                    $stockQty = $variant->relationLoaded('stocks') ? $variant->stocks->sum('qty_available') : 0;

                    $singleVariantAttributes = self::formatVariantAttributes($rawAttributes, $variant->name);

                    return [
                        'id'           => $product->id,
                        'parent_id'    => $product->id,
                        'variation_id' => $variant->id,
                        'p_details' => [
                            'id'   => $variant->id,
                            'type' => "variable"
                        ],
                        'is_variant'   => true,
                        'type'         => 'variable',
                        'name'         => $product->name . ' - ' . $variant->name,
                        'name_bangla'  => $product->name_bangla,
                        'slug'         => $product->slug,
                        'sku'          => $variant->sub_sku,
                        'parent_sku'   => $product->sku,
                        'image_url'    => $product->image ? getImage('products', $product->image) : null,
                        'sell_price'   => (float) ($variant->sell_price ?? $product->sell_price),
                        'mrp_price'    => (float) (($variant->sell_price ?? $product->sell_price) + 20),
                        'category_id'  => $product->category_id,
                        'brand_id'     => $product->brand_id,
                        'is_feature'   => (bool) $product->is_feature,
                        'stock_qty'    => $stockQty,
                        'images'       => ProductImageResource::collection($product->images),
                        'category_name' => $product->category?->name ?? "N/L",
                        'brand_name'    => $product->brand?->name ?? "N/L",

                        'variant_attributes' => $singleVariantAttributes
                    ];
                });
            }

            return [[
                'id'           => $product->id,
                'parent_id'    => $product->id,
                'variation_id' => null,
                'p_details' => [
                    'id'   => $product->id,
                    'type' => "single"
                ],
                'is_variant'   => false,
                'type'         => 'single',
                'name'         => $product->name,
                'name_bangla'  => $product->name_bangla,
                'slug'         => $product->slug,
                'sku'          => $product->sku,
                'parent_sku'   => $product->sku,
                'image_url'    => $product->image ? getImage('products', $product->image) : null,
                'sell_price'   => (float) $product->sell_price,
                'mrp_price'    => (float) ($product->sell_price + 20),
                'category_id'  => $product->category_id,
                'brand_id'     => $product->brand_id,
                'is_feature'   => (bool) $product->is_feature,
                'stock_qty'    => 0,
                'images'       => ProductImageResource::collection($product->images),
                'category_name' => $product->category?->name ?? "N/L",
                'brand_name'    => $product->brand?->name ?? "N/L",
                'variant_attributes' => ""
            ]];
        });

        $resource->setCollection($collection);

        return parent::collection($resource);
    }

    /**
     * Helper to map JSON variant attributes with specific variant name
     * Output Example: "Color: Black, Size: S, material: gold"
     */
    private static function formatVariantAttributes(array $rawAttributes, ?string $variantName): string
    {
        if (empty($rawAttributes) || empty($variantName)) {
            return "";
        }

        // Variant name to array format ("Black-S-gold" -> ["Black", "S", "gold"])
        $variantValues = array_map('trim', explode('-', $variantName));
        $matchedPairs = [];

        foreach ($rawAttributes as $group) {
            if (!is_array($group)) continue;

            foreach ($group as $attributeKey => $values) {
                if (!is_array($values)) continue;

                foreach ($values as $value) {
                    // Variant value matching (Case-insensitive check)
                    foreach ($variantValues as $vVal) {
                        if (strcasecmp($vVal, trim($value)) === 0) {
                            $matchedPairs[] = ucfirst($attributeKey) . ': ' . $vVal;
                            break 2;// Break both loops
                        }
                    }
                }
            }
        }

        return implode(', ', $matchedPairs);
    }

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}


    //This product resource class is used for showing product list [limited data]
   /* public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'name_bangla' => $this->name_bangla,
            'slug'        => $this->slug,
            'sku'         => $this->sku,
            'image_url'   => $this->image ? getImage('products', $this->image) : null,
            'sell_price'  => $this->sell_price,
            'mrp_price'   => $this->sell_price + 20,
            //'min_price'   => $this->min_price,
            //'max_price'   => $this->max_price,
            'type'        => $this->type,
            'category_id'  => $this->category_id,
            'brand_id'     => $this->brand_id,
            'is_feature'   => (bool) $this->is_feature,
            'images'      => ProductImageResource::collection($this->whenLoaded('images')),
            'variations'  => $this->type == 'variable' ? ProductVariationResource::collection($this->whenLoaded('variations')) : false,
            'created_at'  => $this->created_at,
        ];
    }
    */