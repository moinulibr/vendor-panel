<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSummaryReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_orders'        => (int) ($this['total_orders'] ?? 0),
            'full_paid_orders'    => (int) ($this['full_paid_orders'] ?? 0),
            'partial_paid_orders' => (int) ($this['partial_paid_orders'] ?? 0),
            'unpaid_orders'       => (int) ($this['unpaid_orders'] ?? 0),
            'pending_orders'      => (int) ($this['pending_orders'] ?? 0),
            'processing_orders'   => (int) ($this['processing_orders'] ?? 0),
            'received_orders'     => (int) ($this['received_orders'] ?? 0),
            'successful_orders'   => (int) ($this['successful_orders'] ?? 0),
            'cancelled_orders'    => (int) ($this['cancelled_orders'] ?? 0),
            'total_quotations'    => (int) ($this['total_quotations'] ?? 0),
            'total_order_amount'  => (float) round($this['total_order_amount'] ?? 0, 2),
            'total_paid_amount'   => (float) round($this['total_paid_amount'] ?? 0, 2),
            'total_due_amount'    => (float) round($this['total_due_amount'] ?? 0, 2),
            'total_saved_amount'  => (float) round($this['total_saved_amount'] ?? 0, 2),
        ];
    }
}
