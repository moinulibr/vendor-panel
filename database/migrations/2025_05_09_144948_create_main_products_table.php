<?php
// 2025_XX_XX_XX_alter_products_schema_v3.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            return !empty(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ensure boolean columns exist with correct type + comment.
     * Handles: exists (normalize+modify), missing (add), already boolean (idempotent).
     */
    private function ensureBooleanColumns(string $table, array $columns): void
    {
        foreach ($columns as $col => $meta) {
            $default = (int) ($meta['default'] ?? 0);
            $comment = addslashes($meta['comment'] ?? '');
            $after   = $meta['after'] ?? null;

            if (Schema::hasColumn($table, $col)) {
                // Existing → normalize + MODIFY
                try {
                    DB::statement(
                        "UPDATE `{$table}` SET `{$col}` = CASE 
                            WHEN `{$col}` IS NULL THEN 0
                            WHEN `{$col}` > 0 THEN 1 
                            ELSE 0 
                        END"
                    );
                } catch (\Exception $e) {
                    Log::warning("Normalize failed {$table}.{$col}: " . $e->getMessage());
                }

                try {
                    DB::statement(
                        "ALTER TABLE `{$table}` MODIFY `{$col}` TINYINT(1)  NULL DEFAULT {$default} COMMENT '{$comment}'"
                    );
                } catch (\Exception $e) {
                    Log::warning("MODIFY failed {$table}.{$col}: " . $e->getMessage());
                }
            } else {
                // Missing → ADD
                try {
                    Schema::table($table, function (Blueprint $t) use ($col, $default, $comment, $after) {
                        $column = $t->boolean($col)->default((bool)$default)->comment($comment);
                        if ($after) $column->after($after);
                    });
                } catch (\Exception $e) {
                    Log::warning("ADD failed {$table}.{$col}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Apply MODIFY (type + default + comment) on EXISTING columns only.
     * If column missing → skip silently (assumed added elsewhere).
     * Used for NON-boolean columns (prices, strings, enums).
     */
    private function applyColumnDefinitions(string $table, array $defs): void
    {
        foreach ($defs as $col => [$type, $null, $default, $comment]) {
            $defaultSql = $default !== null ? "DEFAULT {$default}" : '';
            $commentSql = addslashes($comment);
            if (!Schema::hasColumn($table, $col)){
                try {
                    DB::statement(
                        "ALTER TABLE `{$table}` ADD `{$col}` {$type } {$null} {$defaultSql} COMMENT '{$commentSql}'"
                    );
                } catch (\Exception $e) {
                    Log::warning("ADD column failed {\$table}.{col: " . $e->getMessage());
                } 
            }else{
                try {
                    DB::statement(
                        "ALTER TABLE `{$table}` MODIFY `{$col}` {$type} {$null} {$defaultSql} COMMENT '{$commentSql}'"
                    );
                } catch (\Exception $e) {
                    Log::warning("MODIFY failed {$table}.{$col}: " . $e->getMessage());
                }
            }
        }
    }

    public function up(): void
    {
        if (!Schema::hasTable('products')) return;

        // ============================================================
        // PHASE 1: BOOLEAN COLUMNS (handle exist/missing)
        // ============================================================
        $this->ensureBooleanColumns('products', [
            'is_ecom'            => ['default' => 1, 'comment' => 'Visible on ecommerce site'],
            'is_mobile_app'      => ['default' => 1, 'comment' => 'Visible on mobile app'],
            'is_feature'         => ['default' => 1, 'comment' => 'Featured product flag'],
            'is_reco'            => ['default' => 0, 'comment' => 'Recommended product flag'],
            //'is_new'             => ['default' => 1, 'comment' => 'New arrival flag'],
            'stock_manage'       => ['default' => 0, 'comment' => 'Manage stock or unlimited'],
            'warranty_available' => ['default' => 0, 'comment' => 'Has warranty'],
            'return_available'   => ['default' => 1, 'comment' => 'Return available flag'],
            'allow_discount'     => ['default' => 1, 'comment' => 'Discount allowed'],
        ]);

        // ============================================================
        // PHASE 2: NON-BOOLEAN EXISTING COLUMNS — MODIFY only if exists
        // ============================================================
        $this->applyColumnDefinitions('products', [
            // Basic
            'name'                    => ['VARCHAR(255)', 'NULL', null, 'Product display name'],
            'invoice_name'            => ['VARCHAR(255)', 'NULL', null, 'Invoice name'],
            'name_bangla'             => ['VARCHAR(255)', 'NULL', null, 'Bangla product name'],
            'invoice_name_bangla'     => ['VARCHAR(255)', 'NULL', null, 'Bangla product name for invoice'],
            'image'                   => ['VARCHAR(255)', 'NULL', null, 'Main image URL (relative path)'],
            'video'                   => ['VARCHAR(255)', 'NULL', null, 'Product video URL'],
            'type'                    => ['VARCHAR(50)',  'NULL', null, 'LEGACY: single or variable'],
            'barcode'                 => ['VARCHAR(255)', 'NULL', null, 'Barcode / EAN / UPC'],
            'mpn'                     => ['VARCHAR(255)', 'NULL', null, 'Manufacturer Part Number'],
            'custom_code'             => ['VARCHAR(255)', 'NULL', null, 'Internal custom code'],
            'description'             => ['TEXT', 'NULL', null, 'Full product description'],
            'specification'           => ['TEXT', 'NULL', null, 'Technical specifications'],

            // Relations
            'stock_alert'             => ['DECIMAL(8,2)', 'NULL', '1.00', 'Low-stock alert threshold'],
            'brand_id'                => ['BIGINT UNSIGNED', 'NULL', null, 'FK to brands'],
            'unit_id'                 => ['BIGINT UNSIGNED', 'NULL', null, 'FK to units'],
            'category_id'             => ['BIGINT UNSIGNED', 'NULL', null, 'FK to categories'],
            'sub_category_id'         => ['BIGINT UNSIGNED', 'NULL', null, 'FK to sub-categories'],
            'status'                  => ['TINYINT', 'NULL', '1', '0=inactive, 1=active'],
            'created_by'              => ['BIGINT UNSIGNED', 'NULL', null, 'Creator user ID'],

            // Pricing
            'purchase_price'          => ['DECIMAL(12,2)', 'NULL', '0.00', 'Latest purchase cost'],
            'sell_price'              => ['DECIMAL(12,2)', 'NULL', '0.00', 'Main selling price'],
            'min_price'               => ['DECIMAL(12,2)', 'NULL', '0.00', 'Min price across variations'],
            'max_price'               => ['DECIMAL(12,2)', 'NULL', '0.00', 'Max price across variations'],
            'wholesale_price'         => ['DECIMAL(12,2)', 'NULL', '0.00', 'Wholesale price'],
            'vendor_purchase_price'   => ['DECIMAL(12,2)', 'NULL', '0.00', 'Vendor purchase price'],
            'vendor_base_price'       => ['DECIMAL(12,2)', 'NULL', '0.00', 'Vendor base price'],
            'vendor_wholesale_price'  => ['DECIMAL(12,2)', 'NULL', '0.00', 'Vendor wholesale price'],
            'vendor_mrp'              => ['DECIMAL(12,2)', 'NULL', '0.00', 'Vendor MRP'],
            'mrp'                     => ['DECIMAL(12,2)', 'NULL', '0.00', 'Maximum retail price'],
            'retail_price'            => ['DECIMAL(12,2)', 'NULL', '0.00', 'Retail price'],
            'dealer_price'            => ['DECIMAL(12,2)', 'NULL', '0.00', 'Dealer price'],
            'last_price_updated_date' => ['TIMESTAMP', 'NULL', null, 'Last price change timestamp'],
            'before_updated_prices'   => ['JSON', 'NULL', null, 'Historical prices snapshot'],

            // Legacy / Extra
            'variants'                => ['TEXT', 'NULL', null, 'LEGACY: use variations table'],
            'user_id'                 => ['BIGINT UNSIGNED', 'NULL', null, 'Vendor user ID (legacy)'],
            'stock_quantity'          => ['INT', 'NULL', '0', 'Denormalized total stock'],

            // Warranty & Return
            'warranty_note'           => ['TEXT', 'NULL', null, 'Warranty terms'],
            'return_note'             => ['TEXT', 'NULL', null, 'Return policy'],
            'return_days'             => ['INT', 'NULL', '0', 'Return window (days)'],
            'warranty_days'           => ['INT', 'NULL', '0', 'Warranty period (days)'],
            'estimate_delivery_day'   => ['INT', 'NULL', null, 'Estimated delivery days'],
            'video_link'              => ['VARCHAR(255)', 'NULL', null, 'External video link'],
            'image_size'              => ['BIGINT UNSIGNED', 'NULL', null, 'Image size in bytes'],

            // SEO
            'meta_title'              => ['VARCHAR(255)', 'NULL', null, 'SEO meta title'],
            'meta_description'        => ['TEXT', 'NULL', null, 'SEO meta description'],
            'meta_keywords'           => ['VARCHAR(255)', 'NULL', null, 'SEO meta keywords'],

            // Discount Policy (values, flags)
            'max_discount_percent'    => ['DECIMAL(5,2)', 'NULL', '0.00', 'Max discount %'],
            'min_sale_price'          => ['DECIMAL(12,2)', 'NULL', '0.00', 'Minimum floor price'],

            // Tax
            'tax_type'                => ["ENUM('exclusive','inclusive','exempt')", 'NULL', "'exclusive'", 'Tax behavior'],
            'tax_rate'                => ['DECIMAL(5,2)', 'NULL', '0.00', 'VAT rate %'],
            'tax_category'            => ['VARCHAR(255)', 'NULL', null, 'Tax group for Mushak'],
            'hs_code'                 => ['VARCHAR(255)', 'NULL', null, 'Harmonized System Code'],

            // Shipping
            'weight'                  => ['DECIMAL(8,2)', 'NULL', '0.00', 'Weight (kg)'],
            'length'                  => ['DECIMAL(8,2)', 'NULL', '0.00', 'Length (cm)'],
            'width'                   => ['DECIMAL(8,2)', 'NULL', '0.00', 'Width (cm)'],
            'height'                  => ['DECIMAL(8,2)', 'NULL', '0.00', 'Height (cm)'],
            'sort_order'              => ['INT', 'NULL', '0', 'Display order'],
        ]);

        // ============================================================
        // PHASE 3: ADD NEW COLUMNS (missing-only, with hasColumn guard)
        // ============================================================
        Schema::table('products', function (Blueprint $table) {

            // ---- Product Type & Behavior ----
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->enum('product_type', ['simple', 'variant', 'combo', 'service'])
                    ->default('simple')->comment('Core product type')->after('type');
            }
            if (!Schema::hasColumn('products', 'has_variants')) {
                $table->boolean('has_variants')->default(false)->comment('True if has variations')->after('product_type');
            }
            if (!Schema::hasColumn('products', 'is_combo')) {
                $table->boolean('is_combo')->default(false)->comment('True if combo/bundle')->after('has_variants');
            }
            if (!Schema::hasColumn('products', 'is_service')) {
                $table->boolean('is_service')->default(false)->comment('True if service')->after('is_combo');
            }
            if (!Schema::hasColumn('products', 'is_digital')) {
                $table->boolean('is_digital')->default(false)->comment('True if digital')->after('is_service');
            }
            if (!Schema::hasColumn('products', 'download_url')) {
                $table->string('download_url', 500)->nullable()->comment('Digital download URL')->after('is_digital');
            }

            // ---- Stock Tracking ----
            if (!Schema::hasColumn('products', 'track_stock')) {
                $table->boolean('track_stock')->default(true)->comment('Track stock?')->after('download_url');
            }
            if (!Schema::hasColumn('products', 'track_batch')) {
                $table->boolean('track_batch')->default(true)->comment('Track batch?')->after('track_stock');
            }
            if (!Schema::hasColumn('products', 'track_expiry')) {
                $table->boolean('track_expiry')->default(true)->comment('Track expiry?')->after('track_batch');
            }
            if (!Schema::hasColumn('products', 'track_serial')) {
                $table->boolean('track_serial')->default(false)->comment('Track serial?')->after('track_expiry');
            }

            // ---- Sale / Purchase Flags ----
            if (!Schema::hasColumn('products', 'is_purchasable')) {
                $table->boolean('is_purchasable')->default(true)->comment('Can be purchased')->after('track_expiry');
            }
            if (!Schema::hasColumn('products', 'is_sellable')) {
                $table->boolean('is_sellable')->default(true)->comment('Can be sold')->after('is_purchasable');
            }

            // ---- Search ----
            if (!Schema::hasColumn('products', 'tags')) {
                $table->text('tags')->nullable()->comment('Comma-separated tags')->after('specification');
            }
            if (!Schema::hasColumn('products', 'category_path')) {
                $table->string('category_path', 500)->nullable()->comment('Denormalized category path')->after('sub_category_id');
            }

            // ---- Vendor ----
            if (!Schema::hasColumn('products', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->comment('Marketplace vendor owner')->after('user_id');
            }

            // ---- Packing / Service ----
            if (!Schema::hasColumn('products', 'pack_size')) {
                $table->decimal('pack_size', 8, 2)->default(1)->comment('Pack/carton size')->after('is_sellable');
            }
            if (!Schema::hasColumn('products', 'service_type')) {
                $table->enum('service_type', ['fixed', 'hourly', 'consultation', 'amc'])->nullable()
                    ->comment('Service type')->after('estimate_delivery_day');
            }
            if (!Schema::hasColumn('products', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable()->comment('Service duration (min)')->after('service_type');
            }

            // ---- Manufacturing ----
            if (!Schema::hasColumn('products', 'manufacturer_name')) {
                $table->string('manufacturer_name', 150)->nullable()->comment('Manufacturer name')->after('duration_minutes');
            }
            if (!Schema::hasColumn('products', 'origin_country')) {
                $table->string('origin_country', 100)->nullable()->comment('Country of origin')->after('manufacturer_name');
            }

            // ---- Commission ----
            if (!Schema::hasColumn('products', 'vendor_commission_type')) {
                $table->enum('vendor_commission_type', ['percentage', 'fixed'])->nullable()
                    ->comment('Vendor commission type')->after('mrp');
            }
            if (!Schema::hasColumn('products', 'vendor_commission_value')) {
                $table->decimal('vendor_commission_value', 8, 2)->default(0.00)
                    ->comment('Vendor commission value')->after('vendor_commission_type');
            }
            if (!Schema::hasColumn('products', 'salesperson_commission_type')) {
                $table->enum('salesperson_commission_type', ['percentage', 'fixed'])->nullable()
                    ->comment('Salesperson commission type')->after('vendor_commission_value');
            }
            if (!Schema::hasColumn('products', 'salesperson_commission_value')) {
                $table->decimal('salesperson_commission_value', 8, 2)->default(0.00)
                    ->comment('Salesperson commission value')->after('salesperson_commission_type');
            }

            // ---- Analytics ----
            if (!Schema::hasColumn('products', 'total_sold')) {
                $table->integer('total_sold')->default(0)->comment('Total sold (cache)')->after('salesperson_commission_type');
            }
            if (!Schema::hasColumn('products', 'total_purchased')) {
                $table->integer('total_purchased')->default(0)->comment('Total purchased (cache)')->after('total_sold');
            }
            if (!Schema::hasColumn('products', 'avg_rating')) {
                $table->decimal('avg_rating', 3, 2)->default(0.00)->comment('Avg rating (0-5)')->after('total_purchased');
            }
            if (!Schema::hasColumn('products', 'review_count')) {
                $table->integer('review_count')->default(0)->comment('Review count')->after('avg_rating');
            }
            if (!Schema::hasColumn('products', 'views_count')) {
                $table->integer('views_count')->default(0)->comment('View count')->after('review_count');
            }
            if (!Schema::hasColumn('products', 'last_sale_at')) {
                $table->timestamp('last_sale_at')->nullable()->comment('Last sale time')->after('views_count');
            }
            if (!Schema::hasColumn('products', 'last_purchase_at')) {
                $table->timestamp('last_purchase_at')->nullable()->comment('Last purchase time')->after('last_sale_at');
            }

            if (!Schema::hasColumn('products', 'approval_status')) {
                $table->enum('approval_status', ['draft', 'pending_approval', 'approved', 'rejected', 'changes_requested'])
                    ->default('approved')  // existing 2000 products auto-approved
                    ->comment('Vendor submission approval status')
                    ->after('status');
            }
            if (!Schema::hasColumn('products', 'approval_notes')) {
                $table->text('approval_notes')->nullable()
                    ->comment('Admin feedback for vendor')
                    ->after('approval_status');
            }
            if (!Schema::hasColumn('products', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()
                    ->comment('Vendor submission timestamp')
                    ->after('approval_notes');
            }
            if (!Schema::hasColumn('products', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()
                    ->comment('FK to users — admin who approved')
                    ->after('submitted_at');
            }
            if (!Schema::hasColumn('products', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()
                    ->comment('Approval timestamp')
                    ->after('approved_by');
            }
            if (!Schema::hasColumn('products', 'price_locked')) {
                $table->boolean('price_locked')->default(false)
                    ->comment('If true, vendor cannot change price fields')
                    ->after('approved_at');
            }
            if (!Schema::hasColumn('products', 'is_vendor_submitted')) {
                $table->boolean('is_vendor_submitted')->default(false)
                    ->comment('Quick filter: was this submitted by a vendor?')
                    ->after('price_locked');
            }

            // ---- Preorder ----
            if (!Schema::hasColumn('products', 'is_preorder')) {
                $table->boolean('is_preorder')->default(false)->comment('Preorder enabled')->after('last_purchase_at');
            }
            if (!Schema::hasColumn('products', 'preorder_available_date')) {
                $table->date('preorder_available_date')->nullable()->comment('Availability date')->after('is_preorder');
            }
        });

        // ============================================================
        // PHASE 4: INDEXES (with hasIndex guard)
        // ============================================================
        Schema::table('products', function (Blueprint $table) {
            if (!$this->hasIndex('products', 'idx_products_type_status')) {
                $table->index(['product_type', 'status'], 'idx_products_type_status');
            }
            if (!$this->hasIndex('products', 'idx_products_track_batch')) {
                $table->index(['track_batch', 'status'], 'idx_products_track_batch');
            }
            if (!$this->hasIndex('products', 'idx_products_featured')) {
                $table->index(['is_feature', 'status'], 'idx_products_featured');
            }
            if (!$this->hasIndex('products', 'idx_products_best_seller')) {
                $table->index(['total_sold', 'status'], 'idx_products_best_seller');
            }
            if (!$this->hasIndex('products', 'idx_products_top_rated')) {
                $table->index(['avg_rating', 'status'], 'idx_products_top_rated');
            }
            if (!$this->hasIndex('products', 'idx_products_vendor')) {
                $table->index('vendor_id', 'idx_products_vendor');
            }
        });

        // Fulltext
        if (!$this->hasIndex('products', 'ft_products_name')) {
            try {
                DB::statement("ALTER TABLE products ADD FULLTEXT ft_products_name (name, name_bangla) WITH PARSER ngram;");
            } catch (\Exception $e) {
                try {
                    DB::statement("ALTER TABLE products ADD FULLTEXT ft_products_name (name, name_bangla);");
                } catch (\Exception $e2) {
                    Log::warning('Fulltext failed: ' . $e2->getMessage());
                }
            }
        }

        // ============================================================
        // PHASE 5: BACKFILL
        // ============================================================
        if (Schema::hasColumn('products', 'product_type') && Schema::hasColumn('products', 'type')) {
            DB::table('products')->whereNull('product_type')->whereNotNull('name')->update(['product_type' => 'simple']);
            DB::table('products')->where('type', 'variable')
                ->update(['has_variants' => true, 'product_type' => 'variant']);
            DB::table('products')->where('type', 'single')
                ->update(['product_type' => 'simple']);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('products')) return;

        $columnsToDrop = [
            'product_type',
            'has_variants',
            'is_combo',
            'is_service',
            'is_digital',
            'download_url',
            'track_stock',
            'track_batch',
            'track_expiry',
            'track_serial',
            'is_purchasable',
            'is_sellable',
            'tags',
            'category_path',
            'vendor_id',
            'pack_size',
            'service_type',
            'duration_minutes',
            'manufacturer_name',
            'origin_country',
            'vendor_commission_type',
            'vendor_commission_value',
            'salesperson_commission_type',
            'salesperson_commission_value',
            'total_sold',
            'total_purchased',
            'avg_rating',
            'review_count',
            'views_count',
            'last_sale_at',
            'last_purchase_at',
            'is_preorder',
            'preorder_available_date',
            'invoice_name_bangla',
            'approval_status',
            'approval_notes',
            'submitted_at',
            'approved_by',
            'approved_at',
            'price_locked',
            'is_vendor_submitted',
        ];

        $existing = array_filter($columnsToDrop, fn($col) => Schema::hasColumn('products', $col));
        if (!empty($existing)) {
            Schema::table('products', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }
    }
};
