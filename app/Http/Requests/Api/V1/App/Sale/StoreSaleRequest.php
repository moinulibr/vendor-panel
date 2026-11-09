<?php

namespace App\Http\Requests\Api\V1\App\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_id'          => 'required|integer|exists:carts,id',
            'channel'          => 'required|string|in:admin_pos,web_ecommerce,mobile_dealer_app,sr',
            'warehouse_id'     => 'required|integer|exists:warehouses,id',
            'customer_type'    => 'nullable|string|in:regular,dealer,special,ecommerce',
            'status'           => 'nullable|string|in:confirmed,quotation', // Default is confirmed
            'sales_person_id'  => 'nullable|integer|exists:users,id',
            'shipping_charge'  => 'nullable|numeric|min:0',
            'discount_type'    => 'nullable|string|in:percentage,fixed',
            'discount_value'   => 'nullable|numeric|min:0',
            'note'             => 'nullable|string|max:500',

            // Initial Payments (Optional)
            'payments'                 => 'nullable|array',
            'payments.*.amount'        => 'required_with:payments|numeric|min:0',
            'payments.*.method'        => 'required_with:payments|string|max:50',
            'payments.*.reference_no'  => 'nullable|string|max:100',
        ];
    }
}
