<?php

namespace App\Http\Requests\Api\V1\App\Sale;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSR = auth()->check() && (int) auth()->user()->user_type == UserType::SR;

        return [
            'user_base_id'    => [$isSR ? 'required' : 'nullable', 'integer', 'exists:users,id'],
            'cart_id'         => 'required|integer|exists:carts,id',
            'shipping_address_id' => 'nullable', //|integer|exists:shipping_addresses,id

            'status'          => 'nullable|string|in:draft,quotation,confirmed',
            'channel'         => 'nullable|string|in:admin_pos,web_ecommerce,mobile_dealer_app,sr',
            'warehouse_id'    => 'nullable|integer|exists:warehouses,id',
            'note'            => 'nullable|string|max:500',
            'shipping_charge' => 'nullable|numeric|min:0',

            'payments'              => 'nullable|array',
            'payments.*.amount'     => 'required_with:payments|numeric|min:0.01',
            'payments.*.method'     => 'required_with:payments|string|max:50',
            'payments.*.reference_no' => 'nullable|string|max:100',
        ];
    }
}
