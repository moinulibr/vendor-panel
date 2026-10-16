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
            $table->foreignId('sale_id')->comment('FK to sales table (denormalized for fast queries)');
            $table->foreignId('sale_item_id')->comment('FK to sale_items table');
            $table->foreignId('batch_id')->comment('FK to batches table');

            $table->integer('qty_used')->comment('Qty consumed from this batch (FIFO)');
            $table->decimal('unit_cost', 12, 2)->comment('Batch cost price per unit');
            $table->decimal('cost_total', 12, 2)->comment('qty_used × unit_cost = COGS');

            $table->decimal('unit_sale_price', 12, 2)->comment('Sale price per unit for this batch');
            $table->decimal('total_sale_amount', 12, 2)->comment('qty_used × unit_sale_price');

            $table->decimal('total_profit', 12, 2)->comment('total_sale_amount - cost_total');

            $table->timestamps();

            $table->index('sale_item_id');
            $table->index('batch_id');
            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_item_batches');
    }
};
