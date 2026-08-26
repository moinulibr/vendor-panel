<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\AddToCartAllClearRequest;
use App\Http\Requests\Api\V1\App\AddToCartListRequest;
use App\Http\Requests\Api\V1\App\AddToCartRequest;
use App\Http\Requests\Api\V1\App\ApplyCouponRemoveRequest;
use App\Http\Requests\Api\V1\App\ApplyCouponRequest;
use App\Http\Requests\Api\V1\App\UpdateToCartRequest;
use App\Http\Resources\Api\V1\App\CartItemResource;
use App\Http\Resources\Api\V1\App\CartResource;
use App\Http\Swagger\CartApiDocInterface;
use App\Services\CartService;
use App\Utils\UserType;
use Illuminate\Http\JsonResponse;
use Exception;

class CartController extends BaseApiController implements CartApiDocInterface
{
    public function __construct(protected CartService $cartService) {}

    public function index(AddToCartListRequest $request): JsonResponse
    {
        try {
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::RETAILER) {
                $retailerId = $request->retailer_user_id;
            }

            $cartData = $this->cartService->getUserCart($retailerId);

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
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::RETAILER) {
                $retailerId = $request->retailer_user_id;
            }

            $cartItem = $this->cartService->addToCart($retailerId, $request->validated());

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
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::RETAILER) {
                $retailerId = $request->retailer_user_id;
            }

            $this->cartService->applyCoupon($retailerId, $request->coupon_code);

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

    public function removeCoupon(ApplyCouponRemoveRequest $request): JsonResponse
    {
        try {
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::RETAILER) {
                $retailerId = $request->retailer_user_id;
            }
            $this->cartService->removeCoupon($retailerId);

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

    public function clear(AddToCartAllClearRequest $request): JsonResponse
    {
        try {
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::RETAILER) {
                $retailerId = $request->retailer_user_id;
            }

            $this->cartService->clearCart($retailerId);

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
