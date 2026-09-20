<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class DeleteShippingAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type == UserType::SR;
        
        return [
            'user_base_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
            'user_detail_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:user_details,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'user_base_id.required' => 'The user base id field is required when you are acting as an SR  user.',
            'user_detail_id.required' => 'The user detail id field is required when you are acting as an SR  user.',
        ];
    }
}
