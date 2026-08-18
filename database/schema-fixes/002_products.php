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
        });
    }

    // High Performance Composite & Single Indexes for Products Table
    Schema::table('products', function (Blueprint $table) {
        // Base Status & Catalog Compound Indexes
        $table->index(['status', 'is_ecom', 'is_new'], 'idx_products_status_ecom_new');
        $table->index(['status', 'is_ecom', 'category_id'], 'idx_products_status_ecom_cat');
        $table->index(['status', 'is_ecom', 'brand_id'], 'idx_products_status_ecom_brand');
        $table->index(['min_price', 'max_price'], 'idx_products_price_range');

        // Search Indexes
        $table->index('sku', 'idx_products_sku');
        $table->index('name', 'idx_products_name');
    });

    // Product Variations Table Indexes
    if (Schema::hasTable('variations')) {
        Schema::table('variations', function (Blueprint $table) {
            $table->index('sub_sku', 'idx_variations_sub_sku');
            $table->index(['sub_sku', 'product_id'], 'idx_variations_subsku_product');
            $table->index(['product_id', 'sub_sku'], 'idx_variations_pid_subsku');
        });
    }

    // Stock Table Indexing for Faster Join/Query
    if (Schema::hasTable('product_stocks')) {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->index(['product_id', 'variant_id'], 'idx_stocks_product_variant');
        });
    }
}
