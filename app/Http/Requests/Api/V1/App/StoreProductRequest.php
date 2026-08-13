<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|integer|exists:categories,id',
            'user_id'         => 'required|integer|exists:users,id',
            'brand_id'        => 'nullable|integer|exists:brands,id',
            'sub_category_id' => 'nullable|integer',
            'sku'             => 'required|string|max:100',
            'type'            => 'required|string|in:single,variable',
            'purchase_price'  => 'required|numeric|min:0',
            'sell_price'      => 'required|numeric|min:0',
            'status'          => 'nullable|boolean',
            'is_ecom'         => 'nullable|boolean',
            'variations'      => 'nullable|array',
            'variations.*.name' => 'required_if:type,variable|string',
            'variations.*.sell_price' => 'required_if:type,variable|numeric',
        ];
    }
}