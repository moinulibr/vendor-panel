<?php
// 2025_01_01_000017_create_sale_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->comment('FK to sales table');
            $table->foreignId('product_id')->comment('FK to products table');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations table');
            $table->foreignId('vendor_id')->nullable()->comment('FK to users table (multi-vendor — which vendor supplied)');

            $table->string('product_name_snapshot', 200)->nullable()->comment('Product name at sale time');
            $table->string('variant_label_snapshot', 100)->nullable()->comment('Variant label at sale time (e.g. Red / M)');
            $table->string('invoice_name_snapshot', 100)->nullable()->comment('Name to display on invoice');
            $table->string('image_snapshot', 255)->nullable()->comment('Product image URL at sale time'); // FIXED: added length 255

            $table->decimal('unit_price_snapshot', 12, 2)->comment('Price locked at sale time');
            $table->enum('customer_type_snapshot', ['regular', 'dealer', 'special'])->default('regular')
                ->comment('Which price tier was applied at sale time');

            $table->integer('qty');
            $table->decimal('line_subtotal', 12, 2)->comment('qty × unit_price_snapshot');

            $table->boolean('allow_discount')->default(true)->comment('Snapshot of product.allow_discount');
            $table->decimal('line_discount', 12, 2)->default(0)->comment('Item-level discount in currency');
            $table->decimal('line_discount_percent', 5, 2)->default(0)->comment('Item-level discount percentage');

            $table->decimal('taxable_amount', 12, 2)->default(0)->comment('Base amount for VAT calculation');
            $table->enum('tax_type_snapshot', ['exclusive', 'inclusive', 'exempt'])->default('exempt')
                ->comment('Tax behavior at sale time');
            $table->decimal('tax_rate_snapshot', 5, 2)->default(0)->comment('VAT rate locked at sale time');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Line-level VAT');

            $table->decimal('allocated_inv_disc', 12, 2)->default(0)->comment('Proportional share of invoice-level discount');
            $table->decimal('line_total', 12, 2)->comment('subtotal - line_disc - alloc_inv_disc + tax');

            $table->boolean('is_combo')->default(false)->comment('Is this a combo/package product?');
            $table->enum('status', ['active', 'removed', 'returned', 'partially_returned'])->default('active');
            $table->string('line_note', 200)->nullable()->comment('e.g. "original", "added today"');
            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index(['product_id', 'created_at']);
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
