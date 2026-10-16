<?php
// 2025_01_01_000003_create_batch_summaries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_batch_id')->unique()->comment('FK to product_batches table (one summary per batch)');
            $table->foreignId('product_id')->comment('FK to products (denormalized for fast reports)');
            $table->foreignId('variation_id')->nullable()->comment('FK to product_variations');
            $table->foreignId('warehouse_id')->comment('FK to warehouses');
            $table->foreignId('vendor_id')->nullable()->comment('FK to vendors (multi-vendor)');

            // Quantity Tracking
            $table->integer('total_received')->default(0)->comment('Total qty received in this batch');
            $table->integer('total_sold')->default(0)->comment('Total qty sold from this batch');
            $table->integer('total_returned')->default(0)->comment('Qty returned by customers and restocked');
            $table->integer('total_wasted')->default(0)->comment('Qty wasted/lost');
            $table->integer('total_damaged')->default(0)->comment('Qty damaged');
            $table->integer('total_expired')->default(0)->comment('Qty expired');
            $table->integer('total_reserved')->default(0)->comment('Qty reserved for pending orders');
            $table->integer('total_available')->default(0)->comment('Current remaining qty');

            // Financial Tracking
            $table->decimal('total_purchase_cost', 14, 2)->default(0)->comment('received × cost_price');
            $table->decimal('total_cogs', 14, 2)->default(0)->comment('Sum of cost_total from sale_item_batches');
            $table->decimal('total_revenue', 14, 2)->default(0)->comment('Sum of total_sale_amount from sale_item_batches');
            $table->decimal('total_profit', 14, 2)->default(0)->comment('total_revenue - total_cogs');
            $table->decimal('total_loss', 14, 2)->default(0)->comment('Loss from wastage/damage/expiry × cost_price');
            $table->decimal('profit_margin_percent', 5, 2)->default(0)->comment('(total_profit / total_revenue) × 100');

            $table->timestamp('last_updated_at')->nullable()->comment('Last time this summary was recalculated');
            $table->timestamps();

            $table->index(['product_id', 'warehouse_id']);
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_summaries');
    }
};
