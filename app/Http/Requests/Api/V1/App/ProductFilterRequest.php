<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'               => 'nullable|string|max:255',
            'category_ids'    => 'nullable|string',
            'sub_category_ids' => 'nullable|string',
            'brand_id'        => 'nullable|integer|exists:brands,id',
            'user_id'         => 'nullable|integer|exists:users,id',//vendor
            'location_id'     => 'nullable|integer',
            //'min_price'       => 'nullable|numeric|min:0',
            //'max_price'       => 'nullable|numeric|gte:min_price',
            'sort_by'         => 'nullable|string|in:latest,price_low,price_high,name_asc,name_desc',
            'per_page'        => 'nullable|integer|min:1|max:100',
        ];
    }
}