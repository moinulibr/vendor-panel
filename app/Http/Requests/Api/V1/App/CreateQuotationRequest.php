<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_base_id' => 'nullable|integer|exists:users,id',
            'contact_id'   => 'nullable|integer|exists:contacts,id',
            'note'         => 'nullable|string|max:500',
        ];
    }
}
