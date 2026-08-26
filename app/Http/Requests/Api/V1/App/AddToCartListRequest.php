<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type !== UserType::RETAILER;
        
        return [
            'retailer_user_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'retailer_user_id.required' => 'The retailer id field is required when you are acting as an SR or non-retailer user.',
        ];
    }
}
