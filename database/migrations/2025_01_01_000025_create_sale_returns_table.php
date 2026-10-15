<?php
// 2025_01_01_000025_create_sale_returns_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no', 50)->unique();
            $table->foreignId('sale_id')->comment('FK to sales table');
            $table->foreignId('customer_id')->nullable()->comment('FK to users table However, for web-lead customers, the contact_id from the contacts table will be used instead.');
            $table->foreignId('warehouse_id')->comment('FK to warehouses table [it will be used as locations table]');

            $table->enum('return_type', ['full', 'partial', 'exchange']);
            $table->decimal('subtotal_refund', 12, 2)->default(0);
            $table->decimal('item_disc_reversal', 12, 2)->default(0);
            $table->decimal('inv_disc_reversal', 12, 2)->default(0);
            $table->decimal('tax_reversal', 12, 2)->default(0);
            $table->decimal('shipping_refund', 12, 2)->default(0);
            $table->decimal('restocking_fee', 12, 2)->default(0)->comment('Fee deducted from refunds for returned items (usually 10-20%)');
            $table->decimal('total_refund', 12, 2)->default(0);

            $table->enum('settlement_type', ['cash', 'original_method', 'store_credit', 'exchange']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->comment('FK to users table');
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
