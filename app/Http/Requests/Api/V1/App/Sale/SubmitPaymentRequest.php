<?php

namespace App\Http\Requests\Api\V1\App\Sale;

use Illuminate\Foundation\Http\FormRequest;

class SubmitPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'         => 'required|numeric|gt:0',
            'method'         => 'required|string',
            'reference_no'   => 'nullable|string|max:100',
            'document'       => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ];
    }
}
