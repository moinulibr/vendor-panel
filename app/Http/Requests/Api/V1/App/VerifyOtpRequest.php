<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile'  => ['required', 'string'],
            'otp'     => ['required', 'string', 'digits:4'],
            'purpose' => ['required', 'string', 'in:login,register,reset_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => 'মোবাইল নম্বর প্রদান করুন।',
            'otp.required'    => '৪-ডিজিটের ওটিপি প্রদান করুন।',
            'purpose.in'      => 'অবৈধ পারপাস প্রদান করা হয়েছে।',
        ];
    }
}