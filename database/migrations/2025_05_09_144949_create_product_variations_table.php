<?php
// 2025_XX_XX_XX_alter_variations_schema_v3.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
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
                        "ALTER TABLE `{$table}` MODIFY `{$col}` TINYINT(1) NOT NULL DEFAULT {$default} COMMENT '{$comment}'"
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
     * If column missing → skip silently.
     */
    private function applyColumnDefinitions(string $table, array $defs): void
    {
        foreach ($defs as $col => [$type, $null, $default, $comment]) {
            if (!Schema::hasColumn($table, $col)) continue;
            try {
                $defaultSql = $default !== null ? "DEFAULT {$default}" : '';
                $commentSql = addslashes($comment);
                DB::statement(
                    "ALTER TABLE `{$table}` MODIFY `{$col}` {$type} {$null} {$defaultSql} COMMENT '{$commentSql}'"
                );
            } catch (\Exception $e) {
                Log::warning("MODIFY failed {$table}.{$col}: " . $e->getMessage());
            }
        }
    }

    public function up(): void
    {
        if (!Schema::hasTable('variations')) return;

        // ============================================================
        // PHASE 1: BOOLEAN COLUMNS (handle exist/missing)
        // ============================================================
        $this->ensureBooleanColumns('variations', [
            'is_single_type'                => ['default' => 0, 'comment' => '1 = single fallback variation'],
            'is_default_selected_variation' => ['default' => 0, 'comment' => '1 = default selected variation'],
            'return_available'              => ['default' => 1, 'comment' => 'Can be returned'],
            'allow_discount'                => ['default' => 1, 'comment' => 'Discount allowed on this variant'],
            'track_batch'                   => ['default' => 1, 'comment' => 'Enable batch tracking for this variant', 'after' => 'stock_alert'],
            'track_expiry'                  => ['default' => 1, 'comment' => 'Enable expiry tracking for this variant', 'after' => 'track_batch'],
            'track_serial'                  => ['default' => 1, 'comment' => 'Enable serial tracking for this variant', 'after' => 'track_expiry'],
        ]);

        // ============================================================
        // PHASE 2: NON-BOOLEAN EXISTING COLUMNS — MODIFY only if exists
        // ============================================================
        $this->applyColumnDefinitions('variations', [
            // ---- Basic Info ----
            'product_id'                => ['BIGINT UNSIGNED', 'NOT NULL', null, 'FK to products (parent)'],
            'name'                      => ['VARCHAR(255)', 'NULL', null, 'Variation display name (e.g. Red / M)'],
            'attribute'                 => ['TEXT', 'NULL', null, 'Variation attributes (JSON or text)'],
            'slug'                      => ['VARCHAR(255)', 'NULL', null, 'SEO-friendly slug'],
            'sub_sku'                   => ['VARCHAR(255)', 'NULL', null, 'Variant-specific SKU'],

            // ---- Pricing ----
            'purchase_price'            => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Variant purchase cost'],
            'sell_price'                => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Variant selling price'],
            'mrp'                       => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Maximum retail price'],
            'retail_price'              => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Retail price tier'],
            'dealer_price'              => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Dealer price tier'],
            'wholesale_price'           => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Wholesale price tier'],
            'vendor_purchase_price'     => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Vendor purchase price'],
            'vendor_base_price'         => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Vendor base price'],
            'vendor_wholesale_price'    => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Vendor wholesale price'],
            'vendor_mrp'                => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Vendor MRP'],
            'last_price_updated_date'   => ['TIMESTAMP', 'NULL', null, 'Last price change timestamp'],
            'before_updated_prices'     => ['JSON', 'NULL', null, 'Historical prices snapshot'],

            // ---- Media & Identifiers ----
            'image'                     => ['VARCHAR(255)', 'NULL', null, 'Variant image URL (relative path)'],
            'image_size'                => ['BIGINT UNSIGNED', 'NULL', null, 'Image size in bytes'],
            'barcode'                   => ['VARCHAR(255)', 'NULL', null, 'Variant barcode / EAN / UPC'],
            'mpn'                       => ['VARCHAR(255)', 'NULL', null, 'Manufacturer Part Number'],
            'custom_code'               => ['VARCHAR(255)', 'NULL', null, 'Internal custom code'],

            // ---- Status ----
            'status'                    => ['TINYINT', 'NOT NULL', '1', '0=deleted, 1=active, 2=inactive'],

            // ---- Discount Policy ----
            'max_discount_percent'      => ['DECIMAL(5,2)', 'NOT NULL', '0.00', 'Max discount % allowed'],
            'min_sale_price'            => ['DECIMAL(12,2)', 'NOT NULL', '0.00', 'Minimum floor price'],

            // ---- Tax & VAT (Mushak) ----
            'tax_type'                  => ["ENUM('exclusive','inclusive','exempt')", 'NOT NULL', "'exclusive'", 'VAT behavior mode'],
            'tax_rate'                  => ['DECIMAL(5,2)', 'NOT NULL', '0.00', 'VAT rate %'],
            'tax_category'              => ['VARCHAR(255)', 'NULL', null, 'Tax group for Mushak reporting'],
            'hs_code'                   => ['VARCHAR(255)', 'NULL', null, 'Harmonized System Code'],

            // ---- Stock & Shipping ----
            'stock_quantity'            => ['INT', 'NOT NULL', '0', 'Current stock quantity'],
            'weight'                    => ['DECIMAL(8,2)', 'NOT NULL', '0.00', 'Weight in kg'],
            'length'                    => ['DECIMAL(8,2)', 'NOT NULL', '0.00', 'Length in cm'],
            'width'                     => ['DECIMAL(8,2)', 'NOT NULL', '0.00', 'Width in cm'],
            'height'                    => ['DECIMAL(8,2)', 'NOT NULL', '0.00', 'Height in cm'],
            'sort_order'                => ['INT', 'NOT NULL', '0', 'Display order'],
        ]);

        // ============================================================
        // PHASE 3: ADD NEW COLUMNS (missing-only, with hasColumn guard)
        // ============================================================
        Schema::table('variations', function (Blueprint $table) {

            // ---- Duplicate detection ----
            if (!Schema::hasColumn('variations', 'attribute_hash')) {
                $table->string('attribute_hash', 64)->nullable()
                    ->comment('MD5 hash of attributes for duplicate check')
                    ->after('attribute');
            }

            // ---- Stock tracking ----
            if (!Schema::hasColumn('variations', 'stock_reserved')) {
                $table->integer('stock_reserved')->default(0)
                    ->comment('Reserved for pending orders')
                    ->after('attribute_hash');
            }
            if (!Schema::hasColumn('variations', 'stock_alert')) {
                $table->decimal('stock_alert', 8, 2)->default(1.00)
                    ->comment('Low-stock alert threshold for this variant')
                    ->after('stock_reserved');
            }
            // NOTE: track_batch, track_expiry, track_serial are handled in Phase 1
            //       via ensureBooleanColumns() — they will be added there if missing.

            // ---- Multi-vendor ----
            if (!Schema::hasColumn('variations', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()
                    ->comment('Vendor supplying this variant')
                    ->after('track_serial');
            }

            // ---- Commission ----
            if (!Schema::hasColumn('variations', 'salesperson_commission_type')) {
                $table->enum('salesperson_commission_type', ['percentage', 'fixed'])->nullable()
                    ->comment('Salesperson commission type')
                    ->after('vendor_id');
            }
            if (!Schema::hasColumn('variations', 'salesperson_commission_value')) {
                $table->decimal('salesperson_commission_value', 8, 2)->default(0.00)
                    ->comment('Salesperson commission value')
                    ->after('salesperson_commission_type');
            }

            // ---- Analytics (denormalized) ----
            if (!Schema::hasColumn('variations', 'total_sold')) {
                $table->integer('total_sold')->default(0)
                    ->comment('Total units sold (cache)')
                    ->after('salesperson_commission_value');
            }
            if (!Schema::hasColumn('variations', 'last_sale_at')) {
                $table->timestamp('last_sale_at')->nullable()
                    ->comment('Last sale timestamp (cache)')
                    ->after('total_sold');
            }
        });

        // ============================================================
        // PHASE 4: INDEXES (with hasIndex guard)
        // ============================================================
        Schema::table('variations', function (Blueprint $table) {
            if (!$this->hasIndex('variations', 'idx_var_attr_hash')) {
                $table->index('attribute_hash', 'idx_var_attr_hash');
            }
            if (!$this->hasIndex('variations', 'idx_var_pid_attr')) {
                $table->index(['product_id', 'attribute_hash'], 'idx_var_pid_attr');
            }
            if (!$this->hasIndex('variations', 'idx_var_vendor')) {
                $table->index('vendor_id', 'idx_var_vendor');
            }
            if (!$this->hasIndex('variations', 'idx_var_track_batch')) {
                $table->index(['track_batch', 'status'], 'idx_var_track_batch');
            }
        });

        // ============================================================
        // PHASE 5: BACKFILL (populate attribute_hash for existing rows)
        // ============================================================
        if (
            Schema::hasColumn('variations', 'attribute_hash')
            && Schema::hasColumn('variations', 'attribute')
        ) {
            try {
                // Only fill where empty (do not overwrite existing)
                DB::statement(
                    "UPDATE variations 
                     SET attribute_hash = MD5(COALESCE(attribute, '')) 
                     WHERE attribute_hash IS NULL 
                       AND attribute IS NOT NULL 
                       AND attribute != ''"
                );
            } catch (\Exception $e) {
                Log::warning('Backfill attribute_hash failed: ' . $e->getMessage());
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('variations')) return;

        // Only drop columns that were ADDED by this migration
        // (pre-existing columns NOT touched, so no data loss)
        $columnsToDrop = [
            'attribute_hash',
            'stock_reserved',
            'stock_alert',
            'track_batch',
            'track_expiry',
            'track_serial',
            'vendor_id',
            'salesperson_commission_type',
            'salesperson_commission_value',
            'total_sold',
            'last_sale_at',
        ];

        $existing = [];
        foreach ($columnsToDrop as $col) {
            if (Schema::hasColumn('variations', $col)) {
                $existing[] = $col;
            }
        }
        if (!empty($existing)) {
            Schema::table('variations', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }

        // NOTE: Type conversions (int → tinyint) NOT reverted.
        // Reason: tinyint(1) values 0/1 fit perfectly in int, no data loss.
    }
};
