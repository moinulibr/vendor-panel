<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:100'],
            'mobile'    => ['required', 'string', 'unique:users,mobile', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'otp'       => ['required', 'string', 'digits:4'],
            'password'  => ['nullable', 'string', 'min:6'],
            'user_type' => ['required', 'in:2,2'],
            'shop_name' => ['required_if:user_type,retailer', 'nullable', 'string', 'max:150'],
            'address'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.unique'         => 'এই নম্বর দিয়ে ইতিমধ্যে অ্যাকাউন্ট রয়েছে।',
            'shop_name.required_if' => 'রিটেইলার অ্যাকাউন্টের জন্য দোকানের নাম দেওয়া বাধ্যতামূলক।',
        ];
    }
}