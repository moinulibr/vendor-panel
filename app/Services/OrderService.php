<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Repositories\Order\Interface\OrderRepositoryInterface;
use App\Utils\ProductUtil;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected CartRepositoryInterface $cartRepository,
        protected ProductUtil $productUtil
    ) {}

    public function getUserOrders(int $userId, array $filters)
    {
        return $this->orderRepository->getPaginatedOrders($userId, $filters);
    }

    public function getOrderDetails(int $orderId, int $userId): Transaction
    {
        $order = $this->orderRepository->findByIdAndUser($orderId, $userId);
        if (!$order) {
            throw new Exception("Order or Quotation not found.");
        }
        return $order;
    }

    public function createQuotationFromCart(int $userId, array $requestData): Transaction
    {
        $cart = $this->cartRepository->getSingleCart($userId);
        $cartItems = $cart->items()->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw new Exception("Cannot create quotation from empty cart.");
        }

        return DB::transaction(function () use ($userId, $cart, $cartItems, $requestData) {
            // Group Cart Items by Vendor (Multi-Vendor Support)
            $vendorCart = $cartItems->groupBy(fn($item) => $item->product->user_id ?? 0);

            // Calculate Totals
            $subTotal = $cartItems->sum(fn($i) => $i->quantity * $i->unit_price);
            $discountAmount = $cart->discount_amount ?? 0;
            $finalAmount = max(0, $subTotal - $discountAmount);

            // 1. Create Base Transaction
            $transaction = $this->orderRepository->createQuotationTransaction([
                'user_id'          => $userId,
                'contact_id'       => $requestData['contact_id'] ?? null,
                'location_id'      => 2, // Default Location/Outlet ID
                'type'             => 'sell',
                'status'           => 'pending',
                'is_new'           => 1,
                'is_pos'           => 0,
                'quotation'        => 1, // Marked as Quotation
                'invoice_no'       => $this->productUtil->generateInvoiceNumber(),
                'transaction_date' => now(),
                'sub_total'        => $subTotal,
                'discount_amount'  => $discountAmount,
                'shipping_charge'  => 0.00, // Negotiable, starts at 0
                'final_amount'     => $finalAmount,
                'note'             => $requestData['note'] ?? null,
                'mail_notification' => 1,
                'sms_notification' => 1
            ]);

            $linesData = [];

            // 2. Loop Vendors and Create Vendor Orders
            foreach ($vendorCart as $vendorId => $items) {
                $vSubTotal = $items->sum(fn($i) => $i->quantity * $i->unit_price);

                $vendorOrder = $this->orderRepository->createVendorOrder([
                    'transaction_id'  => $transaction->id,
                    'vendor_id'       => $vendorId,
                    'invoice_no'      => rand(111111, 999999),
                    'sub_total'       => $vSubTotal,
                    'shipping_charge' => 0.00,
                    'final_amount'    => $vSubTotal,
                ]);

                foreach ($items as $item) {
                    $linesData[] = [
                        'product_id'      => $item->product_id,
                        'variation_id'    => $item->variation_id,
                        'vendor_order_id' => $vendorOrder->id,
                        'quantity'        => $item->quantity,
                        'price'           => $item->unit_price,
                        'old_price'       => $item->old_price ?? 0,
                        'discount'        => $item->discount_amount ?? 0,
                    ];
                }
            }

            // 3. Create Lines Bulk Insert
            $this->orderRepository->createTransactionLines($transaction, $linesData);

            // 4. Clear DB Cart
            $this->cartRepository->clearCart($cart->id);

            return $transaction;
        });
    }

    public function updatePendingQuotation(int $orderId, int $userId, array $data): Transaction
    {
        $transaction = $this->getOrderDetails($orderId, $userId);

        if ($transaction->status !== 'pending' || $transaction->quotation != 1) {
            throw new Exception("Only pending quotations can be edited.");
        }

        return DB::transaction(function () use ($transaction, $data) {
            if (!empty($data['items'])) {
                $linesData = [];
                $subTotal = 0;

                foreach ($data['items'] as $item) {
                    $lineSubtotal = $item['quantity'] * $item['unit_price'];
                    $subTotal += $lineSubtotal;

                    $linesData[] = [
                        'product_id'   => $item['product_id'],
                        'variation_id' => $item['variation_id'] ?? null,
                        'quantity'     => $item['quantity'],
                        'price'        => $item['unit_price'],
                    ];
                }

                $this->orderRepository->syncTransactionLines($transaction, $linesData);

                $data['sub_total'] = $subTotal;
                $data['final_amount'] = max(0, $subTotal + $transaction->shipping_charge - $transaction->discount_amount);
            }

            $this->orderRepository->updateTransaction($transaction, array_filter([
                'note'         => $data['note'] ?? $transaction->note,
                'sub_total'    => $data['sub_total'] ?? $transaction->sub_total,
                'final_amount' => $data['final_amount'] ?? $transaction->final_amount,
            ]));

            return $transaction->fresh();
        });
    }

    public function confirmQuotationToOrder(int $orderId, int $userId): Transaction
    {
        $transaction = $this->getOrderDetails($orderId, $userId);

        if ($transaction->quotation != 1) {
            throw new Exception("This transaction is already confirmed as an order.");
        }

        return DB::transaction(function () use ($transaction) {
            // Convert Quotation -> Sale
            $this->orderRepository->updateTransaction($transaction, [
                'quotation' => 0,
                'status'    => 'final', // Ready for Payment
                'is_new'    => 0,
            ]);

            // Deduct Stock
            foreach ($transaction->lines as $line) {
                $this->productUtil->decreaseProductStock(
                    $line->product_id,
                    $line->variation_id,
                    $transaction->location_id,
                    $line->quantity
                );
            }

            return $transaction->fresh();
        });
    }

    public function submitPaymentAttachment(int $orderId, int $userId, array $data, $documentFile = null)
    {
        $transaction = $this->getOrderDetails($orderId, $userId);

        if ($transaction->quotation == 1) {
            throw new Exception("Cannot attach payment to an unconfirmed quotation.");
        }

        $documentPath = null;
        if ($documentFile) {
            $documentPath = $documentFile->store('payment_receipts', 'public');
        }

        return $this->orderRepository->attachPayment($transaction, [
            'user_id'        => auth()->id(),
            'paid_on'        => now()->toDateString(),
            'method'         => $data['method'],
            'amount'         => $data['amount'],
            'transaction_no' => $data['transaction_no'],
            'account_no'     => $data['account_no'] ?? null,
            'bank_name'      => $data['bank_name'] ?? null,
            'note'           => $data['note'] ?? null,
            'document_path'  => $documentPath,
            'status'         => 'pending', // Requires Admin Approval
        ]);
    }
}
