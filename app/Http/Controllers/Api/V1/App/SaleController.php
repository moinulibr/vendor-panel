<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\StoreSaleRequest;
use App\Http\Resources\Api\V1\App\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use App\Services\BatchService;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class SaleController extends BaseApiController
{
    public function __construct(
        protected SaleService $saleService,
        protected BatchService $batchService,
        protected SaleRepositoryInterface $saleRepository,
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

            // Filter: status
            if ($request->filled('status')) {
                $query->where('status', $request->query('status'));
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
     * Store a newly created sale from cart.
     */
    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $userId = auth()->id() ?? (int) ($data['customer_id'] ?? 1);

            if (!isset($data['created_by']) && auth()->check()) {
                $data['created_by'] = auth()->id();
            }

            $sale = $this->saleService->createSaleFromCart($userId, $data);
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

    /**
     * Confirm a quotation: change status to confirmed, allocate batches, insert ledger entries.
     */
    public function confirmQuotation(int|string $id): JsonResponse
    {
        try {
            $sale = DB::transaction(function () use ($id) {
                /** @var Sale|null $sale */
                $sale = Sale::with(['items', 'customer'])->lockForUpdate()->find($id);

                if (!$sale) {
                    throw new RuntimeException("Sale #{$id} not found.");
                }

                if ($sale->status !== 'quotation') {
                    throw new RuntimeException("Sale #{$id} is not in quotation status (current status: {$sale->status}).");
                }

                // 1. Update status to confirmed
                $sale->update([
                    'status'             => 'confirmed',
                    'quote_converted_at' => now(),
                    'quote_converted_by' => auth()->id(),
                ]);

                // 2. Allocate batches for items tracking batch
                if ($sale->warehouse_id) {
                    foreach ($sale->items as $item) {
                        $product = $item->product ?? Product::find($item->product_id);
                        $trackBatch = $product?->track_batch ?? false;

                        if ($trackBatch && $item->qty > 0) {
                            $allocations = $this->batchService->allocateFIFO(
                                $item->product_id,
                                $item->variation_id,
                                $sale->warehouse_id,
                                $item->qty
                            );

                            foreach ($allocations as $alloc) {
                                $batch         = $alloc['batch'];
                                $qtyUsed       = $alloc['qty_used'];
                                $unitCost      = $alloc['unit_cost'];
                                $unitSalePrice = (float) $item->unit_price_snapshot;
                                $totalSale     = round($qtyUsed * $unitSalePrice, 2);
                                $costTotal     = round($qtyUsed * $unitCost, 2);

                                $this->saleRepository->createSaleItemBatch([
                                    'sale_id'           => $sale->id,
                                    'sale_item_id'      => $item->id,
                                    'batch_id'          => $batch->id,
                                    'qty_used'          => $qtyUsed,
                                    'unit_cost'         => $unitCost,
                                    'cost_total'        => $costTotal,
                                    'unit_sale_price'   => $unitSalePrice,
                                    'total_sale_amount' => $totalSale,
                                    'total_profit'      => round($totalSale - $costTotal, 2),
                                ]);
                            }
                        }
                    }
                }

                // 3. Insert PaymentLedger entries (Customer debit, Vendor credit)
                $this->insertLedgerEntriesForQuotation($sale);

                return $sale->fresh(['items.batchAllocations', 'payments', 'taxes', 'customer', 'salesperson']);
            });

            return $this->jsonResponse(
                success: true,
                message: 'Quotation confirmed successfully.',
                data: new SaleResource($sale),
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

    /**
     * Insert ledger entries for confirmed sale.
     */
    protected function insertLedgerEntriesForQuotation(Sale $sale): void
    {
        $now       = now();
        $entryDate = $sale->sale_date
            ? date('Y-m-d', strtotime($sale->sale_date))
            : $now->toDateString();
        $currency  = $sale->currency ?? 'BDT';
        $exchRate  = (float) ($sale->exchange_rate ?? 1);
        $createdBy = auth()->id() ?? $sale->created_by;

        // --- Customer debit (sale) ---
        if ($sale->customer_id) {
            $prevBalance = $this->saleRepository->getLatestLedgerBalance('customer', $sale->customer_id);
            $newBalance  = round($prevBalance + $sale->final_amount, 2);

            $this->saleRepository->insertLedgerEntry([
                'party_type'      => 'customer',
                'party_id'        => $sale->customer_id,
                'entry_date'      => $entryDate,
                'entry_at'        => $now,
                'entry_type'      => 'sale',
                'reference_type'  => 'Sale',
                'reference_id'    => $sale->id,
                'reference_no'    => $sale->invoice_no,
                'debit'           => $sale->final_amount,
                'credit'          => 0,
                'running_balance' => $newBalance,
                'currency'        => $currency,
                'exchange_rate'   => $exchRate,
                'description'     => "Sale invoice {$sale->invoice_no}",
                'notes'           => null,
                'created_by'      => $createdBy,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // --- Vendor credit (per vendor aggregated) ---
        $vendorTotals = [];
        foreach ($sale->items as $line) {
            $vendorId = $line->vendor_id ?? null;
            if (!$vendorId) {
                continue;
            }
            $vendorTotals[$vendorId] = ($vendorTotals[$vendorId] ?? 0.0)
                + (float) $line->vendor_payable_amount;
        }

        foreach ($vendorTotals as $vendorId => $payable) {
            if ($payable <= 0) {
                continue;
            }
            $prevBalance = $this->saleRepository->getLatestLedgerBalance('vendor', $vendorId);
            $newBalance  = round($prevBalance + $payable, 2);

            $this->saleRepository->insertLedgerEntry([
                'party_type'      => 'vendor',
                'party_id'        => $vendorId,
                'entry_date'      => $entryDate,
                'entry_at'        => $now,
                'entry_type'      => 'sale',
                'reference_type'  => 'Sale',
                'reference_id'    => $sale->id,
                'reference_no'    => $sale->invoice_no,
                'debit'           => 0,
                'credit'          => round($payable, 2),
                'running_balance' => $newBalance,
                'currency'        => $currency,
                'exchange_rate'   => $exchRate,
                'description'     => "Vendor payable for sale {$sale->invoice_no}",
                'notes'           => null,
                'created_by'      => $createdBy,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }
    }
}
