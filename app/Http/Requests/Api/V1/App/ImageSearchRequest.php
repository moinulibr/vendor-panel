<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class ImageSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSrUser = auth()->check() && auth()->user()->user_type == UserType::SR;

        return [
            'image'       => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // Max 5MB
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'user_base_id' => [
                $isSrUser ? 'required' : 'nullable',
                'integer'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Please upload an image to search.',
            'image.image'    => 'The file must be a valid image.',
            'image.max'      => 'Image size must not exceed 5MB.',
        ];
    }
}
