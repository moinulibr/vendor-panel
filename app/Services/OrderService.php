<?php

namespace App\Services;

use App\Models\Transaction;
use App\Utils\ProductUtil;
use App\Services\CartService;
use App\Services\SettingService;
use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Repositories\Order\Interface\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected CartRepositoryInterface $cartRepository,
        protected CartService $cartService,
        protected ProductUtil $productUtil,
        protected SettingService $settingService
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

    /**
     * Create Quotation/Sell Order from Cart
     */
    public function createQuotationFromCart(int $userId, array $requestData)
    {
        $cart = $this->cartRepository->getSingleCartByCartAndUserId($requestData['cart_id'], $userId);

        if (!$cart) {
            throw new Exception("Cart not found or does not belong to the user.");
        }

        $config       = $this->settingService->getCartExpiryConfig();
        $expiresAt    = $this->settingService->getCartExpiresAt();

        $bufferThreshold = $config['buffer_threshold'] ?? $this->settingService->bufferThresholdDefaultValue;
        $extendDuration  = $config['extend_duration'] ?? $this->settingService->extendDurationDefaultValue;
        $cartFrom        = $this->settingService->cartFromDefaultValue;

        // Cart Expiry Validation
        if ($cart->expire_at && now()->greaterThanOrEqualTo($cart->expire_at)) {
            $isRecentlyActive = $cart->updated_at && $cart->updated_at->gte(now()->subMinutes($bufferThreshold));

            if ($isRecentlyActive) {
                $cart->update(['expire_at' => now()->addMinutes($extendDuration)]);
            } else {
                $cart->items()->delete();
                $this->cartRepository->clearCoupon($cart->id);
                $this->cartRepository->clearCartDiscount($cart->id);
                throw new Exception("Your cart session has expired. Please add items to cart again.");
            }
        }

        // Fetch complete Cart calculation via CartService
        $cartCalculation = $this->cartService->getUserCart($userId);
        $summary = $cartCalculation['summary'];
        $cartItems = $cartCalculation['items'];

        if ($cartItems->isEmpty()) {
            throw new Exception("Cannot create quotation from an empty cart.");
        }

        // 4. Bulk Insert Transaction Lines
        //$this->orderRepository->createTransactionLines();

        // tumk ank kaz korte hobe ekhane
        
        // 5. Clear Cart Items and Reset Discounts
        $this->cartRepository->clearCart($cart->id);
        $this->cartRepository->clearCoupon($cart->id);
        $this->cartRepository->clearCartDiscount($cart->id);

    }

    /**
     * Update Pending Quotation
     */
    public function updatePendingQuotation(int $orderId, int $userId, array $data)
    {
        $transaction = $this->orderRepository->findOrderByIdAndUser($orderId, $userId);

        if (!$transaction) {
            throw new Exception("Order not found.");
        }

        //ekhane tumk ank kaz korte hobe. order update korte hobe
    }

    public function confirmQuotationToOrder(int $orderId, int $userId)
    {
        $transaction = $this->getOrderDetails($orderId, $userId);

        if ($transaction->status == 'confirmed') {
            throw new Exception("This transaction is already confirmed as an order.");
        }

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


    /**
     * Get Aggregated Summary Report for User Transactions
     */
    public function getOrderSummaryReport(int $userId): array
    {
        // Query Sales for specific user
        $salesTransactions = [ ];
        $totalOrders       = 0;
        $fullPaidOrders    = 0;
        $partialPaidOrders = 0;
        $unpaidOrders      = 0;

        $pendingOrders     = 0;
        $processingOrders  = 0;
        $receivedOrders    = 0;
        $successfulOrders  = 0;
        $cancelledOrders   = 0;
        $totalQuotations   = 0;

        $totalOrderAmount  = 0;
        $totalPaidAmount   = 0;
        $totalDueAmount    = 0;
        $totalSavedAmount  = 0;

        foreach ($salesTransactions as $txn) {
            // Count Quotations vs Real Orders
            if ((int) $txn->status == 'quotation') {
                $totalQuotations++;
                continue; // Quotation-কে মেইন অর্ডারের সাথে মেশানো হবে না
            }

            $totalOrders++;
            $finalAmt = (float) $txn->final_amount;
            $totalOrderAmount += $finalAmt;

            // Total Discount / Savings Calculation
            $saved = (float) ($txn->total_discount_amount ?? ($txn->discount_amount + $txn->coupon_discount_amount));
            $totalSavedAmount += $saved;

            // Payment Status Aggregation (Assuming relationship or payment_status column)
            $paymentStatus = strtolower($txn->payment_status ?? 'unpaid');
            if ($paymentStatus === 'paid') {
                $fullPaidOrders++;
                $totalPaidAmount += $finalAmt;
            } elseif ($paymentStatus === 'partial') {
                $partialPaidOrders++;
                $paid = (float) ($txn->total_paid ?? 0);
                $totalPaidAmount += $paid;
                $totalDueAmount += max(0, $finalAmt - $paid);
            } else {
                $unpaidOrders++;
                $totalDueAmount += $finalAmt;
            }

            // Order Delivery / Processing Status Aggregation
            $status = strtolower($txn->status ?? 'pending');
            switch ($status) {
                case 'pending':
                    $pendingOrders++;
                    break;
                case 'processing':
                case 'ordered':
                    $processingOrders++;
                    break;
                case 'received':
                case 'delivered':
                    $receivedOrders++;
                    break;
                case 'completed':
                case 'successful':
                    $successfulOrders++;
                    break;
                case 'cancelled':
                case 'canceled':
                    $cancelledOrders++;
                    break;
            }
        }

        return [
            'total_orders'        => $totalOrders,
            'full_paid_orders'    => $fullPaidOrders,
            'partial_paid_orders' => $partialPaidOrders,
            'unpaid_orders'       => $unpaidOrders,
            'pending_orders'      => $pendingOrders,
            'processing_orders'   => $processingOrders,
            'received_orders'     => $receivedOrders,
            'successful_orders'   => $successfulOrders,
            'cancelled_orders'    => $cancelledOrders,
            'total_quotations'    => $totalQuotations,
            'total_order_amount'  => $totalOrderAmount,
            'total_paid_amount'   => $totalPaidAmount,
            'total_due_amount'    => $totalDueAmount,
            'total_saved_amount'  => $totalSavedAmount,
        ];
    }
}
