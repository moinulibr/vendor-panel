<?php

namespace App\Repositories\Interface;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemBatch;
use App\Models\SalePayment;
use App\Models\SalePaymentSummary;
use App\Models\SaleTax;

interface SaleRepositoryInterface
{
    public function createSale(array $data): Sale;

    public function createSaleItem(array $data): SaleItem;

    public function createSaleItemBatch(array $data): SaleItemBatch;

    public function createSaleTax(array $data): SaleTax;

    public function createSalePayment(array $data): SalePayment;

    public function createSalePaymentSummary(array $data): SalePaymentSummary;

    public function getLatestInvoiceNo(string $prefix): ?string;

    public function getLatestPaymentNo(string $prefix): ?string;

    public function getLatestLedgerBalance(string $partyType, int $partyId): float;

    public function insertLedgerEntry(array $data): void;
}
