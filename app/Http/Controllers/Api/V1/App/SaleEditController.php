<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\UpdateSaleRequest;
use App\Http\Resources\Api\V1\App\SaleResource;
use App\Services\SaleEditService;
use Illuminate\Http\JsonResponse;
use Throwable;

class SaleEditController extends BaseApiController
{
    public function __construct(
        protected SaleEditService $saleEditService,
    ) {}

    /**
     * Update (edit) an existing confirmed sale.
     *
     * Creates a new versioned Sale row, recalculates items/batches/ledger,
     * and marks the old version as historical.
     *
     * PUT /api/v1/app/sales/{id}
     */
    public function update(UpdateSaleRequest $request, int|string $id): JsonResponse
    {
        try {
            $data = $request->validated();

            if (!isset($data['edited_by']) && auth()->check()) {
                $data['edited_by'] = auth()->id();
            }

            $newSale = $this->saleEditService->editSale((int) $id, $data);

            return $this->jsonResponse(
                success: true,
                message: 'Sale updated successfully.',
                data: new SaleResource($newSale),
                statusCode: 200
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
}
