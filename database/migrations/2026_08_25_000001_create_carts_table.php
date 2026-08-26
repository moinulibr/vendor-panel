<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade')->comment('null = guest, not null = logged in user as customer/retailer');
                // Cart Level Discounts & Coupon Management
                $table->string('coupon_code')->nullable();
                $table->foreignId('coupon_id')->nullable(); // If you have coupons/discounts table
                $table->decimal('discount_amount', 15, 4)->default(0);
                $table->string('discount_type')->nullable()->comment('fixed, percentage');
                $table->string('cart_from')->nullable()->comment('retailer_app, web, sr etc.');
                $table->foreignId('created_by')->nullable()->constrained('contacts')->onDelete('cascade')->comment('created by user (staff/retailer)');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carts')) {
            Schema::dropIfExists('carts');
        }
    }
};
