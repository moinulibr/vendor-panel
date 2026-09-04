<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ==============================================================================
// [ADDED] Custom helper function to check if an index exists in MySQL
// ==============================================================================
if (!function_exists('hasIndex')) {
    function hasIndex(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();
        $result = DB::select("
            SELECT COUNT(1) as total 
            FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_SCHEMA = ? 
              AND TABLE_NAME = ? 
              AND INDEX_NAME = ?
        ", [$database, $table, $indexName]);

        return ($result[0]->total ?? 0) > 0;
    }
}

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
        // <-- MODIFIED: Index থাকলে এড়িয়ে যাবে, না থাকলে তৈরি করবে
        if (!hasIndex('products', 'idx_products_status_ecom_new')) {
            $table->index(['status', 'is_ecom', 'is_new'], 'idx_products_status_ecom_new');
        }
        if (!hasIndex('products', 'idx_products_status_ecom_cat')) {
            $table->index(['status', 'is_ecom', 'category_id'], 'idx_products_status_ecom_cat');
        }
        if (!hasIndex('products', 'idx_products_status_ecom_brand')) {
            $table->index(['status', 'is_ecom', 'brand_id'], 'idx_products_status_ecom_brand');
        }
        if (!hasIndex('products', 'idx_products_price_range')) {
            $table->index(['min_price', 'max_price'], 'idx_products_price_range');
        }

        // Search Indexes
        if (!hasIndex('products', 'idx_products_sku')) {
            $table->index('sku', 'idx_products_sku');
        }
        if (!hasIndex('products', 'idx_products_name')) {
            $table->index('name', 'idx_products_name');
        }
    });

    // Product Variations Table Indexes
    if (Schema::hasTable('variations')) {
        Schema::table('variations', function (Blueprint $table) {
            // <-- MODIFIED: Check before adding variations index
            if (!hasIndex('variations', 'idx_variations_sub_sku')) {
                $table->index('sub_sku', 'idx_variations_sub_sku');
            }
            if (!hasIndex('variations', 'idx_variations_subsku_product')) {
                $table->index(['sub_sku', 'product_id'], 'idx_variations_subsku_product');
            }
            if (!hasIndex('variations', 'idx_variations_pid_subsku')) {
                $table->index(['product_id', 'sub_sku'], 'idx_variations_pid_subsku');
            }
        });
    }

    // Stock Table Indexing for Faster Join/Query
    if (Schema::hasTable('product_stocks')) {
        Schema::table('product_stocks', function (Blueprint $table) {
            // <-- MODIFIED: Check before adding stocks index
            if (!hasIndex('product_stocks', 'idx_stocks_product_variation')) {
                $table->index(['product_id', 'variation_id'], 'idx_stocks_product_variation');
            }
        });
    }
}

if (!Schema::hasColumn('variations', 'image')) {
    Schema::table('variations', function (Blueprint $table) {
        $table->decimal('retail_price', 12, 2)->default(0)->after('sell_price')->comment('Retail Price for mobile app users');
        $table->decimal('wholesale_price', 12, 2)->default(0)->after('retail_price')->comment('Wholesale Price for mobile app reatiler/dealer users');
        $table->string('image')->nullable()->after('wholesale_price')->comment('Variants Product Image');
        $table->boolean('is_visible')->nullable()->default(1)->after('image')->comment('Variation type product will be visible AND single type product will be not visible');
    });
}

Schema::table('products', function (Blueprint $table) {

    if (!hasIndex('products', 'ft_products_name')) {

        $table->fullText(
            ['name', 'name_bangla'],
            'ft_products_name'
        );
    }
});
/*if (Schema::hasTable('product_images')) {

    if (!Schema::hasColumn('products', 'created_by')) {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }
}*/
//product_images -> every single variants should have image 
