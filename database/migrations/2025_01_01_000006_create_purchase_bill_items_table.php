<?php
// 2025_01_01_000010_create_purchase_bill_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->comment('FK to purchase_bills');
            $table->foreignId('product_id')->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');
            $table->unsignedBigInteger('batch_id')->nullable()->comment('FK to batches — batch created for this line');

            $table->string('product_name_snapshot', 200)->comment('Product name at purchase time');
            $table->string('variant_label_snapshot', 100)->nullable()->comment('Variant label snapshot');
            $table->decimal('unit_price_snapshot', 12, 2)->comment('Cost price locked at purchase time');

            $table->integer('qty')->comment('Quantity purchased');
            $table->decimal('line_subtotal', 12, 2)->comment('qty × unit_price_snapshot');
            $table->decimal('line_discount', 12, 2)->default(0)->comment('Item-level discount');

            $table->decimal('taxable_amount', 12, 2)->default(0)->comment('Base amount for VAT calculation');
            $table->enum('tax_type_snapshot', ['exclusive', 'inclusive', 'exempt'])->default('exempt')
                ->comment('Tax type at purchase time');
            $table->decimal('tax_rate_snapshot', 5, 2)->default(0)->comment('Tax rate at purchase time');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Line-level VAT');

            $table->decimal('allocated_inv_disc', 12, 2)->default(0)->comment('Share of bill-level discount');
            $table->decimal('allocated_freight', 12, 2)->default(0)->comment('Share of freight charge');
            $table->decimal('landed_cost', 12, 2)->default(0)->comment('unit_price + allocated_freight + allocated_tax');
            $table->decimal('line_total', 12, 2)->comment('Final line amount');

            $table->enum('status', ['active', 'removed', 'returned', 'partially_returned'])->default('active');
            $table->string('line_note', 200)->nullable()->comment('e.g. "original", "added today"');
            $table->timestamps();

            $table->index(['bill_id', 'status']);
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bill_items');
    }
};
