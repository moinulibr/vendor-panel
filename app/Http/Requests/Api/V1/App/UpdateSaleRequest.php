<?php

namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Items array — at least one item required
            'items'                                 => 'required|array|min:1',
            'items.*.product_id'                    => 'required|integer|exists:products,id',
            'items.*.variation_id'                  => 'nullable|integer|exists:variations,id',
            'items.*.qty'                           => 'required|integer|min:1',
            'items.*.vendor_id'                     => 'nullable|integer|exists:users,id',

            // Optional item-level overrides (carried forward from old sale if omitted)
            'items.*.unit_price_snapshot'            => 'nullable|numeric|min:0',
            'items.*.line_discount'                  => 'nullable|numeric|min:0',
            'items.*.line_discount_percent'          => 'nullable|numeric|min:0|max:100',
            'items.*.line_note'                      => 'nullable|string|max:500',

            // Optional sale header overrides (fall back to old sale values)
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
            'sales_person_id'                       => 'nullable|integer|exists:users,id',

            // Edit metadata
            'reason'                                => 'nullable|string|max:1000',
            'edited_by'                             => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'             => 'At least one item is required to update a sale.',
            'items.min'                  => 'At least one item is required to update a sale.',
            'items.*.product_id.required' => 'Each item must have a product_id.',
            'items.*.qty.required'        => 'Each item must have a quantity.',
            'items.*.qty.min'             => 'Item quantity must be at least 1.',
        ];
    }
}
