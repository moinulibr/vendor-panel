<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductVariationResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;
use App\Utils\UserType;
use Illuminate\Support\Facades\Session;

class ProductDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userType = Session::get('userTypeForProductDetailFromSession', UserType::GENERAL_APP_CUSTOMER); // 5 = Dealer, 9 = Regular Customer (default)
        Session::put('userTypeForProductDetailFromSession', null);

        $selectedVariationObj = null;
        $formattedAttributes = "";

        if ($this->selected_variation_id && $this->relationLoaded('variations')) {
            $selectedVariationObj = $this->variations->firstWhere('id', $this->selected_variation_id);

            if ($selectedVariationObj) {
                $rawAttributes = is_string($this->variants) ? json_decode($this->variants, true) : ($this->variants ?? []);
                $formattedAttributes = self::formatVariantAttributes($rawAttributes, $selectedVariationObj->name);
            }
        }

        $finalSku = $selectedVariationObj ? $selectedVariationObj->sub_sku : $this->sku;

        // FIX 1: Pass $selectedVariationObj to calculate exact variation prices based on userType
        $prices = self::userTypeWisePriceSetup($this->resource, $selectedVariationObj, $userType);

        return [
            'id'                    => $this->id,
            'variation_id'          => $this->selected_variation_id ?? null,
            'name'                  => $this->name,
            'name_bangla'           => $this->name_bangla,
            'slug'                  => $this->slug,
            'sku'                   => $finalSku,
            'parent_sku'            => $this->sku,
            'image_url'             => $this->image ? getImage('products', $this->image) : null,
            'sell_price'            => (float) $prices['sell_price'],
            'mrp_price'             => (float) $prices['mrp_price'],
            'type'                  => $this->type,
            'stock_manage'          => (bool) $this->stock_manage,

            // Variant Attributes Logic (e.g. Color: Red, Size: S)
            'variant_attributes'    => $formattedAttributes,

            // Selected Variation Payload
            'selected_variation_id' => $this->selected_variation_id ?? null,
            'selected_variation'    => $selectedVariationObj ? new ProductVariationResource($selectedVariationObj) : null,

            // Details & Specifications
            'description'           => $this->description,
            'specification'         => $this->specification,

            // Delivery & Policy Details
            'warranty_available'    => (bool) $this->warranty_available,
            'warranty_days'         => $this->warranty_days,
            'warranty_note'         => $this->warranty_note,
            'return_available'      => (bool) $this->return_available,
            'return_days'           => $this->return_days,
            'return_note'           => $this->return_note,
            'estimate_delivery_day' => $this->estimate_delivery_day,

            // Flags
            'is_feature'            => (bool) $this->is_feature,
            'is_reco'               => (bool) $this->is_reco,

            // Relations
            'category_id'           => $this->category_id,
            'brand_id'              => $this->brand_id,
            'unit_id'               => $this->unit_id,
            'category'              => new CategoryResource($this->whenLoaded('category')),
            'brand'                 => new BrandResource($this->whenLoaded('brand')),
            'unit'                  => new UnitResource($this->whenLoaded('unit')),
            'images'                => ProductImageResource::collection($this->whenLoaded('images')),
            'variations'            => $this->type == 'variable' ? ProductVariationResource::collection($this->whenLoaded('variations')) : [],
        ];
    }

    /**
     * Helper to calculate dynamic sell_price & mrp_price based on User Type.
     * 
     * User Type 5 = Dealer Price (Fallback to Sell Price if empty)
     * User Type 9 = Regular / Wholesale Price (Fallback to Sell Price if empty)
     */
    private static function userTypeWisePriceSetup($product, $variant = null, int $userType = UserType::GENERAL_APP_CUSTOMER): array
    {
        // FIX 2: Correct Logging using ->toArray() on model
        // \Log::info('Product Model:', $product ? $product->toArray() : []);

        // Priority 1: Check Variant prices if available, else fallback to Main Product prices
        $baseSellPrice = $variant?->sell_price ?? $product?->sell_price ?? 0;
        $baseMrp       = $variant?->mrp ?? $product?->mrp ?? ($baseSellPrice + 20);

        if ($userType == UserType::DEALER) {
            // Dealer User (5)
            $dealerPrice = $variant?->dealer_price ?? $product?->dealer_price;
            $finalSellPrice = !empty($dealerPrice) ? (float) $dealerPrice : (float) $baseSellPrice;
        } elseif ($userType == UserType::GENERAL_APP_CUSTOMER) {
            // Regular / Wholesale Customer (9)
            $wholesalePrice = $variant?->wholesale_price ?? $product?->wholesale_price;
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
     * Helper Method: Variant Attributes Matcher
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
}
