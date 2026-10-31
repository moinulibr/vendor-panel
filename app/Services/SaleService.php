<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemBatch;
use App\Models\SalePayment;
use App\Models\SalePaymentSummary;
use App\Models\SaleTax;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleService
{
    public function __construct(
        protected BatchService $batchService
    ) {}

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Create a new sale inside a single DB transaction.
     *
     * Expected $data shape:
     * [
     *   // Sale header
     *   'channel'              => 'admin_pos'|'web_ecommerce'|'mobile_dealer_app'|'sr',
     *   'customer_id'          => int|null,
     *   'customer_type'        => 'regular'|'dealer'|'special'|'ecommerce',
     *   'warehouse_id'         => int,
     *   'sales_person_id'      => int|null,
     *   'sale_date'            => string|null,       // Y-m-d H:i:s, default = now()
     *   'source_ref'           => string|null,
     *   'shipping_charge'      => float,             // default 0
     *   'round_off'            => float,             // default 0
     *   'discount_type'        => 'percentage'|'fixed'|null,
     *   'discount_value'       => float,             // default 0
     *   'coupon_id'            => int|null,
     *   'coupon_discount_type' => 'percentage'|'fixed'|null,
     *   'coupon_discount_value'=> float,             // default 0
     *   'currency'             => string,            // default 'BDT'
     *   'exchange_rate'        => float,             // default 1
     *   'notes'                => string|null,
     *   'quote_valid_until'    => string|null,
     *   'quote_notes'          => string|null,
     *   'status'               => string|null,       // override lifecycle status if needed
     *   'created_by'           => int|null,
     *
     *   // Items array — each element:
     *   'items' => [
     *     [
     *       'product_id'                        => int,
     *       'variation_id'                      => int|null,
     *       'vendor_id'                         => int|null,
     *       'qty'                               => int,
     *       // Snapshots (pass from product/variation at call time)
     *       'product_name_snapshot'             => string,
     *       'variant_label_snapshot'            => string|null,
     *       'invoice_name_snapshot'             => string|null,
     *       'image_snapshot'                    => string|null,
     *       'unit_price_snapshot'               => float,
     *       'customer_type_snapshot'            => string,
     *       'allow_discount'                    => bool,
     *       'tax_type_snapshot'                 => 'exclusive'|'inclusive'|'exempt',
     *       'tax_rate_snapshot'                 => float,           // %
     *       'tax_name'                          => string|null,
     *       'tax_category'                      => string|null,
     *       'hs_code'                           => string|null,
     *       'line_discount'                     => float,           // item-level disc in currency
     *       'line_discount_percent'             => float,           // item-level disc %
     *       'vendor_commission_type_snapshot'   => 'percentage'|'fixed'|null,
     *       'vendor_commission_value_snapshot'  => float,
     *       'salesperson_commission_type_snapshot'  => 'percentage'|'fixed'|null,
     *       'salesperson_commission_value_snapshot' => float,
     *       'is_combo'                          => bool,
     *       'line_note'                         => string|null,
     *       // Batch tracking (if product tracks batches)
     *       'track_batch'                       => bool,
     *     ],
     *     ...
     *   ],
     *
     *   // Optional payments (initial payment at time of sale)
     *   'payments' => [
     *     [
     *       'amount'       => float,
     *       'method'       => 'cash'|'card'|'bkash'|...,
     *       'reference_no' => string|null,
     *       'paid_at'      => string|null,
     *       'received_by'  => int|null,
     *     ],
     *     ...
     *   ],
     * ]
     *
     * @throws RuntimeException On stock shortage or other domain violations.
     */
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {

            // ------------------------------------------------------------------
            // 1. Generate invoice_no
            // ------------------------------------------------------------------
            $invoiceNo = $this->generateInvoiceNo();

            // ------------------------------------------------------------------
            // 2. Calculate per-line figures
            // ------------------------------------------------------------------
            $processedItems = $this->processItems($data['items']);

            // ------------------------------------------------------------------
            // 3. Calculate invoice-level totals
            // ------------------------------------------------------------------
            $subTotal          = $processedItems['sub_total'];         // Sum of line_subtotals
            $itemTotalDiscount = $processedItems['item_total_discount'];// Sum of line_discounts
            $grossTotal        = $subTotal - $itemTotalDiscount;       // Before invoice disc & tax

            $shippingCharge    = (float) ($data['shipping_charge'] ?? 0);
            $roundOff          = (float) ($data['round_off'] ?? 0);

            // Invoice-level discount
            $discountType   = $data['discount_type'] ?? null;
            $discountValue  = (float) ($data['discount_value'] ?? 0);
            $discountAmount = $this->resolveDiscountAmount($discountType, $discountValue, $grossTotal);

            // Coupon discount
            $couponDiscountType   = $data['coupon_discount_type'] ?? null;
            $couponDiscountValue  = (float) ($data['coupon_discount_value'] ?? 0);
            $couponDiscountAmount = $this->resolveDiscountAmount(
                $couponDiscountType,
                $couponDiscountValue,
                $grossTotal - $discountAmount
            );

            $totalInvoiceDiscount = $discountAmount + $couponDiscountAmount;

            // Allocate invoice-level discount proportionally to items
            $processedItems['lines'] = $this->allocateInvoiceDiscount(
                $processedItems['lines'],
                $totalInvoiceDiscount,
                $grossTotal
            );

            // Tax is already computed per line (exclusive adds on top, inclusive is already in price)
            $taxAmount  = $processedItems['tax_total'];

            $finalAmount = round(
                $grossTotal - $totalInvoiceDiscount + $shippingCharge + $taxAmount + $roundOff,
                2
            );

            // ------------------------------------------------------------------
            // 4. Determine payment status from initial payments
            // ------------------------------------------------------------------
            $initialPayments = $data['payments'] ?? [];
            $paidAmount = (float) collect($initialPayments)->sum('amount');
            $paidAmount = round($paidAmount, 2);
            $dueAmount  = round($finalAmount - $paidAmount, 2);

            [$status, $paymentStatus] = $this->resolveStatuses(
                $finalAmount,
                $paidAmount,
                $data['status'] ?? null,
                $data['coupon_id'] ?? null
            );

            // ------------------------------------------------------------------
            // 5. Insert Sale row
            // ------------------------------------------------------------------
            $sale = Sale::create([
                'parent_id'                 => null,
                'version'                   => 1,
                'is_current'                => true,
                'invoice_no'                => $invoiceNo,
                'channel'                   => $data['channel'] ?? 'admin_pos',
                'source_ref'                => $data['source_ref'] ?? null,
                'customer_id'               => $data['customer_id'] ?? null,
                'customer_type'             => $data['customer_type'] ?? 'regular',
                'warehouse_id'              => $data['warehouse_id'] ?? null,
                'sales_person_id'           => $data['sales_person_id'] ?? null,
                'sub_total'                 => $subTotal,
                'item_total_discount'       => $itemTotalDiscount,
                'gross_total'               => $grossTotal,
                'discount_type'             => $discountType,
                'discount_value'            => $discountValue,
                'discount_amount'           => $discountAmount,
                'coupon_id'                 => $data['coupon_id'] ?? null,
                'coupon_discount_type'      => $couponDiscountType,
                'coupon_discount_value'     => $couponDiscountValue,
                'coupon_discount_amount'    => $couponDiscountAmount,
                'shipping_charge'           => $shippingCharge,
                'tax_amount'                => $taxAmount,
                'round_off'                 => $roundOff,
                'final_amount'              => $finalAmount,
                'paid_amount'               => $paidAmount,
                'due_amount'                => $dueAmount,
                'sale_date'                 => $data['sale_date'] ?? now(),
                'status'                    => $status,
                'payment_status'            => $paymentStatus,
                'shipping_status'           => 'pending',
                'quote_valid_until'         => $data['quote_valid_until'] ?? null,
                'quote_notes'               => $data['quote_notes'] ?? null,
                'currency'                  => $data['currency'] ?? 'BDT',
                'exchange_rate'             => $data['exchange_rate'] ?? 1,
                'notes'                     => $data['notes'] ?? null,
                'is_editable_for_admin'     => true,
                'is_editable_for_staff'     => true,
                'is_editable_for_customer'  => true,
                'created_by'                => $data['created_by'] ?? null,
            ]);

            // ------------------------------------------------------------------
            // 6. Insert SaleItems + SaleItemBatches + SaleTaxes
            // ------------------------------------------------------------------
            foreach ($processedItems['lines'] as $line) {
                $saleItem = SaleItem::create([
                    'sale_id'                               => $sale->id,
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

                // Batch allocation (FIFO)
                if (!empty($line['track_batch']) && $data['warehouse_id']) {
                    $allocations = $this->batchService->allocateFIFO(
                        $line['product_id'],
                        $line['variation_id'],
                        $data['warehouse_id'],
                        $line['qty']
                    );

                    foreach ($allocations as $alloc) {
                        /** @var \App\Models\ProductBatch $batch */
                        $batch         = $alloc['batch'];
                        $qtyUsed       = $alloc['qty_used'];
                        $unitCost      = $alloc['unit_cost'];
                        $unitSalePrice = (float) $line['unit_price_snapshot'];
                        $totalSale     = round($qtyUsed * $unitSalePrice, 2);
                        $costTotal     = round($qtyUsed * $unitCost, 2);

                        SaleItemBatch::create([
                            'sale_id'          => $sale->id,
                            'sale_item_id'     => $saleItem->id,
                            'batch_id'         => $batch->id,
                            'qty_used'         => $qtyUsed,
                            'unit_cost'        => $unitCost,
                            'cost_total'       => $costTotal,
                            'unit_sale_price'  => $unitSalePrice,
                            'total_sale_amount'=> $totalSale,
                            'total_profit'     => round($totalSale - $costTotal, 2),
                        ]);
                    }
                }

                // Per-item tax row (when tax is not exempt)
                if ($line['tax_type_snapshot'] !== 'exempt' && $line['tax_amount'] > 0) {
                    SaleTax::create([
                        'sale_id'       => $sale->id,
                        'sale_item_id'  => $saleItem->id,
                        'tax_name'      => $line['tax_name'] ?? null,
                        'tax_category'  => $line['tax_category'] ?? null,
                        'hs_code'       => $line['hs_code'] ?? null,
                        'tax_rate'      => $line['tax_rate_snapshot'],
                        'taxable_amount'=> $line['taxable_amount'],
                        'tax_amount'    => $line['tax_amount'],
                    ]);
                }
            }

            // ------------------------------------------------------------------
            // 7. Insert initial payments + SalePaymentSummary
            // ------------------------------------------------------------------
            $paymentCount   = 0;
            $lastPaymentAt  = null;

            foreach ($initialPayments as $payment) {
                $amount = (float) $payment['amount'];
                if ($amount <= 0) {
                    continue;
                }

                $paidAt = isset($payment['paid_at']) ? $payment['paid_at'] : now();

                SalePayment::create([
                    'payment_no'          => $this->generatePaymentNo(),
                    'sale_id'             => $sale->id,
                    'customer_id'         => $data['customer_id'] ?? null,
                    'amount'              => $amount,
                    'method'              => $payment['method'],
                    'reference_no'        => $payment['reference_no'] ?? null,
                    'status'              => 'success',
                    'paid_at'             => $paidAt,
                    'received_by'         => $payment['received_by'] ?? ($data['created_by'] ?? null),
                    'register_session_id' => $payment['register_session_id'] ?? null,
                    'notes'               => $payment['notes'] ?? null,
                ]);

                $paymentCount++;
                $lastPaymentAt = $paidAt;
            }

            // Always create a payment summary row for the sale.
            SalePaymentSummary::create([
                'sale_id'         => $sale->id,
                'total_paid'      => $paidAmount,
                'total_refunded'  => 0,
                'net_paid'        => $paidAmount,
                'last_payment_at' => $lastPaymentAt,
                'payment_count'   => $paymentCount,
            ]);

            // ------------------------------------------------------------------
            // 8. Insert PaymentLedger entries
            // ------------------------------------------------------------------
            $this->insertLedgerEntries($sale, $processedItems['lines'], $invoiceNo, $data);

            return $sale->fresh();
        });
    }

    // =========================================================================
    // Private – calculations
    // =========================================================================

    /**
     * Process all items: compute per-line financial figures.
     *
     * Returns:
     * [
     *   'lines'              => array,   // mutated item data with computed fields
     *   'sub_total'          => float,
     *   'item_total_discount'=> float,
     *   'tax_total'          => float,
     * ]
     */
    private function processItems(array $items): array
    {
        $lines             = [];
        $subTotal          = 0.0;
        $itemTotalDiscount = 0.0;
        $taxTotal          = 0.0;

        foreach ($items as $item) {
            $qty               = (int) $item['qty'];
            $unitPrice         = (float) $item['unit_price_snapshot'];
            $lineSubtotal      = round($qty * $unitPrice, 2);

            // Item-level discount
            $allowDiscount     = (bool) ($item['allow_discount'] ?? true);
            $lineDiscount      = $allowDiscount ? round((float) ($item['line_discount'] ?? 0), 2) : 0.0;
            $lineDiscPercent   = $allowDiscount ? round((float) ($item['line_discount_percent'] ?? 0), 2) : 0.0;

            // Taxable base
            $taxType           = $item['tax_type_snapshot'] ?? 'exempt';
            $taxRate           = (float) ($item['tax_rate_snapshot'] ?? 0);

            [$taxableAmount, $taxAmount] = $this->computeLineTax(
                $lineSubtotal,
                $lineDiscount,
                $taxType,
                $taxRate
            );

            // Vendor commission
            $vcType   = $item['vendor_commission_type_snapshot'] ?? null;
            $vcValue  = (float) ($item['vendor_commission_value_snapshot'] ?? 0);
            $vcAmount = $this->resolveCommissionAmount($vcType, $vcValue, $lineSubtotal - $lineDiscount);
            $vendorPayable = round(($lineSubtotal - $lineDiscount) - $vcAmount, 2);

            // Salesperson commission
            $spType   = $item['salesperson_commission_type_snapshot'] ?? null;
            $spValue  = (float) ($item['salesperson_commission_value_snapshot'] ?? 0);
            $spAmount = $this->resolveCommissionAmount($spType, $spValue, $lineSubtotal - $lineDiscount);

            // line_total = subtotal - line_disc + tax (invoice disc allocated later)
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
                'allocated_inv_disc'                    => 0.0, // filled later
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
     * Calculate taxable_amount and tax_amount for a single line.
     *
     * - exclusive : tax is added on top of (subtotal - lineDiscount)
     * - inclusive : tax is embedded inside the price; extract it
     * - exempt    : no tax
     *
     * Returns [taxableAmount, taxAmount].
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
                // taxable_amount is the base excluding tax
                round($base / (1 + $taxRate / 100), 2),
                round($base - ($base / (1 + $taxRate / 100)), 2),
            ],
            default => [round($base, 2), 0.0], // exempt
        };
    }

    /**
     * Allocate the total invoice-level discount proportionally across lines
     * by each line's (sub_total - line_discount) weight in the gross_total.
     *
     * The last line absorbs any rounding residual.
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
                // Last line absorbs residual to avoid rounding drift
                $share = round($totalDiscount - $allocated, 2);
            } else {
                $lineBase = $line['line_subtotal'] - $line['line_discount'];
                $share    = round(($lineBase / $grossTotal) * $totalDiscount, 2);
            }

            $line['allocated_inv_disc'] = $share;
            // Recompute line_total to include allocated invoice discount
            $line['line_total'] = round(
                $line['line_subtotal'] - $line['line_discount'] - $share + $line['tax_amount'],
                2
            );
            $allocated += $share;
        }
        unset($line);

        return $lines;
    }

    /**
     * Calculate a discount amount from type + value + base.
     */
    private function resolveDiscountAmount(?string $type, float $value, float $base): float
    {
        if ($value <= 0 || !$type) {
            return 0.0;
        }

        return match ($type) {
            'percentage' => round($base * $value / 100, 2),
            'fixed'      => round(min($value, $base), 2), // cannot exceed base
            default      => 0.0,
        };
    }

    /**
     * Calculate a commission amount from type + value + base.
     */
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

    /**
     * Derive lifecycle status and payment_status.
     *
     * If caller explicitly passes a status override (e.g. 'quotation'), it is used
     * for the lifecycle status only; payment_status is still derived from amounts.
     */
    private function resolveStatuses(
        float $finalAmount,
        float $paidAmount,
        ?string $statusOverride,
        ?int $couponId
    ): array {
        // Payment status
        if ($finalAmount <= 0) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount <= 0) {
            $paymentStatus = 'unpaid';
        } elseif ($paidAmount >= $finalAmount) {
            $paymentStatus = 'paid';
        } else {
            $paymentStatus = 'partially_paid';
        }

        // Lifecycle status
        if ($statusOverride) {
            $status = $statusOverride;
        } elseif ($finalAmount <= 0) {
            $status = 'confirmed';
        } elseif ($paidAmount >= $finalAmount) {
            $status = 'confirmed';
        } else {
            $status = 'pending';
        }

        return [$status, $paymentStatus];
    }

    // =========================================================================
    // Private – ledger
    // =========================================================================

    /**
     * Write PaymentLedger entries:
     * - Customer debit for the full invoice amount (money owed by customer)
     * - Per-vendor credit for vendor_payable_amount (money we owe each vendor)
     */
    private function insertLedgerEntries(Sale $sale, array $lines, string $invoiceNo, array $data): void
    {
        $now      = now();
        $entryDate = $data['sale_date']
            ? date('Y-m-d', strtotime($data['sale_date']))
            : $now->toDateString();
        $currency    = $data['currency'] ?? 'BDT';
        $exchRate    = (float) ($data['exchange_rate'] ?? 1);
        $createdBy   = $data['created_by'] ?? null;

        // --- Customer debit (sale) ---
        if ($sale->customer_id) {
            $prevBalance = $this->getRunningBalance('customer', $sale->customer_id);
            $newBalance  = round($prevBalance + $sale->final_amount, 2);

            DB::table('payment_ledger')->insert([
                'party_type'     => 'customer',
                'party_id'       => $sale->customer_id,
                'entry_date'     => $entryDate,
                'entry_at'       => $now,
                'entry_type'     => 'sale',
                'reference_type' => 'Sale',
                'reference_id'   => $sale->id,
                'reference_no'   => $invoiceNo,
                'debit'          => $sale->final_amount,
                'credit'         => 0,
                'running_balance'=> $newBalance,
                'currency'       => $currency,
                'exchange_rate'  => $exchRate,
                'description'    => "Sale invoice {$invoiceNo}",
                'notes'          => null,
                'created_by'     => $createdBy,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // --- Vendor credit (per vendor aggregated) ---
        $vendorTotals = [];
        foreach ($lines as $line) {
            $vendorId = $line['vendor_id'] ?? null;
            if (!$vendorId) {
                continue;
            }
            $vendorTotals[$vendorId] = ($vendorTotals[$vendorId] ?? 0.0)
                + (float) $line['vendor_payable_amount'];
        }

        foreach ($vendorTotals as $vendorId => $payable) {
            if ($payable <= 0) {
                continue;
            }
            $prevBalance = $this->getRunningBalance('vendor', $vendorId);
            $newBalance  = round($prevBalance + $payable, 2);

            DB::table('payment_ledger')->insert([
                'party_type'     => 'vendor',
                'party_id'       => $vendorId,
                'entry_date'     => $entryDate,
                'entry_at'       => $now,
                'entry_type'     => 'sale',
                'reference_type' => 'Sale',
                'reference_id'   => $sale->id,
                'reference_no'   => $invoiceNo,
                'debit'          => 0,
                'credit'         => round($payable, 2),
                'running_balance'=> $newBalance,
                'currency'       => $currency,
                'exchange_rate'  => $exchRate,
                'description'    => "Vendor payable for sale {$invoiceNo}",
                'notes'          => null,
                'created_by'     => $createdBy,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }

    /**
     * Get the most-recent running_balance for a party (for appending).
     */
    private function getRunningBalance(string $partyType, int $partyId): float
    {
        return (float) DB::table('payment_ledger')
            ->where('party_type', $partyType)
            ->where('party_id', $partyId)
            ->orderBy('id', 'desc')
            ->value('running_balance') ?? 0.0;
    }

    // =========================================================================
    // Private – number generation
    // =========================================================================

    /**
     * Generate an invoice number: INV-YYYY-NNNN.
     * Uses a DB lock to be safe under concurrent requests.
     */
    private function generateInvoiceNo(): string
    {
        $year   = now()->format('Y');
        $prefix = "INV-{$year}-";

        $latest = Sale::where('invoice_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('invoice_no');

        $next = 1;
        if ($latest) {
            $seq  = (int) substr($latest, strlen($prefix));
            $next = $seq + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a payment reference number: PAY-YYYY-NNNN.
     */
    private function generatePaymentNo(): string
    {
        $year   = now()->format('Y');
        $prefix = "PAY-{$year}-";

        $latest = SalePayment::where('payment_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('payment_no');

        $next = 1;
        if ($latest) {
            $seq  = (int) substr($latest, strlen($prefix));
            $next = $seq + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
