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
            $table->foreignId('sale_id')->comment('FK to sales table');
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('total_refunded', 12, 2)->default(0);
            $table->decimal('net_paid', 12, 2)->default(0);
            $table->timestamp('last_payment_at')->nullable();
            $table->integer('payment_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_payment_summary');
    }
};
