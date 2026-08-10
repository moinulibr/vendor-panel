<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string','min:6'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [

            'current_password.required' => 'পুরাতন পাসওয়ার্ড দিয়ে রিসেট করতে আপনার বর্তমান পাসওয়ার্ড প্রদান করুন।',
            'password.required'        => 'নতুন পাসওয়ার্ড প্রদান বাধ্যতামূলক।',
            'password.confirmed'       => 'পাসওয়ার্ড কনফার্মেশনের সাথে মিলছে না।',
        ];
    }
}