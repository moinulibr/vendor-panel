<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('products')) {

    if (!Schema::hasColumn('products', 'created_by')) {

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }

    if (Schema::hasColumn('products', 'user_id')) {
        DB::statement("
            ALTER TABLE products
            MODIFY COLUMN user_id BIGINT UNSIGNED NULL
            COMMENT 'Foreign key reference to vendor (users.id)'
        ");
    }


    if (!Schema::hasColumn('products', 'min_price')) {

        Schema::table('products', function (Blueprint $table) {

            $table->decimal('min_price', 12, 2)->default(0)->after('sell_price');
            $table->decimal('max_price', 12, 2)->default(0)->after('min_price');

            // Performance Compound Indexes
            $table->index(['status', 'is_ecom', 'category_id']);
            $table->index(['status', 'is_ecom', 'brand_id']);
            $table->index(['min_price', 'max_price']);
        });
    }

    /*Schema::table('products', function (Blueprint $table) {
        $table->index(['status', 'is_ecom', 'is_new']); // Composite Index for base filters
        $table->index('sku');                           // Index for Main SKU
        $table->index('name');                          // Index for Name search
    });

    // Product Variations 
    Schema::table('product_variations', function (Blueprint $table) {
        $table->index('sub_sku');                       // Index for Variation SKU
        $table->index(['sub_sku', 'product_id']);       // Composite Index for fast lookup
    });*/

}
