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
            'mobile'   => ['required', 'string', 'exists:users,mobile'],
            'otp'      => ['required', 'string', 'digits:4'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.exists'      => 'এই নম্বরে কোনো অ্যাকাউন্ট পাওয়া যায়নি।',
            'password.confirmed' => 'পাসওয়ার্ড কনফার্মেশনের সাথে মিলছে না।',
        ];
    }
}