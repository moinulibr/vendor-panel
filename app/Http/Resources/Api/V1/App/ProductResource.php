<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'image_url' => $this->image ? asset('storage/products/' . $this->image) : null,
            'min_price' => (float) $this->min_price,
            'max_price' => (float) $this->max_price,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'variations' => $this->whenLoaded('variations'),
        ];
    }
}