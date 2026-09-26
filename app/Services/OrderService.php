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
    public function createQuotationFromCart(int $userId, array $requestData): Transaction
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

        return DB::transaction(function () use ($userId, $cart, $cartItems, $summary, $requestData) {

            // 1. Calculate Aggregated Calculations
            $subTotal          = $summary['sub_total'];
            $itemTotalDiscount = $summary['item_total_discount'];
            $cartDiscount      = $summary['cart_discount'];
            $couponDiscount    = $summary['coupon_discount'];
            $totalDiscount     = $summary['total_cart_discount'];
            $shippingCharge    = $summary['shipping_charge'] ?? 0.00;
            $finalAmount       = $summary['final_amount'];

            // 2. Create Master Transaction
            $transactionData = [
                'user_id'                => $userId,
                'created_by'             => auth()->id(),
                'location_id'            => 2, // Default Outlet ID
                'type'                   => 'sell',
                'status'                 => 'pending',
                'is_new'                 => 0,
                'is_pos'                 => 0,
                'quotation'              => 1,
                'invoice_no'             => $this->productUtil->generateInvoiceNumber(),
                'transaction_date'       => now(),
                'sub_total'              => $subTotal,
                'discount_type'          => $cart->discount_type,
                'discount_amount'        => $cartDiscount,
                'coupon_code'            => $cart->coupon_code,
                'coupon_id'              => $cart->coupon_id,
                'coupon_discount_amount' => $couponDiscount,
                'total_discount_amount'  => $totalDiscount,
                'shipping_charge'        => $shippingCharge,
                'final_amount'           => $finalAmount,
                'note'                   => $requestData['note'] ?? null,
                'mail_notification'      => 1,
                'sms_notification'       => 1,
            ];

            $transaction = $this->orderRepository->createQuotationTransaction($transactionData);

            // 3. Multi-Vendor Grouping & Calculations
            $vendorCart = $cartItems->groupBy(fn($item) => $item->product->user_id ?? 0);
            $linesData = [];

            foreach ($vendorCart as $vendorId => $items) {
                $vSubTotal = 0;
                $vItemDiscount = 0;

                foreach ($items as $item) {
                    $itemSub = $item->quantity * $item->unit_price;
                    $itemDisc = $item->discount_type === 'percentage'
                        ? ($itemSub * ($item->discount_amount / 100))
                        : ($item->discount_amount * $item->quantity);

                    $vSubTotal += $itemSub;
                    $vItemDiscount += $itemDisc;
                }

                // Vendor proportional discount ratio
                $vendorRatio = $subTotal > 0 ? ($vSubTotal / $subTotal) : 0;
                $vCartDiscount = $cartDiscount * $vendorRatio;
                $vCouponDiscount = $couponDiscount * $vendorRatio;
                $vTotalDiscount = $vItemDiscount + $vCartDiscount + $vCouponDiscount;
                $vFinalAmount = max(0, $vSubTotal - $vTotalDiscount);

                $vendorOrder = $this->orderRepository->createVendorOrder([
                    'transaction_id'  => $transaction->id,
                    'vendor_id'       => $vendorId,
                    'invoice_no'      => 'VND-' . strtoupper(uniqid()),
                    'sub_total'       => $vSubTotal,
                    'discount_amount' => $vTotalDiscount,
                    'shipping_charge' => 0.00,
                    'final_amount'    => $vFinalAmount,
                ]);

                foreach ($items as $item) {
                    $itemSubtotal = $item->quantity * $item->unit_price;
                    $itemDiscount = $item->discount_type === 'percentage'
                        ? ($itemSubtotal * ($item->discount_amount / 100))
                        : ($item->discount_amount * $item->quantity);

                    $linesData[] = [
                        'product_id'      => $item->product_id,
                        'variation_id'    => $item->variation_id,
                        'vendor_order_id' => $vendorOrder->id,
                        'quantity'        => $item->quantity,
                        'unit_price'      => $item->unit_price,
                        'sub_total'       => $itemSubtotal,
                        'discount_type'   => $item->discount_type ?? 'fixed',
                        'discount_amount' => $itemDiscount,
                        'net_total'       => max(0, $itemSubtotal - $itemDiscount),
                    ];
                }
            }

            // 4. Bulk Insert Transaction Lines
            $this->orderRepository->createTransactionLines($transaction, $linesData);

            // 5. Clear Cart Items and Reset Discounts
            $this->cartRepository->clearCart($cart->id);
            $this->cartRepository->clearCoupon($cart->id);
            $this->cartRepository->clearCartDiscount($cart->id);

            return $transaction;
        });
    }

    /**
     * Update Pending Quotation
     */
    public function updatePendingQuotation(int $orderId, int $userId, array $data): Transaction
    {
        $transaction = $this->orderRepository->findOrderByIdAndUser($orderId, $userId);

        if (!$transaction) {
            throw new Exception("Order not found.");
        }

        if ($transaction->status !== 'pending' || (int)$transaction->quotation !== 1) {
            throw new Exception("Only pending quotations can be modified.");
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
                        'unit_price'   => $item['unit_price'],
                        'sub_total'    => $lineSubtotal,
                        'net_total'    => $lineSubtotal,
                    ];
                }

                $this->orderRepository->syncTransactionLines($transaction, $linesData);

                $data['sub_total'] = $subTotal;
                $data['final_amount'] = max(0, $subTotal + $transaction->shipping_charge - $transaction->total_discount_amount);
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
