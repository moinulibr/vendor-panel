<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\App\ProductVariationResource;
use App\Http\Resources\Api\V1\App\ProductImageResource;
class ProductResource extends JsonResource
{
    //This product resource class is used for showing product list [limited data]
    public function toArray(Request $request): array
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
}