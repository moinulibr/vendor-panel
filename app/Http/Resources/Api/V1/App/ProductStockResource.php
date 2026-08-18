<?php
namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'product_id'     => $this->product_id,
            'variation_id'   => $this->variation_id,
            'location_id'    => $this->location_id,
            'stock'          => $this->when(isset($this->qty_available), (int) $this->qty_available),
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}