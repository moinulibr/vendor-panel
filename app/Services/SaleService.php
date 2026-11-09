<?php

namespace App\Services;

use App\Models\Sale;
use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Repositories\Sale\Interface\SaleRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleService
{
    public function __construct(
        protected BatchService $batchService,
        protected SaleRepositoryInterface $saleRepository,
        protected CartRepositoryInterface $cartRepository
    ) {}

    // =========================================================================
    // Core Methods
    // =========================================================================

    public function getUserSales(int $userId, array $filters)
    {
        return $this->saleRepository->getPaginatedSales($userId, $filters);
    }

    public function getSaleDetails(int $saleId, int $userId): Sale
    {
        $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);
        if (!$sale) {
            throw new Exception("Sale or Quotation not found.");
        }
        return $sale;
    }

    public function createSaleFromCart(int $userId, array $data): Sale
    {
        return DB::transaction(function () use ($userId, $data) {
            // 1. Fetch Cart
            $cart = $this->cartRepository->getSingleCartByCartAndUserId($data['cart_id'], $userId);
            if (!$cart || $cart->items()->count() === 0) {
                throw new Exception("Cart is empty or not found.");
            }

            // 2. Process Cart Items into Sale format
            $processedItems = $this->processCartItems($cart->items);

            // 3. Totals Calculation
            $subTotal = $processedItems['sub_total'];
            $itemTotalDiscount = $processedItems['item_total_discount'];
            $grossTotal = $subTotal - $itemTotalDiscount;

            $shippingCharge = (float) ($data['shipping_charge'] ?? 0);
            $taxAmount = $processedItems['tax_total'];

            $discountAmount = $this->resolveDiscountAmount(
                $data['discount_type'] ?? null,
                (float) ($data['discount_value'] ?? 0),
                $grossTotal
            );

            $totalInvoiceDiscount = $discountAmount;
            $processedItems['lines'] = $this->allocateInvoiceDiscount($processedItems['lines'], $totalInvoiceDiscount, $grossTotal);

            $finalAmount = round(($grossTotal - $totalInvoiceDiscount) + $shippingCharge + $taxAmount, 2);

            // 4. Payment Status logic
            $initialPayments = $data['payments'] ?? [];
            $paidAmount = collect($initialPayments)->sum('amount');
            $dueAmount = $finalAmount - $paidAmount;

            $isQuotation = (($data['status'] ?? 'confirmed') === 'quotation');

            $paymentStatus = $isQuotation ? 'unpaid' : ($paidAmount <= 0 ? 'unpaid' : ($paidAmount >= $finalAmount ? 'paid' : 'partially_paid'));
            $status = $isQuotation ? 'quotation' : 'confirmed';

            // 5. Create Sale Record
            $sale = $this->saleRepository->createSale([
                'parent_id' => null,
                'version' => 1,
                'is_current' => true,
                'invoice_no' => $this->generateInvoiceNo(),
                'channel' => $data['channel'] ?? 'mobile_dealer_app',
                'source_ref' => $data['source_ref'] ?? null,
                'customer_id' => $userId,
                'customer_type' => $data['customer_type'] ?? 'dealer',
                'warehouse_id' => $data['warehouse_id'],
                'sales_person_id' => $data['sales_person_id'] ?? null,
                'sub_total' => $subTotal,
                'item_total_discount' => $itemTotalDiscount,
                'gross_total' => $grossTotal,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'discount_amount' => $discountAmount,
                'shipping_charge' => $shippingCharge,
                'tax_amount' => $taxAmount,
                'final_amount' => $finalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'sale_date' => now(),
                'status' => $status,
                'payment_status' => $paymentStatus,
                'notes' => $data['note'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // 6. Insert Items & Batches
            foreach ($processedItems['lines'] as $line) {
                $saleItem = $this->saleRepository->createSaleItem(array_merge($line, [
                    'sale_id' => $sale->id,
                    'status' => 'active'
                ]));

                // ONLY Allocate Batches if NOT a quotation
                if (!$isQuotation && $line['track_batch']) {
                    $allocations = $this->batchService->allocateFIFO(
                        $line['product_id'],
                        $line['variation_id'],
                        $sale->warehouse_id,
                        $line['qty']
                    );
                    foreach ($allocations as $alloc) {
                        $this->saleRepository->createSaleItemBatch([
                            'sale_id' => $sale->id,
                            'sale_item_id' => $saleItem->id,
                            'batch_id' => $alloc['batch']->id,
                            'qty_used' => $alloc['qty_used'],
                            'unit_cost' => $alloc['unit_cost'],
                            'cost_total' => round($alloc['qty_used'] * $alloc['unit_cost'], 2),
                            'unit_sale_price' => $line['unit_price_snapshot'],
                            'total_sale_amount' => round($alloc['qty_used'] * $line['unit_price_snapshot'], 2),
                            'total_profit' => round(($alloc['qty_used'] * $line['unit_price_snapshot']) - ($alloc['qty_used'] * $alloc['unit_cost']), 2),
                        ]);
                    }
                }
            }

            // 7. Ledger & Payments (Only if not quotation)
            if (!$isQuotation) {
                $this->insertLedgerEntries($sale, $processedItems['lines']);

                $paymentCount = 0;
                $lastPaymentAt = null;
                foreach ($initialPayments as $payment) {
                    if ($payment['amount'] <= 0) continue;
                    $this->saleRepository->createSalePayment([
                        'payment_no' => $this->generatePaymentNo(),
                        'sale_id' => $sale->id,
                        'customer_id' => $userId,
                        'amount' => $payment['amount'],
                        'method' => $payment['method'],
                        'reference_no' => $payment['reference_no'] ?? null,
                        'status' => 'success',
                        'paid_at' => now(),
                        'received_by' => auth()->id(),
                    ]);
                    $paymentCount++;
                    $lastPaymentAt = now();
                }

                $this->saleRepository->createSalePaymentSummary([
                    'sale_id' => $sale->id,
                    'total_paid' => $paidAmount,
                    'total_refunded' => 0,
                    'net_paid' => $paidAmount,
                    'last_payment_at' => $lastPaymentAt,
                    'payment_count' => $paymentCount,
                ]);
            }

            // 8. Clear Cart
            $this->cartRepository->clearCart($cart->id);

            return $sale;
        });
    }

    public function confirmQuotation(int $saleId, int $userId): Sale
    {
        return DB::transaction(function () use ($saleId, $userId) {
            $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);

            if (!$sale || $sale->status !== 'quotation') {
                throw new Exception("Quotation not found or already confirmed.");
            }

            // ১. স্ট্যাটাস আপডেট
            $this->saleRepository->updateSale($sale, ['status' => 'confirmed']);

            // ২. ব্যাচ অ্যালোকেট করা
            foreach ($sale->items as $item) {
                if ($item->track_batch ?? false) {
                    $allocations = $this->batchService->allocateFIFO(
                        $item->product_id,
                        $item->variation_id,
                        $sale->warehouse_id,
                        $item->qty
                    );
                    foreach ($allocations as $alloc) {
                        $this->saleRepository->createSaleItemBatch([
                            'sale_id' => $sale->id,
                            'sale_item_id' => $item->id,
                            'batch_id' => $alloc['batch']->id,
                            'qty_used' => $alloc['qty_used'],
                            'unit_cost' => $alloc['unit_cost'],
                            'cost_total' => $alloc['qty_used'] * $alloc['unit_cost'],
                            'unit_sale_price' => $item->unit_price_snapshot,
                            'total_sale_amount' => $alloc['qty_used'] * $item->unit_price_snapshot,
                            'total_profit' => ($alloc['qty_used'] * $item->unit_price_snapshot) - ($alloc['qty_used'] * $alloc['unit_cost'])
                        ]);
                    }
                }
            }

            // ৩. লেজার এন্ট্রি
            $linesForLedger = $sale->items->map(fn($item) => [
                'vendor_id' => $item->vendor_id,
                'vendor_payable_amount' => $item->vendor_payable_amount
            ])->toArray();
            $this->insertLedgerEntries($sale, $linesForLedger);

            return $sale;
        });
    }

    public function submitPayment(int $saleId, int $userId, array $data): void
    {
        DB::transaction(function () use ($saleId, $userId, $data) {
            $sale = $this->saleRepository->findByIdAndUser($saleId, $userId);
            if (!$sale || $sale->status === 'quotation') {
                throw new Exception("Cannot submit payment for this sale.");
            }

            $this->saleRepository->createSalePayment([
                'payment_no' => $this->generatePaymentNo(),
                'sale_id' => $sale->id,
                'customer_id' => $userId,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'reference_no' => $data['reference_no'] ?? null,
                'status' => 'pending',
                'paid_at' => now(),
            ]);

            $newPaid = $sale->paid_amount + $data['amount'];
            $newDue = $sale->final_amount - $newPaid;
            $paymentStatus = $newDue <= 0 ? 'paid' : 'partially_paid';

            $this->saleRepository->updateSale($sale, [
                'paid_amount' => $newPaid,
                'due_amount' => max(0, $newDue),
                'payment_status' => $paymentStatus,
            ]);
        });
    }

    public function getSummaryReport(int $userId): array
    {
        $stats = $this->saleRepository->getSummaryStats($userId);
        return array_merge([
            'total_orders' => 0,
            'total_quotations' => 0,
            'total_order_amount' => 0,
            'total_paid_amount' => 0,
            'total_due_amount' => 0
        ], array_filter($stats));
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    private function processCartItems($cartItems): array
    {
        $lines = [];
        $subTotal = 0;
        $itemTotalDiscount = 0;
        $taxTotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $variation = $item->variation;

            $unitPrice = $item->unit_price;
            $qty = $item->quantity;
            $lineSubTotal = $qty * $unitPrice;

            $taxType = $variation->tax_type ?? $product->tax_type ?? 'exempt';
            $taxRate = $variation->tax_rate ?? $product->tax_rate ?? 0;

            $taxAmount = 0;
            if ($taxType === 'exclusive') {
                $taxAmount = round($lineSubTotal * $taxRate / 100, 2);
            } elseif ($taxType === 'inclusive') {
                $taxAmount = round($lineSubTotal - ($lineSubTotal / (1 + $taxRate / 100)), 2);
            }

            $vcType = $variation->vendor_commission_type ?? $product->vendor_commission_type;
            $vcValue = $variation->vendor_commission_value ?? $product->vendor_commission_value ?? 0;
            $vcAmount = ($vcType === 'percentage') ? round($lineSubTotal * $vcValue / 100, 2) : $vcValue;

            $lines[] = [
                'product_id' => $item->product_id,
                'variation_id' => $item->variation_id,
                'vendor_id' => $item->vendor_id ?? $product->vendor_id,
                'product_name_snapshot' => $product->name,
                'variant_label_snapshot' => $variation->name ?? null,
                'image_snapshot' => $variation->image ?? $product->image,
                'unit_price_snapshot' => $unitPrice,
                'qty' => $qty,
                'line_subtotal' => $lineSubTotal,
                'line_discount' => 0,
                'taxable_amount' => $lineSubTotal,
                'tax_type_snapshot' => $taxType,
                'tax_rate_snapshot' => $taxRate,
                'tax_amount' => $taxAmount,
                'line_total' => $lineSubTotal + ($taxType === 'exclusive' ? $taxAmount : 0),
                'vendor_commission_type_snapshot' => $vcType,
                'vendor_commission_value_snapshot' => $vcValue,
                'vendor_commission_amount' => $vcAmount,
                'vendor_payable_amount' => $lineSubTotal - $vcAmount,
                'track_batch' => $product->track_batch,
                'is_combo' => false,
            ];

            $subTotal += $lineSubTotal;
            $taxTotal += $taxAmount;
        }

        return ['lines' => $lines, 'sub_total' => $subTotal, 'item_total_discount' => $itemTotalDiscount, 'tax_total' => $taxTotal];
    }

    private function resolveDiscountAmount(?string $type, float $value, float $base): float
    {
        if ($value <= 0 || !$type) return 0.0;
        return $type === 'percentage' ? round($base * $value / 100, 2) : min($value, $base);
    }

    private function allocateInvoiceDiscount(array $lines, float $totalDiscount, float $grossTotal): array
    {
        if ($totalDiscount <= 0 || $grossTotal <= 0) return $lines;
        $allocated = 0;
        $lastIndex = count($lines) - 1;

        foreach ($lines as $i => &$line) {
            $share = ($i === $lastIndex) ? round($totalDiscount - $allocated, 2) : round(($line['line_subtotal'] / $grossTotal) * $totalDiscount, 2);
            $line['allocated_inv_disc'] = $share;
            $line['line_total'] = round($line['line_subtotal'] - $share + $line['tax_amount'], 2);
            $allocated += $share;
        }
        return $lines;
    }

    private function insertLedgerEntries(Sale $sale, array $lines): void
    {
        $now = now();
        $entryDate = $now->toDateString();

        if ($sale->customer_id) {
            $prev = $this->saleRepository->getLatestLedgerBalance('customer', $sale->customer_id);
            $this->saleRepository->insertLedgerEntry([
                'party_type' => 'customer',
                'party_id' => $sale->customer_id,
                'entry_date' => $entryDate,
                'entry_at' => $now,
                'entry_type' => 'sale',
                'reference_type' => 'Sale',
                'reference_id' => $sale->id,
                'reference_no' => $sale->invoice_no,
                'debit' => $sale->final_amount,
                'credit' => 0,
                'running_balance' => $prev + $sale->final_amount,
                'currency' => 'BDT',
                'exchange_rate' => 1,
                'description' => "Sale Invoice {$sale->invoice_no}",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $vendorTotals = [];
        foreach ($lines as $line) {
            if (isset($line['vendor_id']) && $line['vendor_id']) {
                $vendorTotals[$line['vendor_id']] = ($vendorTotals[$line['vendor_id']] ?? 0) + $line['vendor_payable_amount'];
            }
        }
        foreach ($vendorTotals as $vendorId => $payable) {
            if ($payable <= 0) continue;
            $prev = $this->saleRepository->getLatestLedgerBalance('vendor', $vendorId);
            $this->saleRepository->insertLedgerEntry([
                'party_type' => 'vendor',
                'party_id' => $vendorId,
                'entry_date' => $entryDate,
                'entry_at' => $now,
                'entry_type' => 'sale',
                'reference_type' => 'Sale',
                'reference_id' => $sale->id,
                'reference_no' => $sale->invoice_no,
                'debit' => 0,
                'credit' => $payable,
                'running_balance' => $prev + $payable,
                'currency' => 'BDT',
                'exchange_rate' => 1,
                'description' => "Vendor Payable {$sale->invoice_no}",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function generateInvoiceNo(): string
    {
        $prefix = "INV-" . now()->format('Y') . "-";
        $latest = $this->saleRepository->getLatestInvoiceNo($prefix);
        $next = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private function generatePaymentNo(): string
    {
        $prefix = "PAY-" . now()->format('Y') . "-";
        $latest = $this->saleRepository->getLatestPaymentNo($prefix);
        $next = $latest ? ((int) substr($latest, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
