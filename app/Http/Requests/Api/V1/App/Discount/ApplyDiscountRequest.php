<?php

namespace App\Http\Requests\Api\V1\App\Discount;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class ApplyDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type !== UserType::DEALER;

        return [
            'discount_id'  => 'required|integer|exists:discounts,id',
            'user_base_id' => [
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
