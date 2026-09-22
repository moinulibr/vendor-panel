<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


if (Schema::hasTable('carts')) {
    Schema::table('carts', function (Blueprint $table) {
        if (!Schema::hasColumn('carts', 'session_id')) {
            $table->string('session_id', 191)->nullable()->after('user_id')->comment('Browser/App Session ID for Guest Users');
        }

        if (!Schema::hasColumn('carts', 'guest_token')) {
            $table->string('guest_token', 191)->nullable()->after('session_id')->comment('Device/Guest Identification Token');
        }

        if (!Schema::hasColumn('carts', 'sub_total')) {
            $table->decimal('sub_total', 15, 2)->default(0.00)->nullable()->after('discount_type')->comment('Sum of all cart items subtotal');
        }

        if (!Schema::hasColumn('carts', 'shipping_charge')) {
            $table->decimal('shipping_charge', 15, 2)->default(0.00)->nullable()->after('sub_total')->comment('Negotiable or Fixed Shipping Fee');
        }

        if (!Schema::hasColumn('carts', 'tax_amount')) {
            $table->decimal('tax_amount', 15, 2)->default(0.00)->nullable()->after('shipping_charge')->comment('Total Estimated Tax');
        }

        if (!Schema::hasColumn('carts', 'final_amount')) {
            $table->decimal('final_amount', 15, 2)->default(0.00)->nullable()->after('tax_amount')->comment('Calculated Net Total Amount');
        }

        if (!Schema::hasColumn('carts', 'expires_at')) {
            $table->timestamp('expires_at')->nullable()->after('cart_from')->comment('Cart Expiration Timestamp');
        }
    });
}

// 2. Updating 'cart_items' Table
if (Schema::hasTable('cart_items')) {
    Schema::table('cart_items', function (Blueprint $table) {
        if (!Schema::hasColumn('cart_items', 'sub_total')) {
            $table->decimal('sub_total', 15, 2)->default(0.00)->nullable()->after('unit_price')->comment('Calculated: quantity * unit_price');
        }

        if (!Schema::hasColumn('cart_items', 'net_total')) {
            $table->decimal('net_total', 15, 2)->default(0.00)->nullable()->after('discount_id')->comment('Calculated: sub_total - discount_amount');
        }
    });
}