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

        $grandTotal = 0;
        $totalDiscount = 0;

        foreach ($cartItems as $item) {
            $subtotal = $item->quantity * $item->unit_price;
            $discount = $item->discount_type === 'percentage'
                ? ($subtotal * ($item->discount_amount / 100))
                : ($item->discount_amount * $item->quantity);

            $grandTotal += $subtotal;
            $totalDiscount += $discount;
        }

        return [
            'items'   => $cartItems,
            'summary' => [
                'sub_total'      => $grandTotal,
                'total_discount' => $totalDiscount,
                'final_amount'   => $grandTotal - $totalDiscount,
                'total_items'    => $cartItems->sum('quantity')
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
        return $this->cartRepository->clearCart($cart->id);
    }
}
