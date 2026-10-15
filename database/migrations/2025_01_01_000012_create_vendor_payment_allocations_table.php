<?php
// 2025_01_01_000012_create_vendor_payment_allocations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('vendor_payments')->cascadeOnDelete();
            $table->foreignId('bill_id')->constrained('purchase_bills')->cascadeOnDelete();
            $table->decimal('amount_applied', 12, 2);
            $table->timestamps();

            $table->index(['payment_id', 'bill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payment_allocations');
    }
};
