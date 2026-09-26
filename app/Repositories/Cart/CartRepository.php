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
    
    public function getOrCreateCart(int $userId, ?int $created_by = null): Cart
    {
        $config       = $this->settingService->getCartExpiryConfig();
        $expiresAt    = $this->settingService->getCartExpiresAt();

        $bufferThreshold = $config['buffer_threshold'] ?? $this->settingService->bufferThresholdDefaultValue;
        $extendDuration  = $config['extend_duration'] ?? $this->settingService->extendDurationDefaultValue;

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            [
                'created_by' => $created_by,
                'cart_from'  => $data['cart_from'] ?? 'mobile_app',
                'expire_at'  => $expiresAt,
            ]
        );

        // 3. if card is not recently created (wasRecentlyCreated == false)
        if (! $cart->wasRecentlyCreated) {
            //Expired = $cart->expire_at && $cart->expire_at <= now();
            $isExpired = $cart->expire_at && now()->greaterThanOrEqualTo($cart->expire_at);
            if ($isExpired) {

            /*
             * 2. check - is user active recently. 
             * (CartItem -> $touches = ['cart'] - updated_at)
             */
                //$isRecentlyActive = $cart->updated_at >= now()->subMinutes($bufferThreshold);
                $isRecentlyActive   = $cart->updated_at && $cart->updated_at->gte(now()->subMinutes($bufferThreshold));
                if ($isRecentlyActive) {
                    //if user is active recently, extend the cart expiry
                    $cart->update([
                        'expire_at' => now()->addMinutes($extendDuration),
                    ]);
                } else {
                    // if user is not active recently, clear the cart
                    $cart->items()->delete();
                    $cart->update([
                        'expire_at'               => $expiresAt,
                        'coupon_code'             => null,
                        'coupon_id'               => null,
                        'coupon_discount_amount'  => 0.00,
                        'coupon_discount_type'    => null,
                        'discount_amount'         => 0.00,
                        'discount_type'           => null,
                        'sub_total'               => 0.00,
                        'final_amount'            => 0.00,
                        'shipping_charge'         => 0.00,
                        'tax_amount'              => 0.00,
                    ]);
                }
            }
        }

        return $cart;
        return Cart::firstOrCreate(['user_id' => $userId],['created_by' => $created_by,'cart_from' => $data['cart_from'] ?? 'moible_app','expire_at' => now()->addDays(1),]);
    }

    /**
     * getSingleCart function
     * To get a specific user's cart by user id
     * @param integer $userId
     * @return Cart
     */
    public function getSingleCart(int $userId): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId, 'cart_from' => 'moible_app'] //dealer/client user id
        );
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
        $quantity = $data['quantity'];
        $unitPrice = $data['unit_price'];
        $subTotal = $quantity * $unitPrice;
        $discountAmount = $data['discount_amount'] ?? 0;
        $final_amount = max(0, $subTotal - $discountAmount);

        return CartItem::updateOrCreate(
            [
                'cart_id'      => $cart->id,
                'product_id'   => $data['product_id'],
                'variation_id' => $data['variation_id'] ?? null,
                //'unit_price'   => $data['unit_price'],
            ],
            [
                'quantity'        => $quantity,
                'unit_price'      => $unitPrice,
                'sub_total'       => $subTotal,
                'discount_amount' => $discountAmount,
                'discount_type'   => $data['discount_type'] ?? 'fixed',
                'discount_id'     => $data['discount_id'] ?? null,
                'final_amount'    => $final_amount,
            ]
        );
    }

    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        $item = CartItem::find($cartItemId);
        if (!$item) return false;

        $subTotal = $quantity * $item->unit_price;
        $final_amount = max(0, $subTotal - $item->discount_amount);

        return $item->update([
            'quantity'  => $quantity,
            'sub_total' => $subTotal,
            'final_amount' => $final_amount,
        ]);

        return CartItem::where('id', $cartItemId)->update(['quantity' => $quantity]);
    }

    public function removeItem(int $cartItemId): bool
    {
        return CartItem::where('id', $cartItemId)->delete();
    }

    public function clearAllItemFromCart(int $cartId): bool
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }

    public function clearCart(int $cartId): bool
    {
        return Cart::where('id', $cartId)->delete();
    }

    public function updateCoupon(int $cartId, array $couponData): bool
    {
        return Cart::where('id', $cartId)->update([
            'coupon_code'            => $couponData['coupon_code'],
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
            'sub_total'    => $totals['sub_total'] ?? 0,
            'final_amount' => $totals['final_amount'] ?? 0,
        ]);
    }


    /** not using this
     * getAllCartsByUserId function
     * To get all carts for a specific user's by user id
     * @param integer $userId
     * @return Cart
     */
    public function getAllCartsByUserId(int $userId): ?Cart
    {
        return Cart::where(
            ['user_id' => $userId, 'cart_from' => 'moible_app']
        )->get();
    }
    /** not using this
     * getSingleCartByCartAndUserId function
     * To get a specific user's cart by cart id and user id
     * @param integer $userId
     * @return Cart
     */
    public function getSingleCartByCartAndUserId(int $cartId, int $userId): ?Cart
    {
        return Cart::where(['id' => $cartId, 'user_id' => $userId, 'cart_from' => 'moible_app'])->first();
    }
}
