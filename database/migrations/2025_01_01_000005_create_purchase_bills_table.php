<?php
// 2025_01_01_000009_create_purchase_bills_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Original bill id when this is an edited version');
            $table->integer('version')->default(1)->comment('Version number (1, 2, 3...)');
            $table->boolean('is_current')->default(true)->comment('1 = active version, 0 = history');

            $table->string('bill_no', 50)->unique()->comment('Internal bill number (PB-2025-0001)');
            $table->string('vendor_bill_no', 50)->nullable()->comment('Vendor-side invoice number');
            $table->foreignId('po_id')->nullable()->comment('FK to purchase_orders');
            $table->foreignId('receipt_id')->nullable()->comment('FK to purchase_receipts');
            $table->foreignId('vendor_id')->comment('FK to vendors');
            $table->foreignId('warehouse_id')->comment('FK to warehouses');

            $table->date('bill_date')->comment('Bill date from vendor');
            $table->date('due_date')->nullable()->comment('Payment due date');

            $table->enum('status', ['draft', 'confirmed', 'partially_paid', 'paid', 'returned', 'cancelled'])
                ->default('draft')
                ->comment('Bill lifecycle status');

            $table->decimal('sub_total', 12, 2)->default(0)->comment('Sum of line subtotals');
            $table->decimal('item_total_disc', 12, 2)->default(0)->comment('Sum of line-level discounts');
            $table->decimal('gross_total', 12, 2)->default(0)->comment('sub_total - item_total_disc');

            $table->enum('discount_type', ['percentage', 'fixed'])->nullable()->comment('Bill-level discount type');
            $table->decimal('discount_value', 12, 2)->default(0)->comment('Input value (e.g. 3 for 3%)');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Actual discount in currency');

            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Total VAT');
            $table->decimal('freight', 12, 2)->default(0)->comment('Freight charge');
            $table->decimal('landing_cost', 12, 2)->default(0)->comment('Freight + duty allocated to products');
            $table->decimal('round_off', 5, 2)->default(0)->comment('Round-off adjustment');

            $table->decimal('total_amount', 12, 2)->default(0)->comment('Final bill amount');
            $table->decimal('paid_amount', 12, 2)->default(0)->comment('Cache: sum of successful vendor_payments');
            $table->decimal('due_amount', 12, 2)->default(0)->comment('total_amount - paid_amount');

            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->comment('FK to users');
            $table->timestamps();

            $table->index(['vendor_id', 'is_current', 'status']);
            $table->index(['parent_id', 'version']);
            $table->index('bill_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bills');
    }
};
