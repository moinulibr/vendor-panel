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
            'transaction_date'  => $this->transaction_date,
            'status'            => $this->status, // e.g. pending, approved, final, cancelled
            'is_quotation'      => (bool) $this->quotation,
            'payment_status'    => $this->payment_status,
            'pricing_summary'   => [
                'sub_total'        => (float) $this->sub_total,
                'discount_amount'  => (float) $this->discount_amount,
                'shipping_charge'  => (float) $this->shipping_charge, // Negotiable Amount
                'final_amount'     => (float) $this->final_amount,
                'total_paid'       => (float) $this->payments->sum('amount'),
                'due_amount'       => max(0, (float) $this->final_amount - (float) $this->payments->sum('amount')),
            ],
            'note'              => $this->note,
            'vendor_orders'     => $this->whenLoaded('vendor_orders', function () {
                return $this->vendor_orders->map(fn($vOrder) => [
                    'id'              => $vOrder->id,
                    'vendor_id'       => $vOrder->vendor_id,
                    'invoice_no'      => $vOrder->invoice_no,
                    'sub_total'       => (float) $vOrder->sub_total,
                    'shipping_charge' => (float) $vOrder->shipping_charge,
                    'final_amount'    => (float) $vOrder->final_amount,
                ]);
            }),
            'items' => $this->whenLoaded('lines', function () {
                return $this->lines->map(fn($line) => [
                    'id'           => $line->id,
                    'product_id'   => $line->product_id,
                    'variation_id' => $line->variation_id,
                    'quantity'     => (float) $line->quantity,
                    'unit_price'   => (float) $line->price,
                    'subtotal'     => (float) ($line->quantity * $line->price),
                ]);
            }),
            'payments' => $this->whenLoaded('payments', function () {
                return $this->payments->map(fn($pay) => [
                    'id'             => $pay->id,
                    'method'         => $pay->method,
                    'amount'         => (float) $pay->amount,
                    'transaction_no' => $pay->transaction_no,
                    'paid_on'        => $pay->paid_on,
                    'document_url'   => $pay->document_path ? asset('storage/' . $pay->document_path) : null,
                    'status'         => $pay->status ?? 'pending'
                ]);
            }),
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }
}
