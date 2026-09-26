<?php

namespace App\Services;

use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Models\Product;
use App\Models\Variation;
use App\Repositories\Coupon\Interface\CouponRepositoryInterface;
use App\Repositories\Discount\Interface\DiscountRepositoryInterface;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Utils\UserType;
use Exception;
class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected CouponRepositoryInterface $couponRepository,
        protected ProductRepositoryInterface $productRepository,
        protected UserRepositoryInterface $userRepository,
        protected PriceService $priceService,
        protected DiscountRepositoryInterface $discountRepository,
        protected SettingService $settingService
        ) {}

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

        $grossTotal = max(0, $itemSubtotal - $itemTotalDiscount);

        // General Cart Level Discount
        $cartDiscount = 0;
        if ($cart->discount_amount > 0) {
            $cartDiscount = $cart->discount_type === 'percentage'
                ? ($grossTotal * ($cart->discount_amount / 100))
                : $cart->discount_amount;
        }

        $totalAfterDiscount = max(0, $grossTotal - $cartDiscount);

        // Coupon Discount Calculation
        $couponDiscount = 0;
        if ($cart->coupon_code && $cart->coupon_discount_amount > 0) {
            $couponDiscount = $cart->coupon_discount_type === 'percentage'
                ? ($totalAfterDiscount * ($cart->coupon_discount_amount / 100))
                : $cart->coupon_discount_amount;
        }

        $grandTotalDiscount = $itemTotalDiscount + $cartDiscount + $couponDiscount;
        $finalAmount = max(0, $itemSubtotal - $grandTotalDiscount) + $cart->shipping_charge;

        // Sync Calculated Values into Cart Schema
        $this->cartRepository->updateCartTotals($cart->id, [
            'sub_total'    => $itemSubtotal,
            'final_amount' => $finalAmount,
        ]);

        $userModel = $this->userRepository->findById($userId);

        $deliveryCharge  = $this->settingService->getDeliverySettings();
        $offers  = $this->settingService->getCouponAndDiscountSettings();
        return [
            'delivery_system' => $deliveryCharge,
            'offers'   => $offers,
            'summary' => [
                'sub_total'             => round($itemSubtotal, 2),
                'item_total_discount'   => round($itemTotalDiscount, 2),
                'gross_total'           => round($grossTotal, 2),
                'discount_amount'       => round($cart->discount_amount, 2),
                'discount_type'         => $cart->discount_type,
                'cart_discount'         => round($cartDiscount, 2),
                'coupon_code'           => $cart->coupon_code,
                'coupon_discount'       => round($couponDiscount, 2),
                'total_cart_discount'   => round($grandTotalDiscount, 2),
                'shipping_charge'       => round($cart->shipping_charge, 2),
                'final_amount'          => round($finalAmount, 2),
                'total_items'           => $cartItems->sum('quantity'),
                'expire_at'             => $cart->expire_at,
            ],
            'items'   => $cartItems,
            'user' => [
                'logged_in_user_id'   => auth()->id(),
                'logged_in_user_type' => auth()->user()?->user_type,
                'user_id'             => $userId,
                'type'                => $userModel?->user_type,
                'name'                => $userModel?->name,
            ],
        ];
    }

    public function addToCart(int $userId, array $data): mixed
    {
        $productId = $data['product_base_id'];

        //$product = $this->productRepository->findBySlugOrId($productId, $location = null);
        $product = $this->productRepository->findVariationBySlugOrId($productId, $location = null);

        $cart = $this->cartRepository->getOrCreateCart($userId, auth()->user()->id ?? null);

        $unitPrice = $this->priceService->getUserTypeWisePrice($userId, $product);
        //$unitPrice = $product->sell_price;
        $existingItem = $this->cartRepository->findItem($cart->id, $data['product_id'], $data['variation_id'] ?? null);

        $newQuantity = $existingItem ? ($existingItem->quantity + $data['quantity']) : $data['quantity'];

        return $this->cartRepository->addOrUpdateItem($cart, [
            'product_id'   => $data['product_id'],
            'variation_id' => $data['variation_id'] ?? null,
            'quantity'     => $newQuantity,
            'unit_price'   => $unitPrice,
        ]);
    }
    
    private function userTypeWisePrice(int $userId, Object $productVariation)
    {
       $user =  $this->userRepository->findById($userId);
       $price = [];
       if($user->user_type == UserType::DEALER){
        return $productVariation->dealer_price;
       }
       else if($user->user_type == UserType::GENERAL_APP_CUSTOMER){
        return $productVariation->wholesale_price;
       }else{
        return $productVariation->sell_price;
       }
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

        $coupon = $this->couponRepository->findValidCoupon($couponCode, $grossTotal);

        if (!$coupon) {
            throw new Exception('Invalid or expired coupon code!');
        }

        $cart = $this->cartRepository->getOrCreateCart($userId);

        return $this->cartRepository->updateCoupon($cart->id, [
            'coupon_code'            => $coupon->code,
            'coupon_id'              => $coupon->id,
            'coupon_discount_amount' => $coupon->amount,
            'coupon_discount_type'   => $coupon->discount_type ?? 'fixed',
        ]);
    }

    public function removeCoupon(int $userId): bool
    {
        $cart = $this->cartRepository->getSingleCart($userId);
        return $this->cartRepository->clearCoupon($cart->id);
    }

    /**
     * applyCartDiscount function
     *
     * @param integer $userId
     * @param string $discountTitle
     * @return boolean
     */
    public function applyCartDiscount(int $userId, string $discountTitle): bool
    {
        $cartData = $this->getUserCart($userId);
        $grossTotal = $cartData['summary']['gross_total'];

        $discount = $this->discountRepository->findValidDiscount($discountTitle, $userId, $grossTotal);

        if (!$discount) {
            throw new Exception('Invalid or non-applicable discount offer!');
        }

        $cart = $this->cartRepository->getOrCreateCart($userId);

        return $this->cartRepository->updateCartDiscount($cart->id, [
            'discount_amount' => $discount->amount,
            'discount_type'   => $discount->discount_type ?? 'fixed',
        ]);
    }

    public function removeCartDiscount(int $userId): bool
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);
        return $this->cartRepository->clearCartDiscount($cart->id);
    }

    public function removeItem(int $cartItemId): bool
    {
        return $this->cartRepository->removeItem($cartItemId);
    }

    public function clearCart(int $userId): bool
    {
        $cart = $this->cartRepository->getOrCreateCart($userId);
        // Clean coupon along with cart items
        //$this->cartRepository->clearCoupon($cart->id);
        $this->cartRepository->clearAllItemFromCart($cart->id);
        return $this->cartRepository->clearCart($cart->id);
    }
}
