<?php
namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'product_id'     => $this->product_id,
            'name'           => $this->name,
            'sub_sku'        => $this->sub_sku,
            'purchase_price' => (float) $this->purchase_price,
            'sell_price'     => (float) $this->sell_price,
            'mrp_price'      => (float) ($this->sell_price + 20),
            //'stock'          => $this->when(isset($this->qty_available), (int) $this->qty_available),
            'stock'          => ProductStockResource::collection($this->stocks),
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}