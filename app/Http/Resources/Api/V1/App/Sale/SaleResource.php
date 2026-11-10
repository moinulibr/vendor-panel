<?php

namespace App\Http\Resources\Api\V1\App\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'parent_id'                => $this->parent_id,
            'version'                  => $this->version,
            'is_current'               => (bool) $this->is_current,
            'invoice_no'               => $this->invoice_no,
            'channel'                  => $this->channel,
            'source_ref'               => $this->source_ref,
            'customer_id'              => $this->customer_id,
            'customer_type'            => $this->customer_type,
            'warehouse_id'             => $this->warehouse_id,
            'sales_person_id'          => $this->sales_person_id,
            'sub_total'                => (float) $this->sub_total,
            'item_total_discount'      => (float) $this->item_total_discount,
            'gross_total'              => (float) $this->gross_total,
            'discount_type'            => $this->discount_type,
            'discount_value'           => (float) $this->discount_value,
            'discount_amount'          => (float) $this->discount_amount,
            'coupon_id'                => $this->coupon_id,
            'coupon_discount_type'     => $this->coupon_discount_type,
            'coupon_discount_value'    => (float) $this->coupon_discount_value,
            'coupon_discount_amount'   => (float) $this->coupon_discount_amount,
            'shipping_charge'          => (float) $this->shipping_charge,
            'tax_amount'               => (float) $this->tax_amount,
            'round_off'                => (float) $this->round_off,
            'final_amount'             => (float) $this->final_amount,
            'paid_amount'              => (float) $this->paid_amount,
            'due_amount'               => (float) $this->due_amount,
            'sale_date'                => $this->sale_date?->toDateTimeString() ?? (string) $this->sale_date,
            'status'                   => $this->status,
            'payment_status'           => $this->payment_status,
            'shipping_status'          => $this->shipping_status,
            'currency'                 => $this->currency,
            'exchange_rate'            => (float) $this->exchange_rate,
            'notes'                    => $this->notes,
            'quote_valid_until'        => $this->quote_valid_until?->toDateString() ?? (string) $this->quote_valid_until,
            'quote_converted_at'       => $this->quote_converted_at?->toDateTimeString() ?? (string) $this->quote_converted_at,
            'quote_converted_by'       => $this->quote_converted_by,
            'quote_notes'              => $this->quote_notes,
            'is_editable_for_admin'    => (bool) $this->is_editable_for_admin,
            'is_editable_for_staff'    => (bool) $this->is_editable_for_staff,
            'is_editable_for_customer' => (bool) $this->is_editable_for_customer,
            'created_by'               => $this->created_by,
            'created_at'               => $this->created_at?->toDateTimeString(),
            'updated_at'               => $this->updated_at?->toDateTimeString(),

            // Relationships
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id'    => $this->customer?->id,
                    'name'  => $this->customer?->name,
                    'email' => $this->customer?->email,
                    'phone' => $this->customer?->phone,
                ];
            }),

            'salesperson' => $this->whenLoaded('salesperson', function () {
                return [
                    'id'   => $this->salesperson?->id,
                    'name' => $this->salesperson?->name,
                ];
            }),

            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id'                                   => $item->id,
                        'product_id'                           => $item->product_id,
                        'variation_id'                         => $item->variation_id,
                        'vendor_id'                            => $item->vendor_id,
                        'product_name'                         => $item->product_name_snapshot,
                        'variation_name'                       => $item->variant_label_snapshot,
                        'invoice_name_snapshot'                => $item->invoice_name_snapshot,
                        'image_snapshot'                       => $item->image_snapshot,
                        'unit_price'                           => (float) $item->unit_price_snapshot,
                        'customer_type_snapshot'               => $item->customer_type_snapshot,
                        'qty'                                  => (int) $item->qty,
                        'line_subtotal'                        => (float) $item->line_subtotal,
                        'allow_discount'                       => (bool) $item->allow_discount,
                        'line_discount'                        => (float) $item->line_discount,
                        'line_discount_percent'                => (float) $item->line_discount_percent,
                        'taxable_amount'                       => (float) $item->taxable_amount,
                        'tax_type_snapshot'                    => $item->tax_type_snapshot,
                        'tax_rate_snapshot'                    => (float) $item->tax_rate_snapshot,
                        'tax_amount'                           => (float) $item->tax_amount,
                        'allocated_inv_disc'                   => (float) $item->allocated_inv_disc,
                        'line_total'                           => (float) $item->line_total,
                        'vendor_commission_type_snapshot'      => $item->vendor_commission_type_snapshot,
                        'vendor_commission_value_snapshot'     => (float) $item->vendor_commission_value_snapshot,
                        'vendor_commission_amount'             => (float) $item->vendor_commission_amount,
                        'vendor_payable_amount'                => (float) $item->vendor_payable_amount,
                        'salesperson_commission_type_snapshot' => $item->salesperson_commission_type_snapshot,
                        'salesperson_commission_value_snapshot' => (float) $item->salesperson_commission_value_snapshot,
                        'salesperson_commission_amount'        => (float) $item->salesperson_commission_amount,
                        'is_combo'                             => (bool) $item->is_combo,
                        'line_note'                            => $item->line_note,
                        'batches'                              => $item->relationLoaded('batchAllocations') ? $item->batchAllocations->map(fn($b) => [
                            'id'                => $b->id,
                            'batch_id'          => $b->batch_id ?? $b->product_batch_id,
                            'qty_used'          => (int) ($b->qty_used ?? $b->allocated_qty),
                            'unit_cost'         => (float) ($b->unit_cost ?? $b->batch_cost_price),
                            'cost_total'        => (float) $b->cost_total,
                            'unit_sale_price'   => (float) $b->unit_sale_price,
                            'total_sale_amount' => (float) $b->total_sale_amount,
                            'total_profit'      => (float) $b->total_profit,
                        ]) : null,
                    ];
                });
            }),

            'payments' => $this->whenLoaded('payments', function () {
                return $this->payments->map(function ($pay) {
                    return [
                        'id'           => $pay->id,
                        'payment_no'   => $pay->payment_no,
                        'customer_id'  => $pay->customer_id,
                        'amount'       => (float) $pay->amount,
                        'method'       => $pay->method,
                        'reference_no' => $pay->reference_no,
                        'status'       => $pay->status,
                        'paid_at'      => $pay->paid_at?->toDateTimeString() ?? (string) $pay->paid_at,
                        'received_by'  => $pay->received_by,
                        'notes'        => $pay->notes,
                    ];
                });
            }),

            'taxes' => $this->whenLoaded('taxes', function () {
                return $this->taxes->map(function ($tax) {
                    return [
                        'id'             => $tax->id,
                        'sale_item_id'   => $tax->sale_item_id,
                        'tax_name'       => $tax->tax_name,
                        'tax_category'   => $tax->tax_category,
                        'hs_code'        => $tax->hs_code,
                        'tax_rate'       => (float) $tax->tax_rate,
                        'taxable_amount' => (float) $tax->taxable_amount,
                        'tax_amount'     => (float) $tax->tax_amount,
                    ];
                });
            }),
        ];
    }
}
