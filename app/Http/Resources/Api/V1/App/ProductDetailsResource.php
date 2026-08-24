<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductVariationResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;

class ProductDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $selectedVariationObj = null;
        $formattedAttributes = "";

        if ($this->selected_variation_id && $this->relationLoaded('variations')) {
            $selectedVariationObj = $this->variations->firstWhere('id', $this->selected_variation_id);

            if ($selectedVariationObj) {
                $rawAttributes = is_string($this->variants) ? json_decode($this->variants, true) : ($this->variants ?? []);
                $formattedAttributes = self::formatVariantAttributes($rawAttributes, $selectedVariationObj->name);
            }
        }

        $finalSellPrice = $selectedVariationObj ? ($selectedVariationObj->sell_price ?? $this->sell_price) : $this->sell_price;
        $finalPurchasePrice = $selectedVariationObj ? ($selectedVariationObj->purchase_price ?? $this->purchase_price) : $this->purchase_price;
        $finalSku = $selectedVariationObj ? $selectedVariationObj->sub_sku : $this->sku;
        $finalName = $selectedVariationObj ? $this->name . ' - ' . $selectedVariationObj->name : $this->name;

        return [
            'id'                    => $this->id,
            'variation_id'          => $this->selected_variation_id ?? null,
            'name'                  => $this->name,// ." - ". $formattedAttributes,
            'name_bangla'           => $this->name_bangla,
            'slug'                  => $this->slug,
            'sku'                   => $finalSku,
            'parent_sku'            => $this->sku,
            'image_url'             => $this->image ? getImage('products', $this->image) : null,
            'sell_price'            => (float) $finalSellPrice,
            'mrp_price'             => (float) ($finalSellPrice + 20),
            'purchase_price'        => (float) $finalPurchasePrice,
            'type'                  => $this->type,
            'stock_manage'          => (bool) $this->stock_manage,

            // Variant Attributes Logic (e.g. Color: Red, Size: S, Material: Gold)
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
     * Helper Method: Variant Attributes Matcher
     */
    private static function formatVariantAttributes(array $rawAttributes, ?string $variantName): string
    {
        if (empty($rawAttributes) || empty($variantName)) {
            return "";
        }

        // Variant name (e.g. "Black-S-gold" -> ["Black", "S", "gold"])
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
