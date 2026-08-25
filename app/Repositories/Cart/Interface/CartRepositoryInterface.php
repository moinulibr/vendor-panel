<?php

namespace App\Repositories\Cart\Interface;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function getOrCreateCart(int $userId, ?int $contactId = null): Cart;
    public function findItem(int $cartId, int $productId, ?int $variationId): ?CartItem;
    public function addOrUpdateItem(Cart $cart, array $data): CartItem;
    public function updateQuantity(int $cartItemId, int $quantity): bool;
    public function removeItem(int $cartItemId): bool;
    public function clearCart(int $cartId): bool;
}
