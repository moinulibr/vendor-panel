<?php

namespace App\Repositories\Cart\Interface;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function getOrCreateCart(int $userId, ?int $contactId = null): Cart;
    public function getSingleCart(int $userId): Cart;
    public function findItem(int $cartId, int $productId, ?int $variationId): ?CartItem;
    public function addOrUpdateItem(Cart $cart, array $data): CartItem;
    public function updateQuantity(int $cartItemId, int $quantity): bool;
    public function removeItem(int $cartItemId): bool;
    public function clearCart(int $cartId): bool;

    // Coupon related DB methods
    public function updateCoupon(int $cartId, array $couponData): bool;
    public function clearCoupon(int $cartId): bool;
}
