<?php
// 2025_01_01_000020_create_coupon_usages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->nullable();
            $table->foreignId('sale_id')->nullable();
            $table->foreignId('customer_id')->nullable()->nullable();
            $table->decimal('discount_applied', 12, 2);
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();

            $table->index('sale_id');
            $table->index('coupon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
