<?php
// 2025_XX_XX_XX_create_cart_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cart_items')) return;

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->comment('FK to carts');
            $table->foreignId('product_id')->nullable()->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to variations');

            // ✅ NEW: Multi-vendor + display snapshot
            $table->foreignId('vendor_id')->nullable()
                ->comment('Which vendor supplies this item (multi-vendor)');

            $table->string('product_label_snapshot', 200)->nullable()
                ->comment('Product label (e.g. T-shirt) for UI display');
            $table->string('variant_label_snapshot', 200)->nullable()
                ->comment('Variant label (e.g. Red / M) for UI display');

            $table->string('type', 20)->nullable()->comment('single|variable');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 4)->default(0)
                ->comment('Price at cart time (may differ from final sale price)');

            // ✅ NEW: Line totals (used by CartRepository::addOrUpdateItem)
            $table->decimal('sub_total', 15, 4)->default(0)
                ->comment('quantity × unit_price');
            $table->decimal('final_amount', 15, 4)->default(0)
                ->comment('sub_total - discount_amount');

            // Item-specific discount
            $table->decimal('discount_amount', 15, 4)->default(0);
            $table->string('discount_type', 20)->nullable()->comment('fixed|percentage');
            $table->foreignId('discount_id')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'variation_id'], 'cart_product_variation_unique');
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
