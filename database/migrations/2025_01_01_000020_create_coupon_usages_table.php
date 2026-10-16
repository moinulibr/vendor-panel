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
            $table->foreignId('coupon_id')->nullable()->comment('FK to coupons table');
            $table->foreignId('sale_id')->nullable()->comment('FK to sales table');
            $table->foreignId('customer_id')->nullable()->comment('FK to users table'); // FIXED: removed duplicate nullable()

            $table->decimal('discount_applied', 12, 2)->comment('Actual coupon discount given');
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
