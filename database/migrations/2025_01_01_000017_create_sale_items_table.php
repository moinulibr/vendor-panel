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
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('vendor_id')->nullable()->comment('FK to users tabel. Multi-vendor: which vendor supplied this');

            $table->string('product_name_snapshot', 200)->nullable()->comment('Locked at sale time');
            $table->string('variant_label_snapshot', 100)->nullable()->comment('Locked at sale time');
            $table->string('invoice_name_snapshot', 100)->nullable()->comment('Locked at sale time');
            $table->string('image_snapshot')->nullable()->comment('Locked at sale time');
            $table->decimal('unit_price_snapshot', 12, 2)->comment('Locked at sale time');
            $table->enum('customer_type_snapshot', ['regular', 'dealer','special'])->default('regular')
                ->comment('Which price tier was applied');

            $table->integer('qty');
            $table->decimal('line_subtotal', 12, 2);
            $table->boolean('allow_discount')->default(true);
            $table->decimal('line_discount', 12, 2)->default(0);
            $table->decimal('line_discount_percent', 5, 2)->default(0);

            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->enum('tax_type_snapshot', ['exclusive', 'inclusive', 'exempt'])->default('exempt');
            $table->decimal('tax_rate_snapshot', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);

            $table->decimal('allocated_inv_disc', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);

            $table->boolean('is_combo')->default(false);
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
