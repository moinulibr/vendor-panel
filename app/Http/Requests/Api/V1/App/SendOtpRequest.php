<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile'  => ['required', 'string', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'purpose' => ['required', 'string', 'in:login,register,reset_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => 'মোবাইল নম্বর দেওয়া বাধ্যতামূলক।',
            'mobile.regex'    => 'সঠিক ১-ডিজিটের বাংলাদেশি নম্বর দিন।',
            'purpose.in'      => 'উদ্দেশ্যটি (purpose) সঠিক নয়।',
        ];
    }
}