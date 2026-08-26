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
            'items'   => CartItemResource::collection($this->resource['items']),
            'summary' => $this->resource['summary'],
        ];
    }
}
