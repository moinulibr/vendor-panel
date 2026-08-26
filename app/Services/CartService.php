<?php

namespace App\Services;

use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Models\Product;
use App\Models\Variation;

class CartService
{
    public function __construct(protected CartRepositoryInterface $cartRepository) {}

    public function getUserCart(int $userId): array
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);
        $cartItems = $cart->items()->with(['product', 'variation'])->get();

        $itemSubtotal = 0;
        $itemTotalDiscount = 0;

        foreach ($cartItems as $item) {
            $subtotal = $item->quantity * $item->unit_price;
            $discount = $item->discount_type === 'percentage'
                ? ($subtotal * ($item->discount_amount / 100))
                : ($item->discount_amount * $item->quantity);

            $itemSubtotal += $subtotal;
            $itemTotalDiscount += $discount;
        }

        $grossTotal = $itemSubtotal - $itemTotalDiscount;

        // Overall Cart Level Discount / Coupon Calculation
        $cartDiscount = 0;
        if ($cart->discount_amount > 0) {
            $cartDiscount = $cart->discount_type === 'percentage'
                ? ($grossTotal * ($cart->discount_amount / 100))
                : $cart->discount_amount;
        }

        $finalAmount = max(0, $grossTotal - $cartDiscount);

        return [
            'items'   => $cartItems,
            'summary' => [
                'sub_total'           => $itemSubtotal,
                'item_total_discount' => $itemTotalDiscount,
                'gross_total'         => $grossTotal,
                'coupon_code'         => $cart->coupon_code,
                'cart_discount'       => $cartDiscount,
                'final_amount'        => $finalAmount,
                'total_items'         => $cartItems->sum('quantity')
            ]
        ];
    }

    public function addToCart(int $userId, array $data): mixed
    {
        $cart = $this->cartRepository->getOrCreateCart($userId, auth()->user()->contact_id ?? null);
        $product = Product::findOrFail($data['product_id']);
        $variation = isset($data['variation_id']) ? Variation::find($data['variation_id']) : null;

        $unitPrice = $variation ? $variation->default_sell_price : $product->sell_price;
        $existingItem = $this->cartRepository->findItem($cart->id, $data['product_id'], $data['variation_id'] ?? null);

        $newQuantity = $existingItem ? ($existingItem->quantity + $data['quantity']) : $data['quantity'];

        return $this->cartRepository->addOrUpdateItem($cart, [
            'product_id'   => $data['product_id'],
            'variation_id' => $data['variation_id'] ?? null,
            'quantity'     => $newQuantity,
            'unit_price'   => $unitPrice,
        ]);
    }

    public function applyCoupon(int $userId, string $couponCode): bool
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);

        // Example logic: Normally you would validate coupon from a coupons table here.
        // For demonstration, setting sample discount logic:
        $discountAmount = 50.00;
        $discountType = 'fixed';

        return $this->cartRepository->updateCoupon($cart->id, [
            'coupon_code'     => $couponCode,
            'discount_amount' => $discountAmount,
            'discount_type'   => $discountType,
        ]);
    }

    public function removeCoupon(int $userId): bool
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);
        return $this->cartRepository->clearCoupon($cart->id);
    }

    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        return $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    public function removeItem(int $cartItemId): bool
    {
        return $this->cartRepository->removeItem($cartItemId);
    }

    public function clearCart(int $userId): bool
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);
        // Clean coupon along with cart items
        $this->cartRepository->clearCoupon($cart->id);
        return $this->cartRepository->clearCart($cart->id);
    }
}
