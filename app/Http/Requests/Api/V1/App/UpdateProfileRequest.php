<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:200',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'mobile' => [
                'nullable',
                'string',
                'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/',
                Rule::unique('users', 'mobile')->ignore($userId)
            ],
            'shop_name' => [
                Rule::requiredIf(function () {
                    return $this->user()->user_type == UserType::DEALER;
                }),
                'nullable',
                'string',
                'max:150'
            ],
            'address'       => ['nullable', 'string'],
            'trade_license' => ['nullable', 'string', 'max:100'],
            'license_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'This email is already registered.',
            'mobile.unique'      => 'This mobile number is already registered.',
            'shop_name.required' => 'Dealer account requires a shop name.',
        ];
    }
}