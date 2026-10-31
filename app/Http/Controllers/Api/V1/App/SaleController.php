<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\StoreSaleRequest;
use App\Http\Resources\Api\V1\App\SaleResource;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SaleController extends BaseApiController
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    /**
     * Display a paginated listing of sales with filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Sale::query()->current();

            // Filter: payment_status
            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->query('payment_status'));
            }

            // Filter: channel
            if ($request->filled('channel')) {
                $query->where('channel', $request->query('channel'));
            }

            // Filter: date range
            $startDate = $request->query('date_from', $request->query('start_date'));
            $endDate   = $request->query('date_to', $request->query('end_date'));

            if ($startDate) {
                $query->whereDate('sale_date', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('sale_date', '<=', $endDate);
            }

            $perPage = (int) $request->query('per_page', 15);
            $sales = $query->with(['customer', 'salesperson'])
                ->latest('sale_date')
                ->latest('id')
                ->paginate($perPage);

            return $this->jsonResponse(
                success: true,
                message: 'Sales fetched successfully.',
                data: SaleResource::collection($sales)->response()->getData(true),
                statusCode: 200
            );
        } catch (Throwable $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if (!isset($data['created_by']) && auth()->check()) {
                $data['created_by'] = auth()->id();
            }

            $sale = $this->saleService->createSale($data);
            $sale->load(['items.batchAllocations', 'payments', 'taxes', 'customer', 'salesperson']);

            return $this->jsonResponse(
                success: true,
                message: 'Sale created successfully.',
                data: new SaleResource($sale),
                statusCode: 201
            );
        } catch (Throwable $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? (int) $e->getCode() : 400;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: $statusCode
            );
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(int|string $id): JsonResponse
    {
        try {
            $sale = Sale::with(['items.batchAllocations', 'payments', 'taxes', 'customer', 'salesperson'])->find($id);

            if (!$sale) {
                return $this->jsonResponse(
                    success: false,
                    message: 'Sale not found.',
                    statusCode: 404
                );
            }

            return $this->jsonResponse(
                success: true,
                message: 'Sale fetched successfully.',
                data: new SaleResource($sale),
                statusCode: 200
            );
        } catch (Throwable $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
