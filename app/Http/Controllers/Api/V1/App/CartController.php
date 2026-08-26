<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\AddToCartRequest;
use App\Http\Requests\Api\V1\App\ApplyCouponRequest;
use App\Http\Requests\Api\V1\App\UpdateToCartRequest;
use App\Http\Resources\Api\V1\App\CartItemResource;
use App\Http\Resources\Api\V1\App\CartResource;
use App\Http\Swagger\CartApiDocInterface;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Exception;

class CartController extends BaseApiController implements CartApiDocInterface
{
    public function __construct(protected CartService $cartService) {}

    public function index(): JsonResponse
    {
        try {
            $cartData = $this->cartService->getUserCart(auth()->id());

            return $this->jsonResponse(
                success: true,
                message: 'Cart fetched successfully.',
                data: new CartResource($cartData),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $cartItem = $this->cartService->addToCart(auth()->id(), $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Item added to cart successfully.',
                data: new CartItemResource($cartItem),
                statusCode: 201
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function update(UpdateToCartRequest $request, int $cartItemId): JsonResponse
    {
        try {
            $this->cartService->updateQuantity($cartItemId, $request->quantity);

            return $this->jsonResponse(
                success: true,
                message: 'Cart item updated successfully.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }


    public function applyCoupon(ApplyCouponRequest $request): JsonResponse
    {
        try {
            $this->cartService->applyCoupon(auth()->id(), $request->coupon_code);

            return $this->jsonResponse(
                success: true,
                message: 'Coupon applied successfully.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 422
            );
        }
    }

    public function removeCoupon(): JsonResponse
    {
        try {
            $this->cartService->removeCoupon(auth()->id());

            return $this->jsonResponse(
                success: true,
                message: 'Coupon removed successfully.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function removeCart(int $cartItemId): JsonResponse
    {
        try {
            $this->cartService->removeItem($cartItemId);

            return $this->jsonResponse(
                success: true,
                message: 'Item removed from cart successfully.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $this->cartService->clearCart(auth()->id());

            return $this->jsonResponse(
                success: true,
                message: 'Cart cleared successfully.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
