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
        // ১. SettingService থেকে এক্সপায়ারি ও Grace Period কনফিগ আনা
        $config       = $this->settingService->getCartExpiryConfig();
        $expiresAt    = $this->settingService->getCartExpiresAt();

        $thresholdMin = $config['grace_threshold'] ?? 10; // শেষ 10 মিনিট
        $extendMin    = $config['extend_minutes'] ?? 25;  // 25 মিনিট এক্সটেন্ড

        // ২. কার্ট খুঁজে বের করা অথবা তৈরি করা
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            [
                'created_by' => $created_by,
                'cart_from'  => $data['cart_from'] ?? 'mobile_app',
                'expire_at'  => $expiresAt,
            ]
        );

        // ৩. পুরনো কার্ট হলে (wasRecentlyCreated == false)
        if (! $cart->wasRecentlyCreated) {

            // চেক ১: কার্টের নির্ধারিত সময় শেষ হয়েছে কিনা?
            //sExpired = $cart->expire_at && $cart->expire_at <= now();
            $isExpired = $cart->expire_at && now()->greaterThanOrEqualTo($cart->expire_at);
            if ($isExpired) {

            /*
             * চেক ২: ইউজার কি গত $thresholdMin (যেমন: 10) মিনিটের মধ্যে অ্যাক্টিভ ছিল?
             * (CartItem মডেলে $touches = ['cart'] থাকায় updated_at কারেন্ট অ্যাক্টিভিটি নির্দেশ করবে)
             */
                //$isRecentlyActive = $cart->updated_at >= now()->subMinutes($thresholdMin);
                $isRecentlyActive   = $cart->updated_at && $cart->updated_at->gte(now()->subMinutes($thresholdMin));
                if ($isRecentlyActive) {
                    // ক) ইউজার অ্যাক্টিভ ছিল! তাই কার্ট ক্লিয়ার না করে মেয়াদ 25 মিনিট বাড়িয়ে দেওয়া হলো
                    $cart->update([
                        'expire_at' => now()->addMinutes($extendMin),
                    ]);
                } else {
                    // খ) সত্যি সত্যিই ইনঅ্যাক্টিভ ছিল! তাই কার্ট আইটেম ডিলিট এবং এক্সপায়ারি টাইম নতুন করে রিসেট
                    $cart->items()->delete();

                    $cart->update([
                        'expire_at' => $expiresAt,
                    ]);
                }
            }
        }

        return $cart;
        return Cart::firstOrCreate(
            ['user_id' => $userId], //dealer/client user id
            [
                'created_by' => $created_by,
                'cart_from' => $data['cart_from'] ?? 'moible_app',
                'expire_at' => now()->addDays(1),
            ]
        );
    }

    /**
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

    /**
     * getSingleCart function
     * To get a specific user's cart by user id
     * @param integer $userId
     * @return Cart
     */
    public function getSingleCart(int $userId ): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId, 'cart_from' => 'moible_app'] //dealer/client user id
        );
    }
    /**
     * getSingleCartByCartAndUserId function
     * To get a specific user's cart by cart id and user id
     * @param integer $userId
     * @return Cart
     */
    public function getSingleCartByCartAndUserId(int $cartId,int $userId) : ?Cart
    {
        return Cart::where(['id' => $cartId, 'user_id' => $userId, 'cart_from' => 'moible_app'])->first();
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
        return CartItem::updateOrCreate(
            [
                'cart_id'      => $cart->id,
                'product_id'   => $data['product_id'],
                'variation_id' => $data['variation_id'] ?? null,
                //'type'         => $data['type'],
                'unit_price'   => $data['unit_price'],
            ],
            [
                'quantity'        => $data['quantity'],
                'unit_price'      => $data['unit_price'],
                'discount_amount' => $data['discount_amount'] ?? 0,
            ]
        );
    }

    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
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
            'coupon_code'     => $couponData['coupon_code'],
            'coupon_id'       => $couponData['coupon_id'] ?? null,
            'discount_amount' => $couponData['discount_amount'],
            'discount_type'   => $couponData['discount_type'] ?? 'fixed',
        ]);
    }

    public function clearCoupon(int $cartId): bool
    {
        return Cart::where('id', $cartId)->update([
            'coupon_code'     => null,
            'coupon_id'       => null,
            'discount_amount' => 0,
            'discount_type'   => null,
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
}
