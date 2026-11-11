<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\Sale\SaleListRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleRequest;
use App\Http\Requests\Api\V1\App\Sale\StoreSaleReturnRequest;
use App\Http\Requests\Api\V1\App\Sale\SubmitPaymentRequest;
use App\Http\Requests\Api\V1\App\Sale\UpdateSaleRequest;
use App\Http\Resources\Api\V1\App\Sale\SaleResource;
use App\Services\SaleService;
use App\Services\SaleEditService;
use App\Services\SaleReturnService;
use App\Utils\UserType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;

class SaleController extends BaseApiController
{
    public function __construct(
        protected SaleService $saleService,
        protected SaleEditService $saleEditService,
        protected SaleReturnService $saleReturnService
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
            $sale->load(['items.batchAllocations', 'payments', 'taxes', 'shippingAddress']);
            return $this->jsonResponse(true, $sale->status === 'quotation' ? 'Quotation created successfully.' : 'Sale created successfully.', new SaleResource($sale), 201);
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
            $sale->load(['items.batchAllocations', 'payments']);
            return $this->jsonResponse(true, 'Details fetched successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 404);
        }
    }

    public function update(UpdateSaleRequest $request, int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleEditService->editSale($id, $userId, $request->validated()['items'] ?? []);
            return $this->jsonResponse(true, 'Sale updated successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function confirmQuotation(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->confirmQuotation($id, $userId);
            return $this->jsonResponse(true, 'Quotation confirmed to Sale successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function cancelQuotation(int $id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $sale = $this->saleService->cancelQuotation($id, $userId);
            return $this->jsonResponse(true, 'Quotation cancelled successfully.', new SaleResource($sale), 200);
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
            return $this->jsonResponse(true, 'Payment submitted for verification.', null, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function returnSale(StoreSaleReturnRequest $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.sale_item_id' => 'required|integer',
                'items.*.qty_returned' => 'required|integer|min:1',
                'reason' => 'nullable|string|max:500'
            ]);

            $userId = auth()->id();
            $sale = $this->saleReturnService->createReturn(
                $id,
                $userId,
                $request->items,
                $request->reason ?? 'Customer Return'
            );

            return $this->jsonResponse(true, 'Sale returned successfully.', new SaleResource($sale), 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 400);
        }
    }

    public function summaryReport(): JsonResponse
    {
        try {
            $userId = auth()->id();
            $summary = $this->saleService->getSummaryReport($userId);
            return $this->jsonResponse(true, 'Summary report retrieved successfully.', $summary, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 422);
        }
    }

}
