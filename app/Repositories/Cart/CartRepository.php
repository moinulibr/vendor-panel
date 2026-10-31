<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Services\SettingService;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function getOrCreateCart(int $userId, ?int $created_by = null, ?string $cartFrom = null): Cart
    {
        $config          = $this->settingService->getCartExpiryConfig();
        $expiresAt       = $this->settingService->getCartExpiresAt();
        $bufferThreshold = $config['buffer_threshold'] ?? $this->settingService->bufferThresholdDefaultValue;
        $extendDuration  = $config['extend_duration'] ?? $this->settingService->extendDurationDefaultValue;
        $defaultCartFrom = $cartFrom ?? $this->settingService->cartFromDefaultValue ?? 'mobile_dealer_app';

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            [
                'created_by' => $created_by,
                'cart_from'  => $defaultCartFrom,
                'expire_at'  => $expiresAt,
            ]
        );

        if (!$cart->wasRecentlyCreated) {
            $isExpired = $cart->expire_at && now()->greaterThanOrEqualTo($cart->expire_at);
            if ($isExpired) {
                $isRecentlyActive = $cart->updated_at && $cart->updated_at->gte(now()->subMinutes($bufferThreshold));
                if ($isRecentlyActive) {
                    $cart->update(['expire_at' => now()->addMinutes($extendDuration)]);
                } else {
                    // ✅ NEW: Don't delete items. Just extend expiry.
                    // Price updates are handled in CartService::getUserCart.
                    $cart->update(['expire_at' => $expiresAt]);
                }
            }
        }

        return $cart;
    }

    public function getSingleCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function getSingleCartByCartAndUserId(int $cartId, int $userId): ?Cart
    {
        return Cart::where(['id' => $cartId, 'user_id' => $userId])->first();
    }

    public function getAllCartsByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)->get();
    }

    public function findItem(int $cartId, int $productId, ?int $variationId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->where('variation_id', $variationId)
            ->first();
    }

    public function addOrUpdateItem(Cart $cart, array $data): CartItem
    {
        $quantity       = $data['quantity'];
        $unitPrice      = $data['unit_price'];
        $subTotal       = $quantity * $unitPrice;
        $discountAmount = $data['discount_amount'] ?? 0;
        $finalAmount    = max(0, $subTotal - $discountAmount);

        return CartItem::updateOrCreate(
            [
                'cart_id'      => $cart->id,
                'product_id'   => $data['product_id'],
                'variation_id' => $data['variation_id'] ?? null,
            ],
            [
                'vendor_id'              => $data['vendor_id'] ?? null,
                'product_label_snapshot' => $data['product_label_snapshot'] ?? null,
                'variant_label_snapshot' => $data['variant_label_snapshot'] ?? null,
                'type'                   => $data['type'] ?? 'single',
                'quantity'               => $quantity,
                'unit_price'             => $unitPrice,
                'sub_total'              => $subTotal,
                'discount_amount'        => $discountAmount,
                'discount_type'          => $data['discount_type'] ?? 'fixed',
                'discount_id'            => $data['discount_id'] ?? null,
                'final_amount'           => $finalAmount,
                'notes'                  => $data['notes'] ?? null,
            ]
        );
    }

    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        $item = CartItem::find($cartItemId);
        if (!$item) return false;

        $subTotal = $quantity * $item->unit_price;
        $discountAmount = $item->discount_type === 'percentage'
            ? ($subTotal * ($item->discount_amount / 100))
            : ($item->discount_amount * $quantity);
        $finalAmount = max(0, $subTotal - $discountAmount);

        return $item->update([
            'quantity'     => $quantity,
            'sub_total'    => $subTotal,
            'final_amount' => $finalAmount,
        ]);
    }

    public function removeItem(int $cartItemId): bool
    {
        return CartItem::where('id', $cartItemId)->delete() > 0;
    }

    public function clearAllItemFromCart(int $cartId): bool
    {
        return CartItem::where('cart_id', $cartId)->delete() > 0;
    }

    public function clearCart(int $cartId): bool
    {
        return Cart::where('id', $cartId)->update([
            'sub_total'              => 0,
            'item_total_discount'    => 0,
            'discount_amount'        => 0,
            'discount_type'          => null,
            'discount_id'            => null,
            'coupon_code'            => null,
            'coupon_id'              => null,
            'coupon_discount_amount' => 0,
            'coupon_discount_type'   => null,
            'shipping_charge'        => 0,
            'tax_amount'             => 0,
            'final_amount'           => 0,
        ]);
    }

    public function updateCoupon(int $cartId, array $couponData): bool
    {
        return Cart::where('id', $cartId)->update([
            'coupon_code'            => $couponData['coupon_code'] ?? null,
            'coupon_id'              => $couponData['coupon_id'] ?? null,
            'coupon_discount_amount' => $couponData['coupon_discount_amount'] ?? 0,
            'coupon_discount_type'   => $couponData['coupon_discount_type'] ?? 'fixed',
        ]);
    }

    public function clearCoupon(int $cartId): bool
    {
        return Cart::where('id', $cartId)->update([
            'coupon_code'            => null,
            'coupon_id'              => null,
            'coupon_discount_amount' => 0.00,
            'coupon_discount_type'   => null,
        ]);
    }

    public function updateCartDiscount(int $cartId, array $discountData): bool
    {
        return Cart::where('id', $cartId)->update([
            'discount_amount' => $discountData['discount_amount'] ?? 0,
            'discount_type'   => $discountData['discount_type'] ?? 'fixed',
        ]);
    }

    public function clearCartDiscount(int $cartId): bool
    {
        return Cart::where('id', $cartId)->update([
            'discount_amount' => 0.00,
            'discount_type'   => null,
        ]);
    }

    public function updateCartTotals(int $cartId, array $totals): bool
    {
        return Cart::where('id', $cartId)->update([
            'sub_total'           => $totals['sub_total'] ?? 0,
            'item_total_discount' => $totals['item_total_discount'] ?? 0,
            'tax_amount'          => $totals['tax_amount'] ?? 0,
            'final_amount'        => $totals['final_amount'] ?? 0,
        ]);
    }
}
