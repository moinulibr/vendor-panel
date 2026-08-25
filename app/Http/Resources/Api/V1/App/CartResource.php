<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subtotal = $this->quantity * $this->unit_price;
        $totalDiscount = $this->discount_type === 'percentage'
            ? ($subtotal * ($this->discount_amount / 100))
            : ($this->discount_amount * $this->quantity);

        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'product_name'    => $this->product->name ?? null,
            'variation_id'    => $this->variation_id,
            'variation_name'  => $this->variation->name ?? null,
            'quantity'        => (int) $this->quantity,
            'unit_price'      => (float) $this->unit_price,
            'discount_amount' => (float) $this->discount_amount,
            'sub_total'       => (float) $subtotal,
            'net_total'       => (float) ($subtotal - $totalDiscount),
        ];
    }
}
