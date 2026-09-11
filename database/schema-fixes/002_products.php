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

    if (!Schema::hasColumn('products', 'retail_price')) {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('retail_price', 12, 2)->default(0)->after('sell_price')->comment('Retail Price for all mobile app users');
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


if(Schema::hasTable('variations')){
    // 1. Basic Details & Slug
    if (!Schema::hasColumn('variations', 'attribute')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->text('attribute')->nullable()->after('name')->comment('variation attributes');
        });
    }
    if (!Schema::hasColumn('variations', 'slug')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->string('slug')->nullable()->after('attribute')->comment('variation slug');
        });
    }

    // 2. Core App Pricing
    if (!Schema::hasColumn('variations', 'mrp')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->decimal('mrp', 12, 2)->default(0)->after('sell_price')->comment('maximum retail price');
        });
    }
    if (!Schema::hasColumn('variations', 'retail_price')) {
        Schema::table(
        'variations',
        function (Blueprint $table) {
            $table->decimal('retail_price', 12, 2)->default(0)->after('mrp')->comment('Retail Price is below of mrp');
        });
    }
    if (!Schema::hasColumn('variations', 'dealer_price')) {
        Schema::table(
        'variations',
        function (Blueprint $table) {
            $table->decimal('dealer_price', 12, 2)->default(0)->after('retail_price')->comment('dealer price for dealer user only');
        });
    }
    if (!Schema::hasColumn('variations', 'wholesale_price')) {
        Schema::table(
        'variations',
        function (Blueprint $table) {
            $table->decimal('wholesale_price', 12, 2)->default(0)->after('dealer_price')->comment('Wholesale Price');
        });
    }

    // 3. Vendor / B2B Pricing Structure
    if (!Schema::hasColumn('variations', 'vendor_purchase_price')) {
        Schema::table('variations', function (Blueprint $table) {
            $table->decimal('vendor_purchase_price', 12, 2)->default(0)->after('wholesale_price')->comment('vendor purchase price');
        });
    }
    if (!Schema::hasColumn('variations', 'vendor_base_price')) {
        Schema::table(
            'variations',
            function (Blueprint $table) {
            $table->decimal('vendor_base_price', 12, 2)->default(0)->after('vendor_purchase_price')->comment('vendor base price for platform/marketplace');
        });
    }
    if (!Schema::hasColumn('variations', 'vendor_wholesale_price')) {
        Schema::table(
            'variations',
            function (Blueprint $table) {
        $table->decimal('vendor_wholesale_price', 12, 2)->default(0)->after('vendor_base_price')->comment('vendor wholesale price');
            });
    }
    if (!Schema::hasColumn('variations', 'vendor_mrp')) {
        Schema::table(
            'variations',
            function (Blueprint $table) {
                $table->decimal('vendor_mrp', 12, 2)->default(0)->after('vendor_wholesale_price')->comment('vendor recommended retail price / mrp');
            });
    }

    // 4. Logs & Price History
    if (!Schema::hasColumn('variations', 'last_price_updated_date')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->timestamp('last_price_updated_date')->nullable()->after('vendor_mrp')->comment('Timestamp of the last price change');
        });
    }
    if (!Schema::hasColumn('variations', 'before_updated_prices')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->json('before_updated_prices')->nullable()->after('last_price_updated_date')->comment('Historical prices snapshot before the last update');
        });
    }

    // 5. Media & Identifiers
    if (!Schema::hasColumn('variations', 'image')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->string('image')->nullable()->after('before_updated_prices')->comment('Variants Product Image');
        });
    }
    if (!Schema::hasColumn('variations', 'image_size')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->unsignedBigInteger('image_size')->nullable()->after('image');
        });
    }
    if (!Schema::hasColumn('variations', 'barcode')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('image_size');
        });
    }
    if (!Schema::hasColumn('variations', 'mpn')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->string('mpn')->nullable()->after('barcode')->comment('Manufacturer Part Number');
        });
    }
    if (!Schema::hasColumn('variations', 'custom_code')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->string('custom_code')->nullable()->after('mpn')->comment('Custom Code');
        });
    }

    // 6. Flags & Product Types
    if (!Schema::hasColumn('variations', 'is_single_type')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->boolean('is_single_type')->default(false)->comment('single product = true and all variations product = false');
        });
    }
    if (!Schema::hasColumn('variations', 'is_default_selected_variant')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->boolean('is_default_selected_variant')->default(false)->comment('a single product variant can have only one default selected variant');
        });
    }

    // 7. Status & Soft Deletes
    if (!Schema::hasColumn('variations', 'status')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->tinyInteger('status')->default(3)->comment('Variant Product Status -> 0 = deleted, 1 = Active, 2 = Inactive, 3 = Draft, 4 = Archived');
        });
    }
    if (!Schema::hasColumn('variations', 'deleted_at')) {
        Schema::table('variations',function (Blueprint $table) {
            $table->softDeletes();
        });
    }
}
/*if (!Schema::hasColumn('variations', 'image')) {
    Schema::table('variations', function (Blueprint $table) {
        // Basic Details & Slug
        $table->text('attribute')->nullable()->after('name')->comment('variation attributes');
        $table->string('slug')->nullable()->after('attribute')->comment('variation slug');

        // Core App Pricing (For Customers, Dealers & Wholesalers)
        $table->decimal('mrp', 12, 2)->default(0)->after('sell_price')->comment('maximum retail price');
        $table->decimal('retail_price', 12, 2)->default(0)->after('mrp')->comment('Retail Price is below of mrp');
        $table->decimal('dealer_price', 12, 2)->default(0)->after('retail_price')->comment('dealer price for dealer user only');
        $table->decimal('wholesale_price', 12, 2)->default(0)->after('dealer_price')->comment('Wholesale Price');

        // Vendor / B2B Pricing Structure
        $table->decimal('vendor_purchase_price', 12, 2)->default(0)->after('wholesale_price')->comment('vendor purchase price');
        $table->decimal('vendor_base_price', 12, 2)->default(0)->after('vendor_purchase_price')->comment('vendor base price for platform/marketplace');
        $table->decimal('vendor_wholesale_price', 12, 2)->default(0)->after('vendor_base_price')->comment('vendor wholesale price');
        $table->decimal('vendor_mrp', 12, 2)->default(0)->after('vendor_wholesale_price')->comment('vendor recommended retail price / mrp');

        $table->timestamp('last_price_updated_date')->nullable()->after('vendor_mrp')->comment('Timestamp of the last price change');
        $table->json('before_updated_prices')->nullable()->after('last_price_updated_date')->comment('Historical prices snapshot before the last update');

        // Media & Identifiers
        $table->string('image')->nullable()->after('before_updated_prices')->comment('Variants Product Image');
        $table->unsignedBigInteger('image_size')->nullable()->after('image');
        $table->string('barcode')->nullable()->after('image_size');
        $table->string('mpn')->nullable()->after('barcode')->comment('Manufacturer Part Number');
        $table->string('custom_code')->nullable()->after('mpn')->comment('Custom Code');

        // Flags & Product Types
        $table->boolean('is_single_type')->default(false)->comment('single product = true and all variations product = false');
        $table->boolean('is_default_selected_variant')->default(false)->comment('a single product variant can have only one default selected variant');

        // Status & Soft Deletes
        $table->tinyInteger('status')->default(3)->comment('Variant Product Status -> 0 = deleted, 1 = Active, 2 = Inactive, 3 = Draft, 4 = Archived');
        $table->softDeletes();
        //$table->boolean('is_visible')->nullable()->default(1)->after('image')->comment('Variation type product will be visible AND single type product will be not visible');
    });
}*/
/*
    public function down(): void
    {
        Schema::table('variations', function (Blueprint $table) {
            // Drop columns array akare handle korar upay
            $columns = [
                'attribute', 'slug', 'mrp', 'retail_price', 'dealer_price', 'wholesale_price',
                'vendor_purchase_price', 'vendor_base_price', 'vendor_wholesale_price', 'vendor_mrp',
                'last_price_updated_date', 'before_updated_prices', 'image', 'image_size', 'barcode',
                'mpn', 'custom_code', 'is_single_type', 'is_default_selected_variant', 'status'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('variations', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('variations', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
*/


// $table->enum('type', ['single', 'variable', 'combo', 'service'])->default('single');
// $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');

//main products
// SEO & Meta
//$table->string('meta_title')->nullable();
//$table->text('meta_description')->nullable();
//$table->string('meta_keywords')->nullable();
/*
    Schema::table('products', function (Blueprint $table) {
            
            // 1. Basic Details
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name')->comment('product slug');
            }

            // 2. Core App Pricing (Check kore missing thakle add hobe)
            if (!Schema::hasColumn('products', 'mrp')) {
                $table->decimal('mrp', 12, 2)->default(0)->after('slug')->comment('maximum retail price');
            }
            if (!Schema::hasColumn('products', 'retail_price')) {
                $table->decimal('retail_price', 12, 2)->default(0)->after('mrp')->comment('Retail Price is below of mrp');
            }
            if (!Schema::hasColumn('products', 'dealer_price')) {
                $table->decimal('dealer_price', 12, 2)->default(0)->after('retail_price')->comment('dealer price for dealer user only');
            }
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 12, 2)->default(0)->after('dealer_price')->comment('Wholesale Price');
            }

            // 3. Vendor / B2B Pricing Structure
            if (!Schema::hasColumn('products', 'vendor_purchase_price')) {
                $table->decimal('vendor_purchase_price', 12, 2)->default(0)->after('wholesale_price')->comment('vendor purchase price');
            }
            if (!Schema::hasColumn('products', 'vendor_base_price')) {
                $table->decimal('vendor_base_price', 12, 2)->default(0)->after('vendor_purchase_price')->comment('vendor base price for platform/marketplace');
            }
            if (!Schema::hasColumn('products', 'vendor_wholesale_price')) {
                $table->decimal('vendor_wholesale_price', 12, 2)->default(0)->after('vendor_base_price')->comment('vendor wholesale price');
            }
            if (!Schema::hasColumn('products', 'vendor_mrp')) {
                $table->decimal('vendor_mrp', 12, 2)->default(0)->after('vendor_wholesale_price')->comment('vendor recommended retail price / mrp');
            }

            // 4. Logs & Price History
            if (!Schema::hasColumn('products', 'last_price_updated_date')) {
                $table->timestamp('last_price_updated_date')->nullable()->after('vendor_mrp')->comment('Timestamp of the last price change');
            }
            if (!Schema::hasColumn('products', 'before_updated_prices')) {
                $table->json('before_updated_prices')->nullable()->after('last_price_updated_date')->comment('Historical prices snapshot before the last update');
            }

            // 5. Media & Identifiers
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('before_updated_prices')->comment('Main Product Image');
            }
            if (!Schema::hasColumn('products', 'image_size')) {
                $table->unsignedBigInteger('image_size')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('image_size');
            }
            if (!Schema::hasColumn('products', 'mpn')) {
                $table->string('mpn')->nullable()->after('barcode')->comment('Manufacturer Part Number');
            }
            if (!Schema::hasColumn('products', 'custom_code')) {
                $table->string('custom_code')->nullable()->after('mpn')->comment('Custom Code');
            }

            // 6. Flags
            if (!Schema::hasColumn('products', 'is_single_type')) {
                $table->boolean('is_single_type')->default(false)->comment('single product = true and all variations product = false');
            }

            // 7. Status & Soft Deletes
            if (!Schema::hasColumn('products', 'status')) {
                $table->tinyInteger('status')->default(3)->comment('Product Status -> 0 = deleted, 1 = Active, 2 = Inactive, 3 = Draft, 4 = Archived');
            }
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        --
        public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'slug', 'mrp', 'retail_price', 'dealer_price', 'wholesale_price',
                'vendor_purchase_price', 'vendor_base_price', 'vendor_wholesale_price', 'vendor_mrp',
                'last_price_updated_date', 'before_updated_prices', 'image', 'image_size', 'barcode',
                'mpn', 'custom_code', 'is_single_type', 'status'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('products', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
*/




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
