<?php
// 2025_01_01_000013_create_purchase_returns_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no', 50)->unique();
            $table->foreignId('bill_id')->constrained('purchase_bills')->restrictOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();

            $table->enum('return_type', ['full', 'partial']);
            $table->decimal('subtotal_refund', 12, 2)->default(0);
            $table->decimal('inv_disc_reversal', 12, 2)->default(0);
            $table->decimal('tax_reversal', 12, 2)->default(0);
            $table->decimal('freight_reversal', 12, 2)->default(0);
            $table->decimal('total_return', 12, 2)->default(0);
            $table->enum('settlement_type', ['debit_note', 'refund', 'adjusted']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
