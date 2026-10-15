<?php
// 2025_01_01_000018_create_sale_item_batches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_item_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('sale_item_id')->constrained('sale_items')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('product_batches')->restrictOnDelete();
            $table->integer('qty_used');
            $table->decimal('unit_cost', 12, 2)->comment('Batch cost price');
            $table->decimal('cost_total', 12, 2)->comment('qty_used × unit_cost = COGS for this allocation');
            $table->timestamps();

            $table->index(['sale_item_id']);
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_item_batches');
    }
};
