<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Variation;
use App\Repositories\Batch\Interface\BatchRepositoryInterface;
use App\Repositories\Sale\Interface\SaleEditRepositoryInterface;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleEditService
{
    public function __construct(
        protected SaleService                 $saleService,
        protected BatchService                $batchService,
        protected SaleRepositoryInterface     $saleRepository,
        protected SaleEditRepositoryInterface $saleEditRepository,
        protected BatchRepositoryInterface    $batchRepository,
    ) {}

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Edit an existing sale by creating a new versioned row.
     *
     * Workflow:
     *  1. Load the current sale with items & batch allocations.
     *  2. Validate that it is editable (is_current, confirmed).
     *  3. Build new items list — KEEP old unit_price_snapshot for existing items,
     *     fetch CURRENT price for newly added items.
     *  4. Mark old sale as is_current = 0.
     *  5. Create new Sale row (version + 1, same parent_id chain).
     *  6. Insert new SaleItems, SaleItemBatches, SaleTaxes.
     *  7. Reverse old batch allocations, allocate new batches (delta-aware).
     *  8. Calculate delta_amount → update PaymentLedger (Customer Debit/Credit).
     *  9. Insert SaleEditHistory + SaleEditItems audit trail.
     *
     * Expected $data shape:
     * [
     *   'items' => [
     *     [
     *       'product_id'           => int,
     *       'variation_id'         => int|null,
     *       'qty'                  => int,
     *       // All other snapshot fields are optional for existing items
     *       // (carried forward from old sale). Required for NEW items only
     *       // if unit_price_snapshot is not explicitly set (auto-fetched).
     *     ],
     *   ],
     *   // Optional header overrides (fall back to old sale values)
     *   'shipping_charge'      => float|null,
     *   'round_off'            => float|null,
     *   'discount_type'        => string|null,
     *   'discount_value'       => float|null,
     *   'notes'                => string|null,
     *   'reason'               => string|null,   // edit reason for audit
     *   'edited_by'            => int|null,       // who performed the edit
     * ]
     *
     * @param  int   $saleId  The current sale's ID.
     * @param  array $data    The updated sale data.
     * @return Sale  The newly created Sale version.
     *
     * @throws RuntimeException On validation or stock errors.
     */
    public function editSale(int $saleId, array $data): Sale
    {
        return DB::transaction(function () use ($saleId, $data) {

            // ------------------------------------------------------------------
            // 1. Load current sale with relations
            // ------------------------------------------------------------------
            /** @var Sale|null $oldSale */
            $oldSale = Sale::with(['items.batchAllocations.batch', 'items.product', 'paymentSummary'])
                ->lockForUpdate()
                ->find($saleId);

            if (!$oldSale) {
                throw new RuntimeException("Sale #{$saleId} not found.");
            }

            if (!$oldSale->is_current) {
                throw new RuntimeException("Sale #{$saleId} is not the current version.");
            }

            if ($oldSale->status !== 'confirmed') {
                throw new RuntimeException(
                    "Sale #{$saleId} cannot be edited (status: {$oldSale->status}). Only confirmed sales can be edited."
                );
            }

            // ------------------------------------------------------------------
            // 2. Build a lookup of old items by product_id+variation_id
            // ------------------------------------------------------------------
            $oldItemsMap = [];
            foreach ($oldSale->items as $oldItem) {
                $key = $this->itemKey($oldItem->product_id, $oldItem->variation_id);
                $oldItemsMap[$key] = $oldItem;
            }

            // ------------------------------------------------------------------
            // 3. Process new items — preserve price for existing, fetch for new
            // ------------------------------------------------------------------
            $newItems        = $data['items'] ?? [];
            $editItems       = []; // for SaleEditItems audit
            $processedNewItems = [];

            if (empty($newItems)) {
                throw new RuntimeException('At least one item is required when editing a sale.');
            }

            foreach ($newItems as $newItem) {
                $key = $this->itemKey($newItem['product_id'], $newItem['variation_id'] ?? null);

                if (isset($oldItemsMap[$key])) {
                    // ── Existing item: KEEP old unit_price_snapshot ──
                    $existingOldItem = $oldItemsMap[$key];

                    $newItem['unit_price_snapshot'] = (float) $existingOldItem->unit_price_snapshot;

                    // Carry forward snapshots from old item if not explicitly provided
                    $newItem = $this->carryForwardSnapshots($newItem, $existingOldItem);

                    // Track the change for audit
                    $editItems[] = [
                        'change_type'  => ($newItem['qty'] != $existingOldItem->qty) ? 'qty_changed' : 'unchanged',
                        'old_qty'      => $existingOldItem->qty,
                        'new_qty'      => (int) $newItem['qty'],
                        'old_price'    => (float) $existingOldItem->unit_price_snapshot,
                        'new_price'    => (float) $newItem['unit_price_snapshot'],
                        'old_discount' => (float) $existingOldItem->line_discount,
                        'new_discount' => (float) ($newItem['line_discount'] ?? $existingOldItem->line_discount),
                        '_key'         => $key,
                    ];
                } else {
                    // ── New item: fetch CURRENT price from Product/Variation ──
                    if (!isset($newItem['unit_price_snapshot'])) {
                        $newItem['unit_price_snapshot'] = $this->fetchCurrentPrice(
                            $newItem['product_id'],
                            $newItem['variation_id'] ?? null,
                            $data['customer_type'] ?? $oldSale->customer_type ?? 'regular'
                        );
                    }

                    // Ensure product snapshots are filled for new items
                    $newItem = $this->fillProductSnapshots($newItem);

                    $editItems[] = [
                        'change_type'  => 'added',
                        'old_qty'      => 0,
                        'new_qty'      => (int) $newItem['qty'],
                        'old_price'    => 0,
                        'new_price'    => (float) $newItem['unit_price_snapshot'],
                        'old_discount' => 0,
                        'new_discount' => (float) ($newItem['line_discount'] ?? 0),
                        '_key'         => $key,
                    ];
                }

                $processedNewItems[$key] = $newItem;
            }

            // Detect removed items (in old but not in new)
            foreach ($oldItemsMap as $key => $oldItem) {
                if (!isset($processedNewItems[$key])) {
                    $editItems[] = [
                        'change_type'  => 'removed',
                        'old_qty'      => $oldItem->qty,
                        'new_qty'      => 0,
                        'old_price'    => (float) $oldItem->unit_price_snapshot,
                        'new_price'    => 0,
                        'old_discount' => (float) $oldItem->line_discount,
                        'new_discount' => 0,
                        '_key'         => $key,
                    ];
                }
            }

            // ------------------------------------------------------------------
            // 4. Calculate new sale totals (mirrors SaleService logic)
            // ------------------------------------------------------------------
            $processedItems = $this->processItems(array_values($processedNewItems));

            $subTotal          = $processedItems['sub_total'];
            $itemTotalDiscount = $processedItems['item_total_discount'];
            $grossTotal        = $subTotal - $itemTotalDiscount;

            $shippingCharge = (float) ($data['shipping_charge'] ?? $oldSale->shipping_charge ?? 0);
            $roundOff       = (float) ($data['round_off'] ?? $oldSale->round_off ?? 0);

            $discountType   = $data['discount_type'] ?? $oldSale->discount_type;
            $discountValue  = (float) ($data['discount_value'] ?? $oldSale->discount_value ?? 0);
            $discountAmount = $this->resolveDiscountAmount($discountType, $discountValue, $grossTotal);

            $couponDiscountType   = $data['coupon_discount_type'] ?? $oldSale->coupon_discount_type;
            $couponDiscountValue  = (float) ($data['coupon_discount_value'] ?? $oldSale->coupon_discount_value ?? 0);
            $couponDiscountAmount = $this->resolveDiscountAmount(
                $couponDiscountType,
                $couponDiscountValue,
                $grossTotal - $discountAmount
            );

            $totalInvoiceDiscount = $discountAmount + $couponDiscountAmount;

            $processedItems['lines'] = $this->allocateInvoiceDiscount(
                $processedItems['lines'],
                $totalInvoiceDiscount,
                $grossTotal
            );

            $taxAmount = $processedItems['tax_total'];

            $newFinalAmount = round(
                $grossTotal - $totalInvoiceDiscount + $shippingCharge + $taxAmount + $roundOff,
                2
            );

            // Carry forward payment amounts from old sale
            $paidAmount = (float) $oldSale->paid_amount;
            $dueAmount  = round($newFinalAmount - $paidAmount, 2);

            $paymentStatus = match (true) {
                $newFinalAmount <= 0          => 'paid',
                $paidAmount <= 0              => 'unpaid',
                $paidAmount >= $newFinalAmount => 'paid',
                default                       => 'partially_paid',
            };

            // ------------------------------------------------------------------
            // 5. Mark old sale as historical (is_current = false)
            // ------------------------------------------------------------------
            $this->saleEditRepository->updateOldSaleToHistory($oldSale);

            // ------------------------------------------------------------------
            // 6. Create new Sale row (version + 1)
            // ------------------------------------------------------------------
            $parentId   = $oldSale->parent_id ?? $oldSale->id;
            $newVersion = $oldSale->version + 1;

            $newSale = $this->saleEditRepository->createNewVersion([
                'parent_id'                 => $parentId,
                'version'                   => $newVersion,
                'is_current'                => true,
                'invoice_no'                => $oldSale->invoice_no,
                'channel'                   => $data['channel'] ?? $oldSale->channel,
                'source_ref'                => $data['source_ref'] ?? $oldSale->source_ref,
                'customer_id'               => $data['customer_id'] ?? $oldSale->customer_id,
                'customer_type'             => $data['customer_type'] ?? $oldSale->customer_type,
                'warehouse_id'              => $data['warehouse_id'] ?? $oldSale->warehouse_id,
                'sales_person_id'           => $data['sales_person_id'] ?? $oldSale->sales_person_id,
                'sub_total'                 => $subTotal,
                'item_total_discount'       => $itemTotalDiscount,
                'gross_total'               => $grossTotal,
                'discount_type'             => $discountType,
                'discount_value'            => $discountValue,
                'discount_amount'           => $discountAmount,
                'coupon_id'                 => $data['coupon_id'] ?? $oldSale->coupon_id,
                'coupon_discount_type'      => $couponDiscountType,
                'coupon_discount_value'     => $couponDiscountValue,
                'coupon_discount_amount'    => $couponDiscountAmount,
                'shipping_charge'           => $shippingCharge,
                'tax_amount'                => $taxAmount,
                'round_off'                 => $roundOff,
                'final_amount'              => $newFinalAmount,
                'paid_amount'               => $paidAmount,
                'due_amount'                => $dueAmount,
                'sale_date'                 => $oldSale->sale_date,
                'status'                    => 'confirmed',
                'payment_status'            => $paymentStatus,
                'shipping_status'           => $oldSale->shipping_status,
                'quote_valid_until'         => $oldSale->quote_valid_until,
                'quote_converted_at'        => $oldSale->quote_converted_at,
                'quote_converted_by'        => $oldSale->quote_converted_by,
                'quote_notes'               => $oldSale->quote_notes,
                'currency'                  => $data['currency'] ?? $oldSale->currency ?? 'BDT',
                'exchange_rate'             => $data['exchange_rate'] ?? $oldSale->exchange_rate ?? 1,
                'notes'                     => $data['notes'] ?? $oldSale->notes,
                'is_editable_for_admin'     => $oldSale->is_editable_for_admin,
                'is_editable_for_staff'     => $oldSale->is_editable_for_staff,
                'is_editable_for_customer'  => $oldSale->is_editable_for_customer,
                'created_by'                => $data['edited_by'] ?? auth()->id() ?? $oldSale->created_by,
            ]);

            // ------------------------------------------------------------------
            // 7. Reverse old batch allocations (return stock to batches)
            // ------------------------------------------------------------------
            foreach ($oldSale->items as $oldItem) {
                foreach ($oldItem->batchAllocations as $alloc) {
                    $alloc->batch->increment('qty_available', $alloc->qty_used);

                    if ($alloc->batch->status === 'depleted') {
                        $alloc->batch->update(['status' => 'active']);
                    }
                }
            }

            // ------------------------------------------------------------------
            // 8. Insert new SaleItems + SaleItemBatches + SaleTaxes
            // ------------------------------------------------------------------
            $warehouseId = $newSale->warehouse_id;

            foreach ($processedItems['lines'] as $line) {
                $saleItem = $this->saleRepository->createSaleItem([
                    'sale_id'                               => $newSale->id,
                    'product_id'                            => $line['product_id'],
                    'variation_id'                          => $line['variation_id'],
                    'vendor_id'                             => $line['vendor_id'],
                    'product_name_snapshot'                 => $line['product_name_snapshot'],
                    'variant_label_snapshot'                => $line['variant_label_snapshot'],
                    'invoice_name_snapshot'                 => $line['invoice_name_snapshot'],
                    'image_snapshot'                        => $line['image_snapshot'],
                    'unit_price_snapshot'                   => $line['unit_price_snapshot'],
                    'customer_type_snapshot'                => $line['customer_type_snapshot'],
                    'qty'                                   => $line['qty'],
                    'line_subtotal'                         => $line['line_subtotal'],
                    'allow_discount'                        => $line['allow_discount'],
                    'line_discount'                         => $line['line_discount'],
                    'line_discount_percent'                 => $line['line_discount_percent'],
                    'taxable_amount'                        => $line['taxable_amount'],
                    'tax_type_snapshot'                     => $line['tax_type_snapshot'],
                    'tax_rate_snapshot'                     => $line['tax_rate_snapshot'],
                    'tax_amount'                            => $line['tax_amount'],
                    'allocated_inv_disc'                    => $line['allocated_inv_disc'],
                    'line_total'                            => $line['line_total'],
                    'vendor_commission_type_snapshot'       => $line['vendor_commission_type_snapshot'],
                    'vendor_commission_value_snapshot'      => $line['vendor_commission_value_snapshot'],
                    'vendor_commission_amount'              => $line['vendor_commission_amount'],
                    'vendor_payable_amount'                 => $line['vendor_payable_amount'],
                    'salesperson_commission_type_snapshot'  => $line['salesperson_commission_type_snapshot'],
                    'salesperson_commission_value_snapshot' => $line['salesperson_commission_value_snapshot'],
                    'salesperson_commission_amount'         => $line['salesperson_commission_amount'],
                    'is_combo'                              => $line['is_combo'],
                    'status'                                => 'active',
                    'line_note'                             => $line['line_note'],
                ]);

                // Batch allocation (FIFO) for batch-tracked products
                if (!empty($line['track_batch']) && $warehouseId) {
                    $allocations = $this->batchService->allocateFIFO(
                        $line['product_id'],
                        $line['variation_id'],
                        $warehouseId,
                        $line['qty']
                    );

                    foreach ($allocations as $alloc) {
                        $batch         = $alloc['batch'];
                        $qtyUsed       = $alloc['qty_used'];
                        $unitCost      = $alloc['unit_cost'];
                        $unitSalePrice = (float) $line['unit_price_snapshot'];
                        $totalSale     = round($qtyUsed * $unitSalePrice, 2);
                        $costTotal     = round($qtyUsed * $unitCost, 2);

                        $this->saleRepository->createSaleItemBatch([
                            'sale_id'           => $newSale->id,
                            'sale_item_id'      => $saleItem->id,
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

                // Per-item tax row
                if ($line['tax_type_snapshot'] !== 'exempt' && $line['tax_amount'] > 0) {
                    $this->saleRepository->createSaleTax([
                        'sale_id'        => $newSale->id,
                        'sale_item_id'   => $saleItem->id,
                        'tax_name'       => $line['tax_name'] ?? null,
                        'tax_category'   => $line['tax_category'] ?? null,
                        'hs_code'        => $line['hs_code'] ?? null,
                        'tax_rate'       => $line['tax_rate_snapshot'],
                        'taxable_amount' => $line['taxable_amount'],
                        'tax_amount'     => $line['tax_amount'],
                    ]);
                }
            }

            // ------------------------------------------------------------------
            // 9. PaymentLedger: delta adjustment (Customer Debit/Credit)
            // ------------------------------------------------------------------
            $oldFinalAmount = (float) $oldSale->final_amount;
            $deltaAmount    = round($newFinalAmount - $oldFinalAmount, 2);

            if ($deltaAmount != 0 && $newSale->customer_id) {
                $this->insertDeltaLedgerEntry($newSale, $deltaAmount, $data);
            }

            // ------------------------------------------------------------------
            // 10. Re-link existing SalePaymentSummary to new sale version
            // ------------------------------------------------------------------
            if ($oldSale->paymentSummary) {
                $oldSale->paymentSummary->update([
                    'sale_id' => $newSale->id,
                ]);
            }

            // ------------------------------------------------------------------
            // 11. Re-link existing payments to the new sale version
            // ------------------------------------------------------------------
            DB::table('sale_payments')
                ->where('sale_id', $oldSale->id)
                ->update(['sale_id' => $newSale->id]);

            // ------------------------------------------------------------------
            // 12. Insert SaleEditHistory audit record
            // ------------------------------------------------------------------
            $changeType = $this->determineChangeType($editItems);

            $this->saleEditRepository->createEditHistory([
                'sale_id'      => $newSale->id,
                'version_from' => $oldSale->version,
                'version_to'   => $newVersion,
                'change_type'  => $changeType,
                'delta_amount' => $deltaAmount,
                'reason'       => $data['reason'] ?? null,
                'changed_by'   => $data['edited_by'] ?? auth()->id(),
                'changed_at'   => now(),
            ]);

            // ------------------------------------------------------------------
            // 13. Insert SaleEditItems audit records
            // ------------------------------------------------------------------
            $saleEditItemRecords = [];
            foreach ($editItems as $editItem) {
                if ($editItem['change_type'] === 'unchanged') {
                    continue; // Skip items with no actual change
                }
                $saleEditItemRecords[] = [
                    'sale_id'      => $newSale->id,
                    'sale_item_id' => null,
                    'change_type'  => $editItem['change_type'],
                    'old_qty'      => $editItem['old_qty'],
                    'new_qty'      => $editItem['new_qty'],
                    'old_price'    => $editItem['old_price'],
                    'new_price'    => $editItem['new_price'],
                    'old_discount' => $editItem['old_discount'],
                    'new_discount' => $editItem['new_discount'],
                    'changed_at'   => now(),
                ];
            }

            if (!empty($saleEditItemRecords)) {
                $this->saleEditRepository->createEditItems($saleEditItemRecords);
            }

            return $newSale->fresh([
                'items.batchAllocations',
                'payments',
                'taxes',
                'customer',
                'salesperson',
            ]);
        });
    }

    // =========================================================================
    // Private – item processing (mirrors SaleService logic)
    // =========================================================================

    /**
     * Process all items: compute per-line financial figures.
     * Mirrors SaleService::processItems() exactly.
     */
    private function processItems(array $items): array
    {
        $lines             = [];
        $subTotal          = 0.0;
        $itemTotalDiscount = 0.0;
        $taxTotal          = 0.0;

        foreach ($items as $item) {
            $qty          = (int) $item['qty'];
            $unitPrice    = (float) $item['unit_price_snapshot'];
            $lineSubtotal = round($qty * $unitPrice, 2);

            $allowDiscount   = (bool) ($item['allow_discount'] ?? true);
            $lineDiscount    = $allowDiscount ? round((float) ($item['line_discount'] ?? 0), 2) : 0.0;
            $lineDiscPercent = $allowDiscount ? round((float) ($item['line_discount_percent'] ?? 0), 2) : 0.0;

            $taxType = $item['tax_type_snapshot'] ?? 'exempt';
            $taxRate = (float) ($item['tax_rate_snapshot'] ?? 0);

            [$taxableAmount, $taxAmount] = $this->computeLineTax(
                $lineSubtotal,
                $lineDiscount,
                $taxType,
                $taxRate
            );

            $vcType        = $item['vendor_commission_type_snapshot'] ?? null;
            $vcValue       = (float) ($item['vendor_commission_value_snapshot'] ?? 0);
            $vcAmount      = $this->resolveCommissionAmount($vcType, $vcValue, $lineSubtotal - $lineDiscount);
            $vendorPayable = round(($lineSubtotal - $lineDiscount) - $vcAmount, 2);

            $spType   = $item['salesperson_commission_type_snapshot'] ?? null;
            $spValue  = (float) ($item['salesperson_commission_value_snapshot'] ?? 0);
            $spAmount = $this->resolveCommissionAmount($spType, $spValue, $lineSubtotal - $lineDiscount);

            $lineTotal = round($lineSubtotal - $lineDiscount + $taxAmount, 2);

            $subTotal          += $lineSubtotal;
            $itemTotalDiscount += $lineDiscount;
            $taxTotal          += $taxAmount;

            $lines[] = array_merge($item, [
                'line_subtotal'                         => $lineSubtotal,
                'line_discount'                         => $lineDiscount,
                'line_discount_percent'                 => $lineDiscPercent,
                'taxable_amount'                        => $taxableAmount,
                'tax_type_snapshot'                     => $taxType,
                'tax_rate_snapshot'                     => $taxRate,
                'tax_amount'                            => $taxAmount,
                'allocated_inv_disc'                    => 0.0,
                'line_total'                            => $lineTotal,
                'vendor_commission_type_snapshot'       => $vcType,
                'vendor_commission_value_snapshot'      => $vcValue,
                'vendor_commission_amount'              => $vcAmount,
                'vendor_payable_amount'                 => $vendorPayable,
                'salesperson_commission_type_snapshot'  => $spType,
                'salesperson_commission_value_snapshot' => $spValue,
                'salesperson_commission_amount'         => $spAmount,
                'allow_discount'                        => $allowDiscount,
                'is_combo'                              => (bool) ($item['is_combo'] ?? false),
                'track_batch'                           => (bool) ($item['track_batch'] ?? false),
            ]);
        }

        return [
            'lines'               => $lines,
            'sub_total'           => round($subTotal, 2),
            'item_total_discount' => round($itemTotalDiscount, 2),
            'tax_total'           => round($taxTotal, 2),
        ];
    }

    /**
     * Compute taxable_amount and tax_amount for a single line.
     */
    private function computeLineTax(
        float $lineSubtotal,
        float $lineDiscount,
        string $taxType,
        float $taxRate
    ): array {
        $base = $lineSubtotal - $lineDiscount;

        return match ($taxType) {
            'exclusive' => [
                round($base, 2),
                round($base * $taxRate / 100, 2),
            ],
            'inclusive' => [
                round($base / (1 + $taxRate / 100), 2),
                round($base - ($base / (1 + $taxRate / 100)), 2),
            ],
            default => [round($base, 2), 0.0],
        };
    }

    /**
     * Allocate total invoice-level discount proportionally across lines.
     */
    private function allocateInvoiceDiscount(array $lines, float $totalDiscount, float $grossTotal): array
    {
        if ($totalDiscount <= 0 || $grossTotal <= 0) {
            return $lines;
        }

        $allocated = 0.0;
        $lastIndex = count($lines) - 1;

        foreach ($lines as $i => &$line) {
            if ($i === $lastIndex) {
                $share = round($totalDiscount - $allocated, 2);
            } else {
                $lineBase = $line['line_subtotal'] - $line['line_discount'];
                $share    = round(($lineBase / $grossTotal) * $totalDiscount, 2);
            }

            $line['allocated_inv_disc'] = $share;
            $line['line_total'] = round(
                $line['line_subtotal'] - $line['line_discount'] - $share + $line['tax_amount'],
                2
            );
            $allocated += $share;
        }
        unset($line);

        return $lines;
    }

    private function resolveDiscountAmount(?string $type, float $value, float $base): float
    {
        if ($value <= 0 || !$type) {
            return 0.0;
        }

        return match ($type) {
            'percentage' => round($base * $value / 100, 2),
            'fixed'      => round(min($value, $base), 2),
            default      => 0.0,
        };
    }

    private function resolveCommissionAmount(?string $type, float $value, float $base): float
    {
        if ($value <= 0 || !$type) {
            return 0.0;
        }

        return match ($type) {
            'percentage' => round($base * $value / 100, 2),
            'fixed'      => round($value, 2),
            default      => 0.0,
        };
    }

    // =========================================================================
    // Private – helpers
    // =========================================================================

    /**
     * Generate a unique key for a product+variation combination.
     */
    private function itemKey(int $productId, ?int $variationId): string
    {
        return $productId . ':' . ($variationId ?? 0);
    }

    /**
     * Carry forward snapshot fields from the old SaleItem to the new item data.
     * Only fills fields that are not already explicitly set in $newItem.
     */
    private function carryForwardSnapshots(array $newItem, $oldItem): array
    {
        $snapshotFields = [
            'product_name_snapshot',
            'variant_label_snapshot',
            'invoice_name_snapshot',
            'image_snapshot',
            'customer_type_snapshot',
            'allow_discount',
            'tax_type_snapshot',
            'tax_rate_snapshot',
            'tax_name',
            'tax_category',
            'hs_code',
            'vendor_id',
            'vendor_commission_type_snapshot',
            'vendor_commission_value_snapshot',
            'salesperson_commission_type_snapshot',
            'salesperson_commission_value_snapshot',
            'is_combo',
            'track_batch',
            'line_note',
            'line_discount',
            'line_discount_percent',
        ];

        foreach ($snapshotFields as $field) {
            if (!array_key_exists($field, $newItem)) {
                $newItem[$field] = $oldItem->{$field} ?? null;
            }
        }

        return $newItem;
    }

    /**
     * Fetch the current selling price for a product/variation.
     */
    private function fetchCurrentPrice(int $productId, ?int $variationId, string $customerType): float
    {
        if ($variationId) {
            $variation = Variation::find($variationId);
            if ($variation) {
                $priceField = match ($customerType) {
                    'dealer'    => 'dealer_price',
                    'special'   => 'special_price',
                    'ecommerce' => 'ecommerce_price',
                    default     => 'selling_price',
                };
                $price = $variation->{$priceField} ?? $variation->selling_price ?? $variation->default_sell_price ?? 0;
                if ($price > 0) {
                    return (float) $price;
                }
            }
        }

        $product = Product::find($productId);
        if ($product) {
            $priceField = match ($customerType) {
                'dealer'    => 'dealer_price',
                'special'   => 'special_price',
                'ecommerce' => 'ecommerce_price',
                default     => 'selling_price',
            };
            return (float) ($product->{$priceField} ?? $product->selling_price ?? $product->default_sell_price ?? 0);
        }

        throw new RuntimeException("Product #{$productId} not found. Cannot determine price.");
    }

    /**
     * Fill product snapshots for a newly added item by loading from the database.
     */
    private function fillProductSnapshots(array $newItem): array
    {
        $product   = Product::find($newItem['product_id']);
        $variation = isset($newItem['variation_id']) ? Variation::find($newItem['variation_id']) : null;

        if (!$product) {
            throw new RuntimeException("Product #{$newItem['product_id']} not found.");
        }

        $defaults = [
            'product_name_snapshot'                 => $product->name ?? $product->title ?? (string) $product->id,
            'variant_label_snapshot'                => $variation ? ($variation->label ?? $variation->sku ?? null) : null,
            'invoice_name_snapshot'                 => ($variation ? ($variation->invoice_name ?? null) : null)
                                                       ?? $product->name ?? $product->title,
            'image_snapshot'                        => $product->image ?? null,
            'customer_type_snapshot'                => $newItem['customer_type_snapshot'] ?? 'regular',
            'allow_discount'                        => (bool) ($product->allow_discount ?? true),
            'tax_type_snapshot'                     => $variation
                ? ($variation->tax_type ?? $product->tax_type ?? 'exempt')
                : ($product->tax_type ?? 'exempt'),
            'tax_rate_snapshot'                     => $variation
                ? (float) ($variation->tax_rate ?? $product->tax_rate ?? 0)
                : (float) ($product->tax_rate ?? 0),
            'tax_name'                              => $product->tax_name ?? null,
            'tax_category'                          => $product->tax_category ?? null,
            'hs_code'                               => $product->hs_code ?? null,
            'vendor_id'                             => $newItem['vendor_id'] ?? $product->user_id ?? null,
            'vendor_commission_type_snapshot'       => $product->vendor_commission_type ?? null,
            'vendor_commission_value_snapshot'      => (float) ($product->vendor_commission_value ?? 0),
            'salesperson_commission_type_snapshot'  => $product->salesperson_commission_type ?? null,
            'salesperson_commission_value_snapshot' => (float) ($product->salesperson_commission_value ?? 0),
            'is_combo'                              => (bool) ($product->is_combo ?? false),
            'track_batch'                           => $variation
                ? (bool) ($variation->track_batch ?? $product->track_batch ?? false)
                : (bool) ($product->track_batch ?? false),
            'line_discount'                         => 0,
            'line_discount_percent'                 => 0,
            'line_note'                             => null,
        ];

        foreach ($defaults as $field => $defaultValue) {
            if (!array_key_exists($field, $newItem)) {
                $newItem[$field] = $defaultValue;
            }
        }

        return $newItem;
    }

    // =========================================================================
    // Private – ledger
    // =========================================================================

    /**
     * Insert a delta PaymentLedger entry for the customer.
     *
     * - Positive delta → customer owes MORE (debit)
     * - Negative delta → customer owes LESS (credit / refund adjustment)
     */
    private function insertDeltaLedgerEntry(Sale $newSale, float $deltaAmount, array $data): void
    {
        $now       = now();
        $entryDate = $newSale->sale_date
            ? date('Y-m-d', strtotime($newSale->sale_date))
            : $now->toDateString();
        $currency  = $newSale->currency ?? 'BDT';
        $exchRate  = (float) ($newSale->exchange_rate ?? 1);
        $createdBy = $data['edited_by'] ?? auth()->id();

        $prevBalance = $this->saleRepository->getLatestLedgerBalance('customer', $newSale->customer_id);

        if ($deltaAmount > 0) {
            // Customer owes more — debit
            $newBalance = round($prevBalance + $deltaAmount, 2);
            $this->saleRepository->insertLedgerEntry([
                'party_type'      => 'customer',
                'party_id'        => $newSale->customer_id,
                'entry_date'      => $entryDate,
                'entry_at'        => $now,
                'entry_type'      => 'sale_edit',
                'reference_type'  => 'Sale',
                'reference_id'    => $newSale->id,
                'reference_no'    => $newSale->invoice_no,
                'debit'           => abs($deltaAmount),
                'credit'          => 0,
                'running_balance' => $newBalance,
                'currency'        => $currency,
                'exchange_rate'   => $exchRate,
                'description'     => "Sale edit adjustment (v{$newSale->version}) for {$newSale->invoice_no} — additional charge",
                'notes'           => $data['reason'] ?? null,
                'created_by'      => $createdBy,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        } else {
            // Customer owes less — credit
            $newBalance = round($prevBalance - abs($deltaAmount), 2);
            $this->saleRepository->insertLedgerEntry([
                'party_type'      => 'customer',
                'party_id'        => $newSale->customer_id,
                'entry_date'      => $entryDate,
                'entry_at'        => $now,
                'entry_type'      => 'sale_edit',
                'reference_type'  => 'Sale',
                'reference_id'    => $newSale->id,
                'reference_no'    => $newSale->invoice_no,
                'debit'           => 0,
                'credit'          => abs($deltaAmount),
                'running_balance' => $newBalance,
                'currency'        => $currency,
                'exchange_rate'   => $exchRate,
                'description'     => "Sale edit adjustment (v{$newSale->version}) for {$newSale->invoice_no} — reduction",
                'notes'           => $data['reason'] ?? null,
                'created_by'      => $createdBy,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }
    }

    /**
     * Determine the overall change_type label for the edit history.
     */
    private function determineChangeType(array $editItems): string
    {
        $types = array_unique(array_column($editItems, 'change_type'));

        if (count($types) === 1) {
            return match ($types[0]) {
                'added'       => 'items_added',
                'removed'     => 'items_removed',
                'qty_changed' => 'qty_changed',
                'unchanged'   => 'header_only',
                default       => 'mixed',
            };
        }

        return 'mixed';
    }
}
