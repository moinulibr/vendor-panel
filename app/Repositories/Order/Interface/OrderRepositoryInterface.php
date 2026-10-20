<?php

namespace App\Repositories\Order\Interface;

use App\Models\Transaction;
use App\Models\TransactionPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    //public function getPaginatedOrders(int $userId, array $filters): LengthAwarePaginator;
    public function getPaginatedOrders(int $userId, array $filters);
    //public function findByIdAndUser(int $orderId, int $userId): ?Transaction;
    public function findByIdAndUser(int $orderId, int $userId);
    public function findOrderByIdAndUser(int $orderId, int $userId);
    //public function createQuotationTransaction(array $data): Transaction;
    public function createQuotationTransaction(array $data);
    //public function createVendorOrder(array $data);
    public function createVendorOrder(array $data);
    //public function createTransactionLines(Transaction $transaction, array $linesData): void;
    public function createTransactionLines(Transaction $transaction, array $linesData);
    public function updateTransaction(Transaction $transaction, array $data);
    //public function updateTransaction(Transaction $transaction, array $data): bool;
    public function syncTransactionLines(Transaction $transaction, array $itemsData);
    //public function syncTransactionLines(Transaction $transaction, array $itemsData): void;
    public function attachPayment(Transaction $transaction, array $paymentData);
   // public function attachPayment(Transaction $transaction, array $paymentData): TransactionPayment;
}
