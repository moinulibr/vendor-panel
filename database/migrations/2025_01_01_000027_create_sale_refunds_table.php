<?php
// 2025_01_01_000027_create_sale_refunds_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_no', 50)->unique();
            $table->foreignId('return_id')->comment('FK to sale_returns table');
            $table->foreignId('customer_id')->comment('FK to users table However, for web-lead customers, the contact_id from the contacts table will be used instead.');
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('method', ['cash', 'original_method', 'store_credit', 'bank', 'mobile','other']);
            $table->string('reference_no', 100)->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('refunded_at')->nullable();
            $table->foreignId('refunded_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index('return_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_refunds');
    }
};
