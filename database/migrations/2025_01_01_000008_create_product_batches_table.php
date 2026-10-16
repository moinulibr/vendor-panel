<?php
// 2025_01_01_000008_create_product_batches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no', 50)->comment('Auto or manual batch number (BAT-2025-0001)');

            $table->foreignId('product_id')->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');
            $table->foreignId('vendor_id')->nullable()->comment('FK to users — which vendor supplied');
            $table->foreignId('warehouse_id')->comment('FK to warehouses — where stock is stored');

            $table->unsignedBigInteger('purchase_bill_id')->nullable()->comment('FK to purchase_bills — source purchase');
            $table->unsignedBigInteger('purchase_item_id')->nullable()->comment('FK to purchase_bill_items — source line');
            $table->unsignedBigInteger('purchase_receipt_id')->nullable()->comment('FK to purchase_receipts — source GRN');
            $table->unsignedBigInteger('purchase_receipt_item_id')->nullable()->comment('FK to purchase_receipt_items — source GRN line');

            $table->date('mfg_date')->nullable()->comment('Manufacturing date');
            $table->date('expiry_date')->nullable()->comment('Expiry date — used for FIFO/FEFO');
            $table->string('supplier_batch_no', 50)->nullable()->comment('Vendor-side batch number');

            $table->integer('qty_received')->default(0)->comment('Total qty received in this batch');
            $table->integer('qty_available')->default(0)->comment('Remaining qty in stock');
            $table->integer('qty_reserved')->default(0)->comment('Qty reserved for pending orders');

            $table->decimal('cost_price', 12, 2)->default(0)->comment('Landed cost per unit for this batch');
            $table->decimal('selling_price_ref', 12, 2)->nullable()->comment('Reference selling price at receive time');
            $table->decimal('mrp', 12, 2)->nullable()->comment('Maximum Retail Price for this batch');

            $table->enum('status', ['active', 'expired', 'depleted', 'returned', 'quarantine'])
                ->default('active')
                ->comment('Batch lifecycle status');

            $table->string('serial_range', 100)->nullable()->comment('Serial numbers range if serial-tracked');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['product_id', 'variation_id', 'warehouse_id', 'batch_no'], 'product_batch_unique');
            $table->index(['product_id', 'warehouse_id', 'status']);
            $table->index('batch_no');
            $table->index('expiry_date');
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
