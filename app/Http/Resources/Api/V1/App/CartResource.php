<?php

namespace App\Http\Resources\Api\V1\App;
use App\Http\Resources\Api\V1\App\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'summary' => $this->resource['summary'],
            'items'   => CartItemResource::collection($this->resource['items']),
            'delivery' => $this->resource['delivery_system'],
            'offers' => $this->resource['offers'],
            'user' => $this->resource['user'],
        ];
    }
}
