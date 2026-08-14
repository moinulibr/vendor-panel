<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductVariationResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'image_url' => $this->image ? getImage('products', $this->image) : null,
            'sell_price' => (float) $this->sell_price,
            'mrp_price' => (float) $this->sell_price + 20,
            'purchase_price' => (float) $this->purchase_price,
            //'min_price' => (float) $this->min_price,
            //'max_price' => (float) $this->max_price,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'variations' => ProductVariationResource::collection($this->whenLoaded('variations')),
        ];
    }
}