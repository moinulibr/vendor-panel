<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'    => 'required|integer|exists:products,id',
            'type'          => 'required|string|in:single,variable',
            'variation_id'  => 'nullable|integer|exists:variations,id',
        ];
    }
}
