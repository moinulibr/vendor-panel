<?php

namespace App\Http\Requests\Api\V1\App;

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
            // Sale header
            'channel'                               => 'required|string|in:admin_pos,web_ecommerce,mobile_dealer_app,sr',
            'customer_id'                           => 'nullable|integer|exists:users,id',
            'customer_type'                         => 'nullable|string|in:regular,dealer,special,ecommerce',
            'warehouse_id'                          => 'required|integer|exists:warehouses,id',
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
            'status'                                => 'nullable|string|in:draft,quotation,confirmed,processing,completed,cancelled',
            'created_by'                            => 'nullable|integer|exists:users,id',

            // Items array
            'items'                                 => 'required|array|min:1',
            'items.*.product_id'                    => 'required|integer|exists:products,id',
            'items.*.variation_id'                  => 'nullable|integer',
            'items.*.vendor_id'                     => 'nullable|integer|exists:users,id',
            'items.*.qty'                           => 'required|integer|min:1',
            'items.*.product_name_snapshot'         => 'nullable|string|max:255',
            'items.*.variant_label_snapshot'        => 'nullable|string|max:255',
            'items.*.invoice_name_snapshot'         => 'nullable|string|max:255',
            'items.*.image_snapshot'                => 'nullable|string|max:500',
            'items.*.unit_price_snapshot'           => 'required|numeric|min:0',
            'items.*.customer_type_snapshot'        => 'nullable|string|max:50',
            'items.*.allow_discount'                => 'nullable|boolean',
            'items.*.tax_type_snapshot'             => 'nullable|string|in:exclusive,inclusive,exempt',
            'items.*.tax_rate_snapshot'             => 'nullable|numeric|min:0',
            'items.*.tax_name'                      => 'nullable|string|max:100',
            'items.*.tax_category'                  => 'nullable|string|max:100',
            'items.*.hs_code'                       => 'nullable|string|max:100',
            'items.*.line_discount'                 => 'nullable|numeric|min:0',
            'items.*.line_discount_percent'         => 'nullable|numeric|min:0',
            'items.*.vendor_commission_type_snapshot'   => 'nullable|string|in:percentage,fixed',
            'items.*.vendor_commission_value_snapshot'  => 'nullable|numeric|min:0',
            'items.*.salesperson_commission_type_snapshot'  => 'nullable|string|in:percentage,fixed',
            'items.*.salesperson_commission_value_snapshot' => 'nullable|numeric|min:0',
            'items.*.is_combo'                      => 'nullable|boolean',
            'items.*.line_note'                     => 'nullable|string|max:500',
            'items.*.track_batch'                   => 'nullable|boolean',

            // Payments array
            'payments'                              => 'nullable|array',
            'payments.*.amount'                     => 'required_with:payments|numeric|min:0',
            'payments.*.method'                     => 'required_with:payments|string|max:50',
            'payments.*.reference_no'               => 'nullable|string|max:100',
            'payments.*.paid_at'                    => 'nullable|date',
            'payments.*.received_by'                => 'nullable|integer|exists:users,id',
        ];
    }
}
