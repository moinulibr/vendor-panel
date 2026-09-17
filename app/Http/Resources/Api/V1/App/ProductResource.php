<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;
use App\Utils\UserType;
use Illuminate\Support\Facades\Session;

class ProductResource extends JsonResource
{
    /**
     * Override collection method to flatten variable products into individual items.
     */
    public static function collection($resource)
    {
        $userType = Session::get('userTypeForProductListFromSession', UserType::GENERAL_APP_CUSTOMER); // 5 = Dealer, 9 = Regular Customer (default)
        Session::put('userTypeForProductListFromSession', null);

        $collection = $resource->getCollection()->flatMap(function ($product) use ($userType) {

            if ($product->type === 'variable' && $product->relationLoaded('variations') && $product->variations->isNotEmpty()) {

                $rawAttributes = is_string($product->variants) ? json_decode($product->variants, true) : ($product->variants ?? []);

                return $product->variations->map(function ($variant) use ($product, $rawAttributes, $userType) {
                    $stockQty = $variant->relationLoaded('stocks') ? $variant->stocks->sum('qty_available') : 0;

                    $singleVariantAttributes = self::formatVariantAttributes($rawAttributes, $variant->name);

                    // Dynamic Price
                    $prices = self::userTypeWisePriceSetup($product, $variant, $userType);

                    return [
                        'id'           => $product->id,
                        'parent_id'    => $product->id,
                        'variation_id' => $variant->id,
                        'product_base_id' => $variant->id,
                        'p_details' => [
                            'id'   => $variant->id,
                            'type' => "variable"
                        ],
                        'is_variant'   => true,
                        'type'         => 'variable',
                        'name'         => $product->name,
                        'name_bangla'  => $product->name_bangla,
                        'slug'         => $product->slug,
                        'sku'          => $variant->sub_sku,
                        'parent_sku'   => $product->sku,
                        'image_url'    => $product->image ? getImage('products', $product->image) : null,
                        'sell_price'   => $prices['sell_price'],
                        'mrp_price'    => $prices['mrp_price'],
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

            // Dynamic Price Calculation for Single Product
            $prices = self::userTypeWisePriceSetup($product, null, $userType);
            $pv = $product->variations->first();
            return [[
                'id'           => $product->id,
                'parent_id'    => $product->id,
                'variation_id' =>  $pv?->id ?? $product->id,
                'product_base_id' =>  $pv?->id ??  $product->id,
                'p_details' => [
                    //'id'   => $product->id,
                    'id'   => $pv?->id ??  $product->id,
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
                'sell_price'   => $prices['sell_price'],
                'mrp_price'    => $prices['mrp_price'],
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
     * Helper to calculate dynamic sell_price & mrp_price based on User Type.
     * 
     * User Type 5 = Dealer Price (Fallback to Sell Price if empty)
     * User Type 9 = Regular / Wholesale Price (Fallback to Sell Price if empty)
     */
    private static function userTypeWisePriceSetup($product, $variant = null, int $userType = UserType::GENERAL_APP_CUSTOMER): array
    {
        $baseSellPrice = $variant?->sell_price ?? $product->sell_price ?? 0;
        $baseMrp = $variant?->mrp ?? $product->mrp ?? ($baseSellPrice + 20);

        if ($userType == UserType::DEALER) {
            // Dealer User (5)
            $dealerPrice = $variant?->dealer_price ?? $product->dealer_price;
            $finalSellPrice = !empty($dealerPrice) ? (float) $dealerPrice : (float) $baseSellPrice;
        } elseif ($userType == UserType::GENERAL_APP_CUSTOMER) {
            // Regular / Wholesale Customer (9)
            $wholesalePrice = $variant?->wholesale_price ?? $product->wholesale_price;
            $finalSellPrice = !empty($wholesalePrice) ? (float) $wholesalePrice : (float) $baseSellPrice;
        } else {
            // Default Customer Price
            $finalSellPrice = (float) $baseSellPrice;
        }

        return [
            'sell_price' => $finalSellPrice,
            'mrp_price'  => (float) $baseMrp,
        ];
    }

    /**
     * Helper to map JSON variant attributes with specific variant name
     */
    private static function formatVariantAttributes(array $rawAttributes, ?string $variantName): string
    {
        if (empty($rawAttributes) || empty($variantName)) {
            return "";
        }

        $variantValues = array_map('trim', explode('-', $variantName));
        $matchedPairs = [];

        foreach ($rawAttributes as $group) {
            if (!is_array($group)) continue;

            foreach ($group as $attributeKey => $values) {
                if (!is_array($values)) continue;

                foreach ($values as $value) {
                    foreach ($variantValues as $vVal) {
                        if (strcasecmp($vVal, trim($value)) === 0) {
                            $matchedPairs[] = ucfirst($attributeKey) . ': ' . $vVal;
                            break 2;
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