<?php

namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generatePdf(Sale $sale)
    {
        // লোড রিলেশনশিপ
        $sale->load(['items.product', 'items.variation', 'customer', 'payments', 'shippingAddress']);

        $data = [
            'sale' => $sale,
            'company_name' => 'Original Ponno',
            // 🟢 NOTE: এখানে ডাটাবেস থেকে কোম্পানির আসল ঠিকানা, ফোন, লোগো আনো (Settings Table থেকে)
            'company_address' => 'Mirpur-1, Dhaka-1216 • BIN: 004829104-0203',
            'company_phone' => '+880 1712-794033',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', $data);
        //$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.barryvdh-dompdf-template', $data);
        //$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', $data);
        return $pdf->download('Invoice-' . $sale->invoice_no . '.pdf');
    }
}
