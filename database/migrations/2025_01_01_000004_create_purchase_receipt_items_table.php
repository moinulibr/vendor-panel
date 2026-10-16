<?php
// 2025_01_01_000008_create_purchase_receipt_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->comment('FK to purchase_receipts');
            $table->foreignId('po_item_id')->nullable()->comment('FK to purchase_order_items (null if direct receipt)');
            $table->foreignId('product_id')->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');

            $table->string('batch_no', 50)->comment('Batch number generated for this receipt');
            $table->date('mfg_date')->nullable()->comment('Manufacturing date');
            $table->date('expiry_date')->nullable()->comment('Expiry date');

            $table->integer('qty_received')->comment('Qty physically received');
            $table->integer('qty_rejected')->default(0)->comment('Qty rejected due to quality/damage');
            $table->string('rejection_reason', 200)->nullable()->comment('Why rejected');

            $table->decimal('unit_price', 12, 2)->comment('Unit cost from vendor');
            $table->decimal('line_total', 12, 2)->comment('qty_received × unit_price');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('receipt_id');
            $table->index('batch_no');
            $table->index(['product_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_receipt_items');
    }
};
