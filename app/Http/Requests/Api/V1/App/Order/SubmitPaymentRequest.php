<?php

namespace App\Http\Requests\Api\V1\App\Order;

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
            'method'         => 'required|string',
            'amount'         => 'required|numeric|gt:0',
            'transaction_no' => 'required|string|max:100',
            'account_no'     => 'nullable|string|max:50',
            'bank_name'      => 'nullable|string|max:100',
            'note'           => 'nullable|string|max:255',
            'document'       => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120', // Max 5MB
        ];
    }
}
