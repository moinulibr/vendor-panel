<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'retailer_user_id' => $this->user_id,
            'created_by '   => $this->created_by,
            'type'         => $this->type,
            'product_id'   => $this->product_id,
            'product_name' => $this->product->name ?? null,
            'variation_id'    => $this->variation_id,
            'variation_name'  => $this->variation->name ?? null,
            'sell_price'   => $this->product->sell_price ?? null,
            'image_url'    => $this->product->image ? getImage('products', $this->product->image) : null,
        ];
    }
}
