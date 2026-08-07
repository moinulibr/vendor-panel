<?php
namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class FcmStoreTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token'   => ['required', 'string'],
            'device_type' => ['nullable', 'string', 'in:android,ios,web'],
            'device_id'   => ['nullable', 'string', 'max:255'],
        ];
    }
    public function messages(): array
    {
        return [
            'fcm_token.required' => 'FCM ডিভাইস টোকেন দেওয়া বাধ্যতামূলক।',
            'device_type.in'     => 'ডিভাইসের ধরণ অবশ্যই android, ios অথবা web হতে হবে।',
        ];
    }
}