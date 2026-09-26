<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subtotal = $this->quantity * $this->unit_price;
        $totalDiscount = $this->discount_type === 'percentage'
            ? ($subtotal * ($this->discount_amount / 100))
            : ($this->discount_amount * $this->quantity);

        // Product & Variation references
        $product = $this->product;
        $variation = $this->variation;

        // Image selection logic: Variation Image > Product Main Image
        $imagePath = $variation?->image ?: $product?->image;
        $imageUrl = $imagePath ? getImage('products', $imagePath) : null;

        // SKU selection logic
        $finalSku = $variation?->sub_sku ?: $product?->sku;

        // Variant Attributes Formatting Logic (matches ProductDetailsResource)
        $formattedAttributes = "";
        $variationName = null;

        if ($variation && $variation->name && $variation->name !== 'dummy') {
            $variationName = $variation->name;
            if ($product && isset($product->variants)) {
                $rawAttributes = is_string($product->variants)
                    ? json_decode($product->variants, true)
                    : ($product->variants ?? []);

                $formattedAttributes = self::formatVariantAttributes($rawAttributes, $variationName);
            }
        }

        return [
            'id'                 => $this->id,
            'product_id'         => $this->product_id,
            'product_name'       => $product?->name,
            'product_name_bangla' => $product?->name_bangla,
            'product_slug'       => $product?->slug,
            'sku'                => $finalSku,
            'variation_id'       => $this->variation_id,
            'variation_name'     => $variationName,
            'variant_attributes' => $formattedAttributes,
            'image_url'          => $imageUrl,
            'quantity'           => (int) $this->quantity,
            'unit_price'         => (float) $this->unit_price,
            'discount_amount'    => (float) $this->discount_amount,
            'discount_type'      => $this->discount_type ?? 'fixed',
            'sub_total'          => (float) $subtotal,
            'total_discount'     => (float) $totalDiscount,
            'net_total'          => (float) max(0, $subtotal - $totalDiscount),
        ];
    }

    /**
     * Helper Method: Format Variant Attributes matching ProductDetailsResource logic
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
