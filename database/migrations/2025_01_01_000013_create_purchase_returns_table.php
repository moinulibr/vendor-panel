<?php
// 2025_01_01_000016_create_purchase_returns_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no', 50)->unique()->comment('Unique return number (PR-2025-0001)');
            $table->foreignId('bill_id')->comment('FK to purchase_bills (original bill)');
            $table->foreignId('vendor_id')->comment('FK to vendors');
            $table->foreignId('warehouse_id')->comment('FK to warehouses — from where goods are returned');

            $table->enum('return_type', ['full', 'partial'])->comment('Full or partial return');

            $table->decimal('subtotal_refund', 14, 2)->default(0)->comment('Sum of returned line subtotals');
            $table->decimal('item_disc_reversal', 14, 2)->default(0)->comment('Item-level discount reversal');
            $table->decimal('inv_disc_reversal', 14, 2)->default(0)->comment('Bill-level discount reversal');
            $table->decimal('tax_reversal', 14, 2)->default(0)->comment('VAT reversal');
            $table->decimal('freight_reversal', 14, 2)->default(0)->comment('Freight reversal');
            $table->decimal('total_return', 14, 2)->default(0)->comment('Final return amount');

            $table->enum('settlement_type', ['debit_note', 'refund', 'adjusted'])
                ->comment('How vendor will compensate us');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();

            $table->foreignId('created_by')->nullable()->comment('FK to users');
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index('bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
