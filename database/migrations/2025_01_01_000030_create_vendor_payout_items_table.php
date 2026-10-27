<?php
// 2025_01_01_000030_create_vendor_payout_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->comment('FK to vendor_payouts');
            $table->foreignId('sale_id')->comment('FK to sales (denormalized)');
            $table->foreignId('sale_item_id')->comment('FK to sale_items');
            $table->string('invoice_no', 50)->nullable()->comment('Invoice number snapshot');
            $table->date('sale_date')->comment('Sale date snapshot');

            $table->decimal('line_total', 12, 2)->default(0)->comment('Sale item line_total');
            $table->enum('commission_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('commission_value', 8, 2)->default(0)->comment('Commission rate/value');
            $table->decimal('commission_amount', 12, 2)->default(0)->comment('Actual commission deducted');

            $table->decimal('return_amount', 12, 2)->default(0)->comment('Returns deducted for this line');
            $table->decimal('net_payable', 12, 2)->default(0)->comment('line_total - commission - returns');

            $table->decimal('post_payout_adjustment', 12, 2)->default(0)
                ->comment('Adjustment made in later payout for late returns');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['payout_id', 'sale_item_id']);
            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payout_items');
    }
};
