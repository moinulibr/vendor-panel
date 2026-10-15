<?php
// 2025_01_01_000014_create_purchase_return_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('purchase_returns')->cascadeOnDelete();
            $table->foreignId('bill_item_id')->constrained('purchase_bill_items')->restrictOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->decimal('unit_price_snapshot', 12, 2);
            $table->integer('qty_returned');
            $table->decimal('line_refund', 12, 2);
            $table->decimal('inv_disc_rev', 12, 2)->default(0);
            $table->decimal('tax_rev', 12, 2)->default(0);
            $table->decimal('net_refund', 12, 2);
            $table->string('reason', 200)->nullable();
            $table->timestamps();

            $table->index('return_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
