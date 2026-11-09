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
            'note'                   => 'nullable|string|max:500',
            'items'                  => 'nullable|array|min:1',
            'items.*.product_id'     => 'required_with:items|integer|exists:products,id',
            'items.*.variation_id'   => 'nullable|integer|exists:variations,id',
            'items.*.quantity'       => 'required_with:items|integer|min:1',
            'items.*.unit_price'     => 'required_with:items|numeric|min:0',
            'items.*.discount'       => 'nullable|numeric|min:0',
        ];
    }
}
