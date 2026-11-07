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
}
