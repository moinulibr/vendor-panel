<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemBatch;
use App\Models\SalePayment;
use App\Models\SalePaymentSummary;
use App\Models\SaleTax;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SaleRepository implements SaleRepositoryInterface
{
    public function createSale(array $data): Sale
    {
        return Sale::create($data);
    }

    public function createSaleItem(array $data): SaleItem
    {
        return SaleItem::create($data);
    }

    public function createSaleItemBatch(array $data): SaleItemBatch
    {
        return SaleItemBatch::create($data);
    }

    public function createSaleTax(array $data): SaleTax
    {
        return SaleTax::create($data);
    }

    public function createSalePayment(array $data): SalePayment
    {
        return SalePayment::create($data);
    }

    public function createSalePaymentSummary(array $data): SalePaymentSummary
    {
        return SalePaymentSummary::create($data);
    }

    public function getLatestInvoiceNo(string $prefix): ?string
    {
        return Sale::where('invoice_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('invoice_no');
    }

    public function getLatestPaymentNo(string $prefix): ?string
    {
        return SalePayment::where('payment_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('payment_no');
    }

    public function getLatestLedgerBalance(string $partyType, int $partyId): float
    {
        return (float) (DB::table('payment_ledger')
            ->where('party_type', $partyType)
            ->where('party_id', $partyId)
            ->orderBy('id', 'desc')
            ->value('running_balance') ?? 0.0);
    }

    public function insertLedgerEntry(array $data): void
    {
        DB::table('payment_ledger')->insert($data);
    }

    public function updateSaleStatus(Sale $sale, string $paymentStatus, string $status): void
    {
        $sale->update([
            'payment_status' => $paymentStatus,
            'status' => $status
        ]);
    }
    
    public function getPaginatedSales(int $userId, array $filters)
    {
        $query = Sale::where('customer_id', $userId)->current();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $query->where('invoice_no', 'like', '%' . $filters['search'] . '%');
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('sale_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('sale_date', '<=', $filters['date_to']);
        }

        return $query->with(['items.product', 'payments'])->latest('id')->paginate($filters['per_page'] ?? 15);
    }

    public function findByIdAndUser(int $saleId, int $userId): ?Sale
    {
        return Sale::where('id', $saleId)
            ->where('customer_id', $userId)
            ->with(['items.product', 'payments', 'taxes'])
            ->first();
    }

    public function findSaleById(int $saleId): ?Sale
    {
        return Sale::with('items')->find($saleId);
    }

    public function updateSale(Sale $sale, array $data): bool
    {
        return $sale->update($data);
    }

    public function getSummaryStats(int $userId): array
    {
        return Sale::where('customer_id', $userId)
            ->where('is_current', true)
            ->selectRaw("
                COUNT(CASE WHEN status != 'quotation' THEN 1 END) as total_orders,
                COUNT(CASE WHEN status = 'quotation' THEN 1 END) as total_quotations,
                SUM(CASE WHEN status != 'quotation' THEN final_amount ELSE 0 END) as total_order_amount,
                SUM(CASE WHEN status != 'quotation' THEN paid_amount ELSE 0 END) as total_paid_amount,
                SUM(CASE WHEN status != 'quotation' THEN due_amount ELSE 0 END) as total_due_amount
            ")->first()->toArray();
    }
}
