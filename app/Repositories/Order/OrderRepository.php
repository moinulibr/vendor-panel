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
    //public function getPaginatedOrders(int $userId, array $filters): LengthAwarePaginator
    public function getPaginatedOrders(int $userId, array $filters)
    {
        //$query = object();//Sale::..;

        if (!empty($filters['status'])) {
            //$query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
           // $query->where('invoice_no', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['date_from'])) {
            //$query->whereDate('sale_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            //$query->whereDate('sale_date', '<=', $filters['date_to']);
        }

        //return $query->latest('id')->paginate($filters['per_page'] ?? 15);
    }

    public function findByIdAndUser(int $orderId, int $userId)
    {
        //return Sale::where('id', $orderId)
            //->where('customer_id', $userId)
            //->with(['lines.product', 'vendor_orders', 'payments'])
            //->first();
    }

    public function findOrderByIdAndUser(int $orderId, int $userId)
    {
    }

    public function createQuotationTransaction(array $data)
    {
        //return sale::create($data);
    }

    public function createVendorOrder(array $data)
    {
        //return VendorOrder::create($data);
    }

    public function createTransactionLines(Transaction $transaction, array $linesData)
    {
        //$dsle->lines()->createMany($linesData);
    }

    public function updateTransaction(Transaction $transaction, array $data)
    {
        //return $sale->update($data);
    }

    public function syncTransactionLines(Transaction $transaction, array $itemsData)
    {
        //$transaction->lines()->delete();
        //$transaction->lines()->createMany($itemsData);
    }

    public function attachPayment(Transaction $transaction, array $paymentData)
    {
        //return $transaction->payments()->create($paymentData);
    }
}
