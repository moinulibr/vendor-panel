<?php

namespace App\Services;

use App\Models\ProductBatch;
use App\Repositories\Batch\Interface\BatchRepositoryInterface;
use RuntimeException;

class BatchService
{
    public function __construct(
        protected BatchRepositoryInterface $batchRepository
    ) {}

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Allocate qty from available batches using FIFO (oldest batch first, soonest-expiry first).
     *
     * Locks the selected rows with FOR UPDATE inside the caller's transaction so concurrent
     * sales cannot double-allocate the same qty.
     *
     * Returns an array of allocation segments, each with the batch record and qty consumed:
     * [
     *   ['batch' => ProductBatch, 'qty_used' => int, 'unit_cost' => float],
     *   ...
     * ]
     *
     * @throws RuntimeException When total available stock is insufficient.
     */
    public function allocateFIFO(
        int $productId,
        ?int $variationId,
        int $warehouseId,
        int $qtyNeeded
    ): array {
        // Fetch eligible batches ordered by expiry_date ASC (FEFO), then created_at ASC (FIFO).
        // Non-expiring batches (null expiry) go last.
        $batches = $this->batchRepository->getFIFOBatches($productId, $variationId, $warehouseId);

        $allocations = [];
        $remaining   = $qtyNeeded;

        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }

            /** @var ProductBatch $batch */
            $consume = min($batch->qty_available, $remaining);

            // Decrement qty_available on the batch immediately (within caller's DB::transaction).
            $this->batchRepository->decrementBatchQty($batch, $consume);

            $allocations[] = [
                'batch'     => $batch,
                'qty_used'  => $consume,
                'unit_cost' => (float) $batch->cost_price,
            ];

            $remaining -= $consume;
        }

        if ($remaining > 0) {
            throw new RuntimeException(
                "Insufficient stock for product_id={$productId}" .
                ($variationId ? ", variation_id={$variationId}" : '') .
                ", warehouse_id={$warehouseId}. Short by {$remaining} unit(s)."
            );
        }

        return $allocations;
    }

    /**
     * Create and persist a new ProductBatch.
     *
     * Generates a sequential batch_no (BAT-YYYY-NNNN) if not provided in $data.
     *
     * @param  array{
     *   product_id: int,
     *   warehouse_id: int,
     *   variation_id?: int|null,
     *   vendor_id?: int|null,
     *   qty_received: int,
     *   cost_price: float|numeric-string,
     *   selling_price_ref?: float|numeric-string|null,
     *   mrp?: float|numeric-string|null,
     *   mfg_date?: string|null,
     *   expiry_date?: string|null,
     *   batch_no?: string|null,
     *   supplier_batch_no?: string|null,
     *   serial_range?: string|null,
     *   notes?: string|null,
     *   purchase_bill_id?: int|null,
     *   purchase_item_id?: int|null,
     *   purchase_receipt_id?: int|null,
     *   purchase_receipt_item_id?: int|null,
     * } $data
     */
    public function createBatch(array $data): ProductBatch
    {
        $batchNo = $data['batch_no'] ?? $this->generateBatchNo();

        return $this->batchRepository->createBatch([
            'batch_no'                   => $batchNo,
            'product_id'                 => $data['product_id'],
            'variation_id'               => $data['variation_id'] ?? null,
            'vendor_id'                  => $data['vendor_id'] ?? null,
            'warehouse_id'               => $data['warehouse_id'],
            'purchase_bill_id'           => $data['purchase_bill_id'] ?? null,
            'purchase_item_id'           => $data['purchase_item_id'] ?? null,
            'purchase_receipt_id'        => $data['purchase_receipt_id'] ?? null,
            'purchase_receipt_item_id'   => $data['purchase_receipt_item_id'] ?? null,
            'mfg_date'                   => $data['mfg_date'] ?? null,
            'expiry_date'                => $data['expiry_date'] ?? null,
            'supplier_batch_no'          => $data['supplier_batch_no'] ?? null,
            'qty_received'               => $data['qty_received'],
            'qty_available'              => $data['qty_received'], // All received qty starts as available
            'qty_reserved'               => 0,
            'cost_price'                 => $data['cost_price'],
            'selling_price_ref'          => $data['selling_price_ref'] ?? null,
            'mrp'                        => $data['mrp'] ?? null,
            'status'                     => 'active',
            'serial_range'               => $data['serial_range'] ?? null,
            'notes'                      => $data['notes'] ?? null,
        ]);
    }

    // --------------------------------------------------------------------------
    // Private helpers
    // --------------------------------------------------------------------------

    /**
     * Generate a sequential batch number: BAT-YYYY-NNNN.
     */
    private function generateBatchNo(): string
    {
        $year   = now()->format('Y');
        $prefix = "BAT-{$year}-";

        $latest = $this->batchRepository->getLatestBatchNo($prefix);

        $next = 1;
        if ($latest) {
            $seq  = (int) substr($latest, strlen($prefix));
            $next = $seq + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
