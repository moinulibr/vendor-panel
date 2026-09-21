<?php

namespace App\Http\Requests\Api\V1\App\Order;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class OrderListRequest extends FormRequest
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
            'status'       => 'nullable|string',
            'date_from'    => 'nullable|date',
            'date_to'      => 'nullable|date|after_or_equal:date_from',
            'search'       => 'nullable|string',
            'per_page'     => 'nullable|integer|min:1|max:100',
        ];
    }
}
