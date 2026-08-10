<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile'       => ['required', 'string', 'exists:users,mobile'],
            //'reset_by'     => ['required', 'in:otp,old_password'],
            //'otp'          => ['required_if:reset_by,otp', 'nullable', 'string', 'digits:4'],
            //'old_password' => ['required_if:reset_by,old_password', 'nullable', 'string'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.exists'            => 'এই নম্বরে কোনো অ্যাকাউন্ট পাওয়া যায়নি।',
            //'reset_by.in'              => 'রিসেট টাইপ অবশ্যই otp অথবা old_password হতে হবে।',
            //'otp.required_if'          => 'ওটিপি দিয়ে রিসেট করতে ৪-ডিজিটের ওটিপি প্রদান করুন।',
            //'old_password.required_if' => 'পুরাতন পাসওয়ার্ড দিয়ে রিসেট করতে আপনার বর্তমান পাসওয়ার্ড প্রদান করুন।',
            'password.required'        => 'নতুন পাসওয়ার্ড প্রদান বাধ্যতামূলক।',
            'password.confirmed'       => 'পাসওয়ার্ড কনফার্মেশনের সাথে মিলছে না।',
        ];
    }
}