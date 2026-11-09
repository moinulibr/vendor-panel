<?php

namespace App\Http\Resources\Api\V1\App\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'invoice_no'        => $this->invoice_no,
            'channel'           => $this->channel,
            'customer_id'       => $this->customer_id,
            'customer_type'     => $this->customer_type,
            'warehouse_id'      => $this->warehouse_id,

            // Amounts
            'sub_total'         => (float) $this->sub_total,
            'item_discount'     => (float) $this->item_total_discount,
            'invoice_discount'  => (float) $this->discount_amount,
            'tax_amount'        => (float) $this->tax_amount,
            'shipping_charge'   => (float) $this->shipping_charge,
            'final_amount'      => (float) $this->final_amount,
            'paid_amount'       => (float) $this->paid_amount,
            'due_amount'        => (float) $this->due_amount,

            // Statuses
            'status'            => $this->status,
            'payment_status'    => $this->payment_status,
            'is_current'        => (bool) $this->is_current,
            'sale_date'         => $this->sale_date ? $this->sale_date->format('Y-m-d H:i:s') : $this->created_at->format('Y-m-d H:i:s'),

            // Relationships
            'customer'          => $this->whenLoaded('customer', fn() => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'phone' => $this->customer->phone ?? null,
            ]),

            'items'             => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id'                    => $item->id,
                        'product_id'            => $item->product_id,
                        'variation_id'          => $item->variation_id,
                        'product_name'          => $item->product_name_snapshot,
                        'variant_label'         => $item->variant_label_snapshot,
                        'unit_price'            => (float) $item->unit_price_snapshot,
                        'qty'                   => (int) $item->qty,
                        'line_subtotal'         => (float) $item->line_subtotal,
                        'line_discount'         => (float) $item->line_discount,
                        'tax_amount'            => (float) $item->tax_amount,
                        'line_total'            => (float) $item->line_total,
                        'status'                => $item->status,
                    ];
                });
            }),

            'payments'          => $this->whenLoaded('payments', function () {
                return $this->payments->map(fn($p) => [
                    'id' => $p->id,
                    'payment_no' => $p->payment_no,
                    'amount' => (float) $p->amount,
                    'method' => $p->method,
                    'status' => $p->status,
                    'paid_at' => $p->paid_at ? $p->paid_at->format('Y-m-d H:i:s') : null,
                ]);
            }),
        ];
    }
}
