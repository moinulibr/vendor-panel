<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('status') || empty($this->status)) {
            $this->merge([
                'status' => 'confirmed',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            // Required parameters
            'cart_id'                               => 'required|integer|exists:carts,id',
            'channel'                               => 'required|string|in:admin_pos,web_ecommerce,mobile_dealer_app,sr',
            'warehouse_id'                          => 'required|integer|exists:warehouses,id',

            // Optional status (default: confirmed via prepareForValidation)
            'status'                                => 'nullable|string|in:draft,quotation,confirmed,processing,completed,cancelled',

            // Optional payments array
            'payments'                              => 'nullable|array',
            'payments.*.amount'                     => 'required_with:payments|numeric|min:0',
            'payments.*.method'                     => 'required_with:payments|string|max:50',
            'payments.*.reference_no'               => 'nullable|string|max:100',
            'payments.*.paid_at'                    => 'nullable|date',
            'payments.*.received_by'                => 'nullable|integer|exists:users,id',
            'payments.*.register_session_id'        => 'nullable|integer',
            'payments.*.notes'                      => 'nullable|string|max:500',

            // Optional sale header overrides
            'customer_id'                           => 'nullable|integer|exists:users,id',
            'customer_type'                         => 'nullable|string|in:regular,dealer,special,ecommerce',
            'sales_person_id'                       => 'nullable|integer|exists:users,id',
            'sale_date'                             => 'nullable|date',
            'source_ref'                            => 'nullable|string|max:100',
            'shipping_charge'                       => 'nullable|numeric|min:0',
            'round_off'                             => 'nullable|numeric',
            'discount_type'                         => 'nullable|string|in:percentage,fixed',
            'discount_value'                        => 'nullable|numeric|min:0',
            'coupon_id'                             => 'nullable|integer',
            'coupon_discount_type'                  => 'nullable|string|in:percentage,fixed',
            'coupon_discount_value'                 => 'nullable|numeric|min:0',
            'currency'                              => 'nullable|string|max:10',
            'exchange_rate'                         => 'nullable|numeric|min:0',
            'notes'                                 => 'nullable|string|max:1000',
            'quote_valid_until'                     => 'nullable|date',
            'quote_notes'                           => 'nullable|string',
            'created_by'                            => 'nullable|integer|exists:users,id',
        ];
    }
}
