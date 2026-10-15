<?php
// 2025_01_01_000028_create_sale_credits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->comment('FK to users table');
            $table->foreignId('source_return_id')->nullable()->comment('FK to sale_returns table');
            $table->decimal('amount', 12, 2);
            $table->decimal('used', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'balance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_credits');
    }
};
