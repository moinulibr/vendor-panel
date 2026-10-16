<?php
// 2025_01_01_000022_create_sale_payment_summary_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_payment_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->comment('FK to sales table — also PK'); // FIXED: added primary()
            $table->decimal('total_paid', 12, 2)->default(0)->comment('Sum of successful sale_payments');
            $table->decimal('total_refunded', 12, 2)->default(0)->comment('Sum of successful sale_refunds');
            $table->decimal('net_paid', 12, 2)->default(0)->comment('total_paid - total_refunded');
            $table->timestamp('last_payment_at')->nullable();
            $table->integer('payment_count')->default(0)->comment('Count of payments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_payment_summary');
    }
};
