<?php
// 2025_01_01_000014_create_vendor_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 50)->unique()->comment('Unique payment number (VP-2025-0001)');
            $table->foreignId('vendor_id')->comment('FK to vendors');

            $table->decimal('amount', 14, 2)->comment('Payment amount');
            $table->enum('method', ['cash', 'bank', 'cheque', 'mobile', 'adjustment', 'advance'])
                ->comment('Payment method');
            $table->string('reference_no', 100)->nullable()->comment('Bank trx id / cheque no');

            $table->enum('status', ['pending', 'success', 'failed', 'reversed'])->default('pending');
            $table->timestamp('paid_at')->nullable();

            $table->foreignId('paid_by')->nullable()->comment('FK to users — who made the payment');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};
