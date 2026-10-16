<?php
// 2025_01_01_000015_create_vendor_payment_allocations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->comment('FK to vendor_payments');
            $table->foreignId('bill_id')->comment('FK to purchase_bills');
            $table->decimal('amount_applied', 14, 2)->comment('How much of the payment goes to this bill');

            $table->timestamps();

            $table->index(['payment_id', 'bill_id']);
            $table->index('bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payment_allocations');
    }
};
