<?php

namespace App\Http\Requests\Api\V1\App;

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
            'name'        => ['required', 'string', 'max:100'],
            'email'       => [
                'nullable',
                'string',
                'email',
                'max:200',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            /*'mobile'      => [
                'required',
                'string',
                'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/',
                Rule::unique('users', 'mobile')->ignore($userId)
            ],*/
            'shop_name'   => ['required_if:access_type,2', 'nullable', 'string', 'max:150'],
            'address'     => ['nullable', 'string'],
            'trade_license' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            //'mobile.unique'         => 'এই নম্বরটি অন্য একটি অ্যাকাউন্টে ব্যবহৃত হচ্ছে।',
            'email.unique'          => 'এই ইমেইলটি অন্য একটি অ্যাকাউন্টে ব্যবহৃত হচ্ছে।',
            'shop_name.required_if' => 'রিটেইলার অ্যাকাউন্টের জন্য দোকানের নাম দেওয়া বাধ্যতামূলক।',
        ];
    }
}