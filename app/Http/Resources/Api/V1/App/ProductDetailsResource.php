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
        $selectedVariation = null;

        if ($this->selected_variation_id && $this->relationLoaded('variations')) {
            $selectedVarObj = $this->variations->firstWhere('id', $this->selected_variation_id);
            if ($selectedVarObj) {
                $selectedVariation = new ProductVariationResource($selectedVarObj);
            }
        }

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'name_bangla'           => $this->name_bangla,
            'slug'                  => $this->slug,
            'sku'                   => $this->sku,
            'image_url'             => $this->image ? getImage('products', $this->image) : null,
            'sell_price'            => (float) $this->sell_price,
            'mrp_price'             => (float) ($this->sell_price + 20),
            'purchase_price'        => (float) $this->purchase_price,
            'type'                  => $this->type,
            'stock_manage'          => (bool) $this->stock_manage,

            // Selected Variation Payload
            'selected_variation_id' => $this->selected_variation_id ?? null,
            'selected_variation'    => $selectedVariation,

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
    /*public function toArray(Request $request): array
    {
        $selectedVariation = null;
        if ($this->selected_variation_id && $this->relationLoaded('variations')) {
            $selectedVariation = $this->variations->firstWhere('id', $this->selected_variation_id);
        }
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'name_bangla'           => $this->name_bangla,
            'slug'                  => $this->slug,
            'sku'                   => $this->sku,
            'image_url'             => $this->image ? getImage('products', $this->image) : null,
            'sell_price'            => (float) $this->sell_price,
            'mrp_price'             => (float) $this->sell_price + 20,
            'purchase_price'        => (float) $this->purchase_price,
            'type'                  => $this->type,
            'stock_manage'          => (bool) $this->stock_manage,
            //'stock_alert'           => $this->stock_alert,
            // Selected Variation Info 
            'selected_variation_id'  => $this->selected_variation_id ?? null,
            'selected_variation'     => $selectedVariation,
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
            'variations'            => $this->type == 'variable' ? ProductVariationResource::collection($this->whenLoaded('variations')) : false,
        ];
    }
    */
}