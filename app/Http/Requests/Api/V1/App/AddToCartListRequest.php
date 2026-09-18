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
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type !== UserType::DEALER;
        
        return [
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
            'user_base_id.required' => 'The user base id field is required when you are logged in as a SR user, but have to be acted as a dealer or others user.',
        ];
    }
}
