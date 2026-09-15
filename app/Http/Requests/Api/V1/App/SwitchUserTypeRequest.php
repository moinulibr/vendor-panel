<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class SwitchUserTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        //retailer_id
        //retailer_user_id
        return [
            'user_id'           => ['required', 'integer', 'exists:users,id'],
            'retailer_id'       => ['required', 'integer', 'exists:retailers,id'],
            'from_user_type_id' => ['required', 'integer'],
            'to_user_type_id'   => ['required', 'integer'],
            'shop_name'         => ['required', 'string', 'max:255'],
            'trade_license'     => ['nullable', 'string', 'max:100'],
            'license_image'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}