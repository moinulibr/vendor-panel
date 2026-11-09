<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'invoice_no'        => $this->invoice_no,
            'order_type'        => $this->status,
            'is_editable'       => $this->is_editable_for_customer,
            'sale_date'         => $this->sale_date,
            'status'            => $this->status, // e.g. pending, approved, final, cancelled
            'shipping_status'   => $this->shipping_status, // e.g. pending, approved, final, cancelled
            'is_quotation'      => $this->status == 'quotation' ? true : false,
            'payment_status'    => $this->payment_status,
            'pricing_summary'   => [
                //please do modify as per Sale table. 
                'sub_total'           => (float) $this->sub_total,
                'item_total_discount' => (float) $this->item_total_discount ?? 0,
                'gross_total'         => (float) $this->gross_total ?? 0, // ekhono db te nai

                'discount_type'       => (string) $this->discount_type,
                'discount_value'      => (float) $this->discount_amount,
                'discount_amount'     => (float) $this->cal_discount,

                'coupon_code'         => (string) $this->coupon_code ?? 'N/L', // ekhono db te nai
                'coupon_type'         => (string) $this->coupon_type ?? 'N/L', // ekhono db te nai
                'coupon_value'        => (float) $this->coupon_value ?? 0, // ekhono db te nai
                'coupon_discount_amount' => (float) $this->coupon_discount_amount ?? 0, // ekhono db te nai 
                'coupon_id'           => (float) $this->coupon_id ?? null,

                'order_total_discount' => (float) $this->total_order_discount ?? 0, // ekhono db te nai

                'shipping_charge'     => (float) $this->shipping_charge,
                'final_amount'        => (float) $this->final_amount,

                'total_items'         => (int) $this->total_items,
                'total_paid'          => (float) $this->payments->sum('amount'),
                'due_amount'          => max(0, (float) $this->final_amount - (float) $this->payments->sum('amount')),
            ],
            'note'       => $this->notes,
            // please do modify as per Sale table.
            'payments' => $this->whenLoaded('payments', function () {
                return $this->payments->map(fn($pay) => [
                    'id'             => $pay->id,
                    'method'         => $pay->method,
                    'amount'         => (float) $pay->amount,
                    //'transaction_no' => $pay->transaction_no,
                    'paid_on'        => $pay->paid_on,
                    //'document_url'   => $pay->document_path ? asset('storage/' . $pay->document_path) : null,
                    //'status'         => $pay->status ?? 'pending'
                ]);
            }),
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }
}
