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

            'current_password.required' => 'Current Password field is required',
            'password.required'        => 'Password field is required',
            'password.confirmed'       => 'Password confirmation does not match',
        ];
    }
}