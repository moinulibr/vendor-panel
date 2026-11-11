<?php

namespace App\Repositories\Sale\Interface;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemBatch;
use App\Models\SalePayment;
use App\Models\SalePaymentSummary;
use App\Models\SaleTax;
use App\Models\SaleReturn;

interface SaleRepositoryInterface
{
    public function createSale(array $data): Sale;
    public function createSaleItem(array $data): SaleItem;
    public function createSaleItemBatch(array $data): SaleItemBatch;
    public function createSaleTax(array $data): SaleTax;
    public function createSalePayment(array $data): SalePayment;
    public function createSalePaymentSummary(array $data): SalePaymentSummary;
    public function createSaleReturn(array $data): SaleReturn;

    public function getLatestInvoiceNo(string $prefix): ?string;
    public function getLatestPaymentNo(string $prefix): ?string;
    public function getLatestLedgerBalance(string $partyType, int $partyId): float;
    public function insertLedgerEntry(array $data): void;

    public function getPaginatedSales(int $userId, array $filters);
    public function findByIdAndUser(int $saleId, int $userId): ?Sale;
    public function findSaleById(int $saleId): ?Sale;
    public function updateSale(Sale $sale, array $data): bool;
    public function getSummaryStats(int $userId): array;
    public function getSaleItemsWithBatches(int $saleId); // For Return
}
