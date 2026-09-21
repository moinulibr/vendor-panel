<?php

namespace App\Repositories\Order;

use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\TransactionPayment;
use App\Models\VendorOrder;
use App\Repositories\Order\Interface\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function getPaginatedOrders(int $userId, array $filters): LengthAwarePaginator
    {
        $query = Transaction::where('user_id', $userId)
            ->where('type', 'sell')
            ->with(['lines', 'vendor_orders', 'payments']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where('invoice_no', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        return $query->latest('id')->paginate($filters['per_page'] ?? 15);
    }

    public function findByIdAndUser(int $orderId, int $userId): ?Transaction
    {
        return Transaction::where('id', $orderId)
            ->where('user_id', $userId)
            ->with(['lines.product', 'vendor_orders', 'payments'])
            ->first();
    }

    public function createQuotationTransaction(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function createVendorOrder(array $data)
    {
        return VendorOrder::create($data);
    }

    public function createTransactionLines(Transaction $transaction, array $linesData): void
    {
        $transaction->lines()->createMany($linesData);
    }

    public function updateTransaction(Transaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }

    public function syncTransactionLines(Transaction $transaction, array $itemsData): void
    {
        $transaction->lines()->delete();
        $transaction->lines()->createMany($itemsData);
    }

    public function attachPayment(Transaction $transaction, array $paymentData): TransactionPayment
    {
        return $transaction->payments()->create($paymentData);
    }
}
