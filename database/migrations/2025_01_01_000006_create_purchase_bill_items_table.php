<?php
// 2025_01_01_000006_create_purchase_bill_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('purchase_bills')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->unsignedBigInteger('batch_id')->nullable()->comment('FK to product_batches, set after batch creation');

            $table->string('product_name_snapshot', 200);
            $table->string('variant_label_snapshot', 100)->nullable();
            $table->decimal('unit_price_snapshot', 12, 2)->comment('Cost locked at purchase time');

            $table->integer('qty');
            $table->decimal('line_subtotal', 12, 2);
            $table->decimal('line_discount', 12, 2)->default(0);
            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->enum('tax_type_snapshot', ['exclusive', 'inclusive', 'exempt'])->default('exempt');
            $table->decimal('tax_rate_snapshot', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('allocated_inv_disc', 12, 2)->default(0);
            $table->decimal('allocated_freight', 12, 2)->default(0);
            $table->decimal('landed_cost', 12, 2)->default(0)->comment('unit_price + freight share');
            $table->decimal('line_total', 12, 2);

            $table->enum('status', ['active', 'removed', 'returned'])->default('active');
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
