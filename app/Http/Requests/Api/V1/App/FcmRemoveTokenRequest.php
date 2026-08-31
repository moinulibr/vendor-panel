<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class FcmRemoveTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token'   => ['required_if:remove_scope,current_device', 'nullable', 'string'],
            'remove_scope' => ['required', 'string', 'in:current_device,all_devices'], // 👈 Flexible deletion scope
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required_if' => 'বর্তমান ডিভাইসের টোকেন মুছে ফেলার জন্য FCM টোকেন প্রদান করুন। Please provide FCM token for current device.',
            'remove_scope.required' => 'টোকেন মোছার ধরণ (remove_scope) উল্লেখ করা বাধ্যতামূলক।',
            'remove_scope.in'       => 'remove_scope এর মান অবশ্যই current_device অথবা all_devices হতে হবে।',
        ];
    }
}