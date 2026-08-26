<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Cart\Interface\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getOrCreateCart(int $userId, ?int $created_by = null): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId], //retailer user id
            [
                'created_by' => $created_by,
                'cart_from' => $data['cart_from'] ?? 'moible_app'
            ]
        );
    }

    public function getSingleCart(int $userId ): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId] //retailer user id
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
        return CartItem::updateOrCreate(
            [
                'cart_id'      => $cart->id,
                'product_id'   => $data['product_id'],
                'variation_id' => $data['variation_id'] ?? null,
                'type'         => $data['type'],
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

    public function clearCart(int $cartId): bool
    {
        return CartItem::where('cart_id', $cartId)->delete();
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
}
