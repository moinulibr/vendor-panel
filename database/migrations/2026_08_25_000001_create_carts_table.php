<?php
// 2025_XX_XX_XX_create_carts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carts')) return;

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                ->comment('null = guest, not null = logged-in user (customer/exclusive customer/dealer)'); //->unique()

            // Source tracking
            $table->string('cart_from', 50)->nullable()
                ->comment('admin_pos, web_ecommerce, mobile_dealer_app, sr');

            // Coupon
            $table->string('coupon_code', 50)->nullable()->comment('Applied coupon code');
            $table->foreignId('coupon_id')->nullable()->comment('FK to coupons');
            $table->decimal('coupon_discount_amount', 15, 4)->default(0)
                ->comment('Coupon discount value (input)');
            $table->string('coupon_discount_type', 20)->nullable()
                ->comment('percentage|fixed');

            // Cart-level discount
            $table->decimal('discount_amount', 15, 4)->default(0)
                ->comment('Cart-level discount value (input)');
            $table->string('discount_type', 20)->nullable()
                ->comment('percentage|fixed');
            $table->foreignId('discount_id')->nullable()
                ->comment('FK to discounts if applied from a rule');

            // ✅ NEW: Totals (used by CartRepository::updateCartTotals)
            $table->decimal('sub_total', 15, 4)->default(0)
                ->comment('Sum of cart_items.sub_total');
            $table->decimal('item_total_discount', 15, 4)->default(0)
                ->comment('Sum of cart_items.discount_amount');
            $table->decimal('shipping_charge', 15, 4)->default(0)
                ->comment('Shipping/delivery charge');
            $table->decimal('tax_amount', 15, 4)->default(0)
                ->comment('Total VAT/tax');
            $table->decimal('final_amount', 15, 4)->default(0)
                ->comment('Grand total after all discounts + shipping');

            // ✅ NEW: Expiry (used by CartRepository::getOrCreateCart)
            $table->timestamp('expire_at')->nullable()
                ->comment('Cart expiry timestamp');

            $table->unsignedBigInteger('created_by')->nullable()
                ->comment('created by user (staff/retailer)');

            $table->timestamps();

            $table->index('cart_from');
            $table->index('expire_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
