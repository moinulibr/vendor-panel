<?php
// 2025_01_01_000006_create_purchase_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->comment('FK to purchase_orders');
            $table->foreignId('product_id')->comment('FK to products');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');

            $table->string('product_name_snapshot', 200)->nullable()->comment('Product name at order time');
            $table->string('variant_label_snapshot', 200)->nullable()->comment('Variant label snapshot');

            $table->decimal('unit_price', 12, 2)->comment('Negotiated price per unit');
            $table->integer('qty_ordered')->comment('Quantity ordered');
            $table->integer('qty_received')->default(0)->comment('Qty received so far (updated by GRN)');
            $table->decimal('discount', 12, 2)->default(0)->comment('Line-level discount');
            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax rate %');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Line tax amount');
            $table->decimal('line_total', 12, 2)->default(0)->comment('Final line amount');

            $table->enum('status', ['pending', 'partial', 'received', 'cancelled'])
                ->default('pending')
                ->comment('Line status');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['po_id', 'status']);
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
