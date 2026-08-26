<?php

namespace App\Services;

use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Models\Product;
use App\Models\Variation;
use App\Repositories\Coupon\Interface\CouponRepositoryInterface;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use App\Utils\UserType;
use Exception;
class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected CouponRepositoryInterface $couponRepository,
        protected ProductRepositoryInterface $productRepository
        ) {}

    public function getUserCart(int $retailerId): array
    {
        //always match with retailer id. 
        $cart = $this->cartRepository->getOrCreateCart($retailerId);
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

    public function addToCart(int $retailerUserId, array $data): mixed
    {
        $productId = $data['type'] == "single" ? $data['product_id'] : $data['variation_id'];

        $product = $this->productRepository->findBySlugOrId($productId, null, $data['type']);

        $cart = $this->cartRepository->getOrCreateCart($retailerUserId, auth()->user()->id ?? null);
        
        $unitPrice = $product->sell_price;
        $existingItem = $this->cartRepository->findItem($cart->id, $data['product_id'], $data['variation_id'] ?? null);

        $newQuantity = $existingItem ? ($existingItem->quantity + $data['quantity']) : $data['quantity'];

        return $this->cartRepository->addOrUpdateItem($cart, [
            'product_id'   => $data['product_id'],
            'variation_id' => $data['variation_id'] ?? null,
            'quantity'     => $newQuantity,
            'type'         => $data['type'],
            'unit_price'   => $unitPrice,
        ]);
    }

    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        return $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    public function applyCoupon(int $userId, string $couponCode): bool
    {
        $cartData = $this->getUserCart($userId);
        $grossTotal = $cartData['summary']['gross_total'];

        if ($grossTotal <= 0) {
            throw new Exception('Cart total must be greater than zero to apply coupon.');
        }

        // Validate coupon from CouponRepository using actual logic
        $coupon = $this->couponRepository->findValidCoupon($couponCode, $grossTotal);

        if (!$coupon) {
            throw new Exception('Invalid or expired coupon code!');
        }

        $cart = $this->cartRepository->getOrCreateCart($userId);

        return $this->cartRepository->updateCoupon($cart->id, [
            'coupon_code'     => $coupon->code,
            'coupon_id'       => $coupon->id,
            'discount_amount' => $coupon->amount,
            'discount_type'   => $coupon->discount_type ?? 'fixed',
        ]);
    }

    public function removeCoupon(int $userId): bool
    {
        $cart = $this->cartRepository->getSingleCart($userId);
        return $this->cartRepository->clearCoupon($cart->id);
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
