<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\CreateQuotationRequest;
use App\Http\Requests\Api\V1\App\OrderListRequest;
use App\Http\Requests\Api\V1\App\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\UpdateQuotationRequest;
use App\Http\Resources\Api\V1\App\OrderResource;
use App\Http\Swagger\OrderApiDocInterface;
use App\Services\OrderService;
use App\Utils\UserType;
use Exception;
use Illuminate\Http\JsonResponse;

class OrderController extends BaseApiController implements OrderApiDocInterface
{
    public function __construct(protected OrderService $orderService) {}

    public function index(OrderListRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $orders = $this->orderService->getUserOrders($userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Orders fetched successfully.',
                data: OrderResource::collection($orders)->response()->getData(true),
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

    public function storeQuotation(CreateQuotationRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $transaction = $this->orderService->createQuotationFromCart($userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Quotation submitted successfully.',
                data: new OrderResource($transaction),
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

    public function show(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $transaction = $this->orderService->getOrderDetails($id, $userId);

            return $this->jsonResponse(
                success: true,
                message: 'Order details fetched successfully.',
                data: new OrderResource($transaction),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 404
            );
        }
    }

    public function update(UpdateQuotationRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $transaction = $this->orderService->updatePendingQuotation($id, $userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Quotation updated successfully.',
                data: new OrderResource($transaction),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    public function confirmOrder(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $transaction = $this->orderService->confirmQuotationToOrder($id, $userId);

            return $this->jsonResponse(
                success: true,
                message: 'Order confirmed successfully. You can now proceed to payment.',
                data: new OrderResource($transaction),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    public function submitPayment(SubmitPaymentRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $payment = $this->orderService->submitPaymentAttachment(
                $id,
                $userId,
                $request->validated(),
                $request->file('document')
            );

            return $this->jsonResponse(
                success: true,
                message: 'Payment information submitted for verification.',
                data: $payment,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }
}
