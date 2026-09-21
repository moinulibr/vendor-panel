<?php

namespace App\Http\Requests\Api\V1\App\Order;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type == UserType::SR;
        return [
            'user_base_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
            'cart_id'   => 'required|integer|exists:carts,id',
            'note'      => 'nullable|string|max:500',
        ];
    }
}
