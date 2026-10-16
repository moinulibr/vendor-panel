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
            $table->string('return_no', 50)->unique()->comment('Auto-generated return number');
            $table->foreignId('sale_id')->comment('FK to sales table (original sale)');

            $table->foreignId('customer_id')->nullable()
                ->comment('FK to users.id. For web-lead customers, use contacts.id.');

            $table->foreignId('warehouse_id')->comment('FK to warehouses — where returned stock goes');

            $table->enum('return_type', ['full', 'partial', 'exchange'])->comment('Full/partial/exchange');
            $table->decimal('subtotal_refund', 12, 2)->default(0)->comment('Sum of returned line subtotals');
            $table->decimal('item_disc_reversal', 12, 2)->default(0)->comment('Item-level discount reversal');
            $table->decimal('inv_disc_reversal', 12, 2)->default(0)->comment('Invoice discount reversal');
            $table->decimal('tax_reversal', 12, 2)->default(0)->comment('VAT reversal');
            $table->decimal('shipping_refund', 12, 2)->default(0)->comment('Shipping refund (usually full return)');
            $table->decimal('restocking_fee', 12, 2)->default(0)->comment('Fee deducted (10-20% policy)');
            $table->decimal('total_refund', 12, 2)->default(0)->comment('Final refund amount');

            $table->enum('settlement_type', ['cash', 'original_method', 'store_credit', 'exchange'])
                ->comment('How is customer getting compensated?');
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
