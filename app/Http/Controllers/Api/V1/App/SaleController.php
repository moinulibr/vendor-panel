<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\Sale\SaleListRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleRequest;
use App\Http\Requests\Api\V1\App\Sale\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\Sale\UpdateSaleRequest;
use App\Http\Resources\Api\V1\App\Sale\SaleResource;
use App\Services\SaleService;
use App\Services\SaleEditService;
use App\Utils\UserType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SaleController extends BaseApiController
{
    public function __construct(
        protected SaleService $saleService,
        protected SaleEditService $saleEditService
    ) {}

    public function index(SaleListRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $sales = $this->saleService->getUserSales($userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Sales fetched successfully.',
                data: SaleResource::collection($sales)->response()->getData(true),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $userId = auth()->id();
            if (auth()->user()->user_type == UserType::SR && $request->filled('user_base_id')) {
                $userId = $request->user_base_id;
            }

            $sale = $this->saleService->createSaleFromCart($userId, $request->validated());
            //Log::info(json_encode(new SaleResource($sale->load('items', 'items.batchAllocations', 'payments'))));
            return $this->jsonResponse(
                success: true,
                message: $sale->status === 'quotation' ? 'Quotation created successfully.' : 'Sale created successfully.',
                data: new SaleResource($sale->load('items', 'items.batchAllocations', 'payments')),
                statusCode: 201
            );
        } catch (Exception $e) {
            Log::error($e);
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->getSaleDetails($id, $userId);
            Log::info(json_encode(new SaleResource($sale->load('items', 'items.batchAllocations', 'payments'))));
            return $this->jsonResponse(
                success: true,
                message: 'Details fetched successfully.',
                data: new SaleResource($sale->load('items', 'items.batchAllocations', 'payments')),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 404);
        }
    }

    public function update(UpdateSaleRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();

            $sale = $this->saleEditService->updateQuotation($id, $userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Quotation updated successfully.',
                data: new SaleResource($sale),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function confirmQuotation(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->confirmQuotation($id, $userId);

            return $this->jsonResponse(
                success: true,
                message: 'Quotation confirmed to Sale successfully.',
                data: new SaleResource($sale),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function submitPayment(SubmitPaymentRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();

            if ($request->hasFile('document')) {
                $request->file('document')->store('payment_receipts', 'public');
            }

            $this->saleService->submitPayment($id, $userId, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Payment submitted for verification.',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function summaryReport(): JsonResponse
    {
        try {
            $userId = auth()->id();
            $summary = $this->saleService->getSummaryReport($userId);

            return $this->jsonResponse(
                success: true,
                message: 'Summary report retrieved successfully.',
                data: $summary,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 422);
        }
    }
}
