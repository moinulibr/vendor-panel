<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile'     => ['required', 'string'],
            'login_type' => ['required', 'in:password,otp'],
            'password'   => ['required_if:login_type,password', 'nullable', 'string'],
            'otp'        => ['required_if:login_type,otp', 'nullable', 'string', 'digits:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required'     => 'মোবাইল নম্বর প্রয়োজন।',
            'login_type.in'       => 'লগইন টাইপ অবশ্যই password অথবা otp হতে হবে।',
            'password.required_if' => 'পাসওয়ার্ড দিয়ে লগইন করতে পাসওয়ার্ড প্রদান করুন।',
            'otp.required_if'      => 'ওটিপি দিয়ে লগইন করতে ৪-ডিজিটের ওটিপি প্রদান করুন।',
        ];
    }
}