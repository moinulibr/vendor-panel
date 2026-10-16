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
            $table->foreignId('sale_item_id')->comment('FK to sale_items (original line)');
            $table->foreignId('batch_id')->nullable()->comment('FK to batches — returned to this batch');
            $table->foreignId('product_id')->comment('FK to products table');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations table');

            $table->decimal('unit_price_snapshot', 12, 2)->comment('Original sale price (locked)');
            $table->integer('qty_returned');
            $table->decimal('line_refund', 12, 2)->comment('unit_price × qty_returned');
            $table->decimal('line_disc_reversal', 12, 2)->default(0)->comment('Item discount reversal');
            $table->decimal('inv_disc_reversal', 12, 2)->default(0)->comment('Invoice discount proportional reversal');
            $table->decimal('tax_reversal', 12, 2)->default(0)->comment('VAT reversal');
            $table->decimal('net_refund', 12, 2)->comment('Final refund for this line');

            $table->boolean('restock')->default(true)->comment('Does this go back to sellable stock?');
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
