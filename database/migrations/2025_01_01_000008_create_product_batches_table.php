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
            $table->string('batch_no', 50);
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('purchase_bill_id')->nullable()->constrained('purchase_bills')->nullOnDelete();
            $table->foreignId('purchase_item_id')->nullable()->constrained('purchase_bill_items')->nullOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();

            $table->date('mfg_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('qty_received');
            $table->integer('qty_available');
            $table->decimal('cost_price', 12, 2)->comment('Landed cost per unit for this batch');
            $table->decimal('selling_price_ref', 12, 2)->nullable();
            $table->enum('status', ['active', 'expired', 'depleted', 'returned'])->default('active');
            $table->timestamps();

            $table->unique(['product_id', 'variant_id', 'warehouse_id', 'batch_no'], 'batch_unique');
            $table->index(['product_id', 'status', 'expiry_date']);
            $table->index('batch_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
