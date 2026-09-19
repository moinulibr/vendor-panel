<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type == UserType::SR;
        
        return [
            'product_id'      => 'required|integer|exists:products,id',
            'product_base_id' => 'required|integer|exists:variations,id',
            //'type'         => 'required|string|in:single,variable',
            'variation_id'  => 'nullable|integer|exists:variations,id',
            'quantity'      => 'required|integer|min:1',
            'user_base_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'user_base_id.required' => 'The User base id field is required when you are acting as an SR user.',
        ];
    }
}
