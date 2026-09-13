<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedUserTypes = [UserType::SR, UserType::DEALER, UserType::GENERAL_APP_CUSTOMER];

        return [
            'name'      => ['required', 'string', 'max:100'],
            'email'      => ['nullable', 'string', 'unique:users,email', 'max:200'],
            'mobile'    => ['required', 'string', 'unique:users,mobile', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            //'otp'       => ['required', 'string', 'digits:4'],
            'password'  => ['nullable', 'string', 'min:6'],
            //'user_type' => ['required', 'in:4,5,9'], //UserType::SR, UserType::DEALER, UserType::GENERAL_APP_CUSTOMER
            'user_type'   => ['required', 'in:' . implode(',', $allowedUserTypes)],
            'access_type' => ['required', 'in:2,2'],
            //'shop_name' => ['required_if:access_type,2', 'nullable', 'string', 'max:150'],
            'shop_name'   => [
                Rule::requiredIf(function () {
                    return $this->input('user_type') == UserType::DEALER && $this->input('access_type') == 2;
                }),
                'nullable',
                'string',
                'max:150'
            ],
            'address'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.unique'         => 'This mobile number is already registered।',
            'shop_name.required_if' => 'Retailer account requires shop name।',
        ];
    }
}