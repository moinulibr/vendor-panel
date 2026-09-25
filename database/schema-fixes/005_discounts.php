<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


if (Schema::hasTable('discounts')) {
    Schema::table('discounts', function (Blueprint $table) {

        // 1. Core Columns
        if (!Schema::hasColumn('discounts', 'is_ecom')) {
            $table->boolean('is_ecom')->default(false)->after('status')->comment('true/1 is = applicabe for ecommerce');
        }
        if (!Schema::hasColumn('discounts', 'is_mobile_app')) {
            $table->boolean('is_mobile_app')->default(false)->after('is_ecom')->comment('true/1 is = applicabe for dealer mobile app');
        }
        if (!Schema::hasColumn('discounts', 'is_admin_pos')) {
            $table->boolean('is_admin_pos')->default(false)->after('is_mobile_app')->comment('true/1 is = applicabe for admin pos');
        }
        if (!Schema::hasColumn('discounts', 'is_sr_panel')) {
            $table->boolean('is_sr_panel')->default(false)->after('is_admin_pos')->comment('true/1 is = applicabe for SR panel');
        }
    });
};