<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\AddToCartRequest;
use App\Http\Requests\Api\V1\App\ApplyCouponRequest;
use App\Http\Resources\Api\V1\App\CartResource;
use App\Http\Swagger\CartApiDocInterface;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class CartController extends BaseApiController implements CartApiDocInterface
{
    public function __construct(protected CartService $cartService) {}

    public function index(): JsonResponse
    {
        try {
            $cartData = $this->cartService->getUserCart(auth()->id());
            return $this->sendResponse([
                'items'   => CartResource::collection($cartData['items']),
                'summary' => $cartData['summary']
            ], 'Cart fetched successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch cart.', ['error' => $e->getMessage()]);
        }
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $cartItem = $this->cartService->addToCart(auth()->id(), $request->validated());
            return $this->sendResponse(new CartResource($cartItem), 'Item added to cart successfully.', 201);
        } catch (Exception $e) {
            return $this->sendError('Failed to add item to cart.', ['error' => $e->getMessage()]);
        }
    }

    public function applyCoupon(ApplyCouponRequest $request): JsonResponse
    {
        try {
            $this->cartService->applyCoupon(auth()->id(), $request->coupon_code);
            return $this->sendResponse(null, 'Coupon applied successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to apply coupon.', ['error' => $e->getMessage()]);
        }
    }

    public function removeCoupon(): JsonResponse
    {
        try {
            $this->cartService->removeCoupon(auth()->id());
            return $this->sendResponse(null, 'Coupon removed successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to remove coupon.', ['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        try {
            $this->cartService->updateQuantity($id, $request->quantity);
            return $this->sendResponse(null, 'Cart item updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to update cart.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->cartService->removeItem($id);
            return $this->sendResponse(null, 'Item removed from cart successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to remove item.', ['error' => $e->getMessage()]);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $this->cartService->clearCart(auth()->id());
            return $this->sendResponse(null, 'Cart cleared successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to clear cart.', ['error' => $e->getMessage()]);
        }
    }
}
