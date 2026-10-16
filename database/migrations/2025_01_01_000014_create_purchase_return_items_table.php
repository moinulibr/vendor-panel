<?php
// 2025_01_01_000017_create_purchase_return_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->comment('FK to purchase_returns');
            $table->foreignId('bill_item_id')->comment('FK to purchase_bill_items (original line)');
            $table->foreignId('batch_id')->nullable()->comment('FK to batches');
            $table->foreignId('product_id')->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');

            $table->decimal('unit_price_snapshot', 12, 2)->comment('Original purchase price (locked)');
            $table->integer('qty_returned')->comment('Quantity returned');
            $table->decimal('line_refund', 12, 2)->comment('unit_price × qty_returned');
            $table->decimal('inv_disc_rev', 12, 2)->default(0)->comment('Bill discount reversal for this line');
            $table->decimal('tax_rev', 12, 2)->default(0)->comment('Tax reversal');
            $table->decimal('net_refund', 12, 2)->comment('Final refund for this line');

            $table->string('reason', 200)->nullable()->comment('defective/expired/wrong_item/etc');
            $table->timestamps();

            $table->index('return_id');
            $table->index('bill_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
