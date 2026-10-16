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
            $table->string('payment_no', 50)->unique()->comment('Auto-generated payment number');
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();

            $table->foreignId('customer_id')->nullable()
                ->comment('FK to users.id. For web-lead customers, use contacts.id.');

            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('method', ['cash', 'card', 'bkash', 'nagad', 'rocket', 'bank', 'credit', 'advance', 'exchange'])
                ->comment('credit/advance = adjustment against advance; exchange = adjustment from return');

            $table->string('reference_no', 100)->nullable()->comment('Gateway trx id / cheque no');
            $table->enum('status', ['pending', 'success', 'failed', 'reversed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('received_by')->nullable()->comment('FK to users — cashier/receiver');
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
