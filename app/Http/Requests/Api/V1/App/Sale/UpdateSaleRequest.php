<?php

namespace App\Http\Requests\Api\V1\App\Sale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason'                 => 'required|string|max:500',
            'shipping_charge'        => 'nullable|numeric|min:0',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|integer|exists:products,id',
            'items.*.variation_id'   => 'nullable|integer|exists:variations,id',
            'items.*.qty'            => 'required|integer|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0', // Used only if it's a NEW item
        ];
    }
}
