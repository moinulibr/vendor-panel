<?php
// 2025_01_01_000021_create_sale_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 50)->unique();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->comment('FK to users table. However, for web-lead customers, the contact_id from the contacts table will be used instead.');
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('method', ['cash', 'card', 'bkash', 'nagad', 'rocket', 'bank', 'credit','advance', 'exchange'])->comment('credit = advance payment');
            $table->string('reference_no', 100)->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'reversed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('received_by')->nullable()->comment('FK to users');
            $table->unsignedBigInteger('register_session_id')->nullable()->comment('FK to cash_register_sessions');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index(['customer_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_payments');
    }
};
