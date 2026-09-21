<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotationRequest extends FormRequest
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
            'items.*.product_id'     => 'required_with:items|integer',
            'items.*.variation_id'   => 'nullable|integer',
            'items.*.quantity'       => 'required_with:items|numeric|min:1',
            'items.*.unit_price'     => 'required_with:items|numeric|min:0',
        ];
    }
}
