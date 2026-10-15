<?php
// 2025_01_01_000026_create_sale_return_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->comment('FK to sale_returns table');
            $table->foreignId('sale_item_id')->comment('FK to sale_items table');
            $table->foreignId('batch_id')->nullable()->comment('FK to batches table');
            $table->foreignId('product_id')->comment('FK to products table');
            $table->foreignId('variation_id')->nullable()->comment('FK to variations table');

            $table->decimal('unit_price_snapshot', 12, 2);
            $table->integer('qty_returned');
            $table->decimal('line_refund', 12, 2);
            $table->decimal('line_disc_reversal', 12, 2)->default(0);
            $table->decimal('inv_disc_reversal', 12, 2)->default(0);
            $table->decimal('tax_reversal', 12, 2)->default(0);
            $table->decimal('net_refund', 12, 2);

            $table->boolean('restock')->default(true);
            $table->enum('condition', ['good', 'damaged', 'expired'])->default('good');
            $table->timestamps();

            $table->index('return_id');
            $table->index('sale_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_return_items');
    }
};
