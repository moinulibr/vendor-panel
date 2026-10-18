<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Fetching master data (brands, categories, attributes, units)...');

        // ============================================================
        // 1. FETCH MASTER IDs (with defensive fallbacks)
        // ============================================================
        $brandIds = $this->safePluck('brands', 'status', 1) ?: [1];
        $categoryIds = $this->safePluck('categories', 'status', 1) ?: [1];
        $unitIds = $this->safePluck('units') ?: [1];

        $dbAttributes = $this->safeAttributes();
        if ($dbAttributes->isEmpty()) {
            $dbAttributes = collect([
                (object) ['id' => 1, 'name' => 'Color', 'valus' => 'Red,Blue,Green'],
            ]);
        }

        // Parse attributes into usable array
        $parsedAttributes = [];
        foreach ($dbAttributes as $attr) {
            // Handle legacy typo `valus` OR correct `values`
            $values = $attr->valus ?? ($attr->values ?? 'Default');
            $parsedAttributes[] = [
                'id'     => $attr->id,
                'name'   => $attr->name,
                'values' => array_map('trim', explode(',', $values)),
            ];
        }

        $this->command->info('🚀 Seeding 200 products using real data...');

        // ============================================================
        // 2. PRODUCT DATA POOL
        // ============================================================
        $sanitaryItems = [
            ['name' => 'Wall Tap',            'bn' => 'ওয়াল ট্যাপ',            'image' => 'https://images.unsplash.com/photo-1584820927498-cafe3c1579c1?w=500'],
            ['name' => 'Towel Hanger',         'bn' => 'তোয়ালে হ্যাঙ্গার',      'image' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?w=500'],
            ['name' => 'Soap Case',            'bn' => 'সোপ কেস',              'image' => 'https://images.unsplash.com/photo-1585837575652-267c041d77d4?w=500'],
            ['name' => 'Commode',              'bn' => 'কমোড',                 'image' => 'https://images.unsplash.com/photo-1585671963294-6e1a4d4779ed?w=500'],
            ['name' => 'Toilet Paper Holder',  'bn' => 'টয়লেট পেপার হোল্ডার',   'image' => 'https://images.unsplash.com/photo-1584556812952-905ffd0c611a?w=500'],
            ['name' => 'Hand Shower',          'bn' => 'হ্যান্ড শাওয়ার',       'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500'],
            ['name' => 'Water Tank',           'bn' => 'ওয়াটার ট্যাংক',         'image' => 'https://images.unsplash.com/photo-1600857544200-b2f666a9a2ec?w=500'],
            ['name' => 'Mirror',               'bn' => 'আয়না',                 'image' => 'https://images.unsplash.com/photo-1585670210634-f7b7d238588a?w=500'],
            ['name' => 'Basin',                'bn' => 'বেসিন',                'image' => 'https://images.unsplash.com/photo-1556940623-902ee19d487b?w=500'],
            ['name' => 'Shower',               'bn' => 'শাওয়ার',               'image' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=500'],
            ['name' => 'Kitchen Sink',         'bn' => 'কিচেন সিঙ্ক',           'image' => 'https://images.unsplash.com/photo-1608528577891-eb055944f2e7?w=500'],
            ['name' => 'Bathroom Shelf',       'bn' => 'বাথরুম শেলফ',           'image' => 'https://images.unsplash.com/photo-1586942543784-eecaa23dfe8b?w=500'],
        ];

        $now = Carbon::now();
        $total = 200;

        // ============================================================
        // 3. MAIN LOOP — wrapped in transaction
        // ============================================================
        DB::beginTransaction();

        try {
            for ($i = 1; $i <= $total; $i++) {

                // ---------------------------------------------
                // 3.1 Determine product type
                // ---------------------------------------------
                $rand = rand(1, 100);
                if ($rand <= 30) {
                    $productType = 'simple';
                    $legacyType = 'single';
                    $hasVariants = false;
                    $isCombo = false;
                    $isService = false;
                } elseif ($rand <= 70) {
                    $productType = 'variant';
                    $legacyType = 'variable';
                    $hasVariants = true;
                    $isCombo = false;
                    $isService = false;
                } elseif ($rand <= 90) {
                    $productType = 'combo';
                    $legacyType = 'single';
                    $hasVariants = false;
                    $isCombo = true;
                    $isService = false;
                } else {
                    $productType = 'service';
                    $legacyType = 'single';
                    $hasVariants = false;
                    $isCombo = false;
                    $isService = true;
                }

                // ---------------------------------------------
                // 3.2 Base data (sequential uniqueness)
                // ---------------------------------------------
                $item = $sanitaryItems[array_rand($sanitaryItems)];
                $productName = $item['name'] . ' - ' . strtoupper(Str::random(4));
                $banglaName  = $item['bn']  . ' - ' . $i;

                $sku      = 'SKU-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT);
                $slug     = Str::slug($productName) . '-' . $i;
                $barcode  = '880' . str_pad((string) $i, 10, '0', STR_PAD_LEFT);
                $customCd = 'CST-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT);

                $sellPrice = rand(10000, 500000) / 100;   // 100.00 – 5000.00
                $mrp       = $sellPrice + (rand(1000, 5000) / 100);

                $brandId    = $brandIds[array_rand($brandIds)];
                $categoryId = $categoryIds[array_rand($categoryIds)];
                $unitId     = $unitIds[array_rand($unitIds)];

                // Stock behavior
                $trackStock   = !$isService;
                $stockManage  = !$isService;
                $trackBatch   = $trackStock && (rand(1, 100) <= 60); // 60% batch-tracked
                $trackExpiry  = $trackBatch && (rand(1, 100) <= 70);
                $trackSerial  = $trackStock && !$trackBatch && (rand(1, 100) <= 10);

                $baseStock = $trackStock ? rand(10, 500) : 0;

                // ---------------------------------------------
                // 3.3 Pre-calculate variations data (if variant)
                // ---------------------------------------------
                $variationsData = [];   // Will hold all variations to insert after
                $varPrices = [];
                $varStocks = [];

                if ($productType === 'variant') {
                    $attr        = $parsedAttributes[array_rand($parsedAttributes)];
                    $attrValues  = $attr['values'];
                    shuffle($attrValues);
                    $pickedVals  = array_slice($attrValues, 0, min(3, count($attrValues)));

                    foreach ($pickedVals as $vIdx => $val) {
                        $varSellPrice = round($sellPrice + (rand(50, 300) / 10), 2);
                        $varStock     = rand(10, 200);

                        $varPrices[] = $varSellPrice;
                        $varStocks[] = $varStock;

                        $variationsData[] = [
                            'is_default' => ($vIdx === 0),
                            'name'       => $val,
                            'attribute'  => [$attr['name'] => $val],
                            'sell_price' => $varSellPrice,
                            'stock'      => $varStock,
                            'sort_order' => $vIdx + 1,
                        ];
                    }
                } else {
                    // Simple/Combo/Service → 1 default variation
                    $variationsData[] = [
                        'is_default' => true,
                        'name'       => 'Default',
                        'attribute'  => ['Type' => 'Default'],
                        'sell_price' => $sellPrice,
                        'stock'      => $baseStock,
                        'sort_order' => 0,
                    ];
                    $varPrices[] = $sellPrice;
                    $varStocks[] = $baseStock;
                }

                // ---------------------------------------------
                // 3.4 Product-level aggregates
                // ---------------------------------------------
                $productStock  = array_sum($varStocks);
                $minPrice      = min($varPrices);
                $maxPrice      = max($varPrices);
                $totalPurchased = $productStock + rand(0, 100);
                $lastPurchase  = $now->copy()->subDays(rand(1, 60));
                $totalSold     = rand(0, 1000);
                $lastSaleAt    = $totalSold > 0 ? $now->copy()->subDays(rand(1, 30)) : null;

                // ---------------------------------------------
                // 3.5 INSERT PRODUCT
                // ---------------------------------------------
                $productId = DB::table('products')->insertGetId([
                    // ---- Basic ----
                    'name'                 => $productName,
                    'invoice_name'         => $productName,
                    'name_bangla'          => $banglaName,
                    'invoice_name_bangla'  => $banglaName,
                    'image'                => $item['image'],
                    'slug'                 => $slug,
                    'sku'                  => $sku,
                    'barcode'              => $barcode,
                    'mpn'                  => 'MPN-' . strtoupper(Str::random(6)),
                    'custom_code'          => $customCd,
                    'description'          => "High-quality {$item['name']} for daily use.",
                    'specification'        => "Material: Premium quality\nOrigin: Bangladesh",
                    'tags'                 => implode(',', [strtolower($item['name']), 'sanitary', 'bathroom']),

                    // ---- Type & Behavior ----
                    'type'                 => $legacyType,
                    'product_type'         => $productType,
                    'has_variants'         => $hasVariants,
                    'is_combo'             => $isCombo,
                    'is_service'           => $isService,
                    'is_digital'           => false,
                    'track_stock'          => $trackStock,
                    'track_batch'          => $trackBatch,
                    'track_expiry'         => $trackExpiry,
                    'track_serial'         => $trackSerial,
                    'is_purchasable'       => !$isService,
                    'is_sellable'          => true,

                    // ---- Relations ----
                    'stock_alert'          => 10.00,
                    'brand_id'             => $brandId,
                    'unit_id'              => $unitId,
                    'category_id'          => $categoryId,
                    'sub_category_id'      => null,
                    'category_path'        => 'Sanitary > ' . ucfirst($productType),

                    // ---- Status ----
                    'status'               => 1,
                    'is_ecom'              => 1,
                    'is_mobile_app'        => 1,
                    'is_feature'           => rand(0, 1),
                    'is_reco'              => rand(0, 1),
                    'is_new'               => 0,  // ✅ Always 0 per requirement

                    // ---- Audit ----
                    'created_at'           => $now,
                    'updated_at'           => $now,
                    'created_by'           => 1,

                    // ---- Pricing ----
                    'purchase_price'         => round($sellPrice * 0.7, 2),
                    'sell_price'             => $sellPrice,
                    'min_price'              => $minPrice,       // ✅ From variations
                    'max_price'              => $maxPrice,       // ✅ From variations
                    'wholesale_price'        => round($sellPrice * 0.9, 2),
                    'retail_price'           => $sellPrice,
                    'dealer_price'           => round($sellPrice * 0.85, 2),
                    'mrp'                    => $mrp,
                    'vendor_purchase_price'  => round($sellPrice * 0.65, 2),
                    'vendor_base_price'      => round($sellPrice * 0.7, 2),
                    'vendor_wholesale_price' => round($sellPrice * 0.8, 2),
                    'vendor_mrp'             => $mrp,
                    'last_price_updated_date' => $now,

                    // ---- Extra & Policies ----
                    'user_id'              => 1,
                    'vendor_id'            => 1,
                    'stock_manage'         => $stockManage,
                    'stock_quantity'       => $productStock,   // ✅ Sum of variations
                    'pack_size'            => 1,
                    'warranty_available'   => 0,
                    'return_available'     => 1,
                    'allow_discount'       => 1,
                    'max_discount_percent' => 10.00,
                    'min_sale_price'       => round($sellPrice * 0.8, 2),

                    // ---- Tax & VAT ----
                    'tax_type'     => 'exclusive',
                    'tax_rate'     => 5.00,
                    'tax_category' => 'Standard 5%',
                    'hs_code'      => '8481.80.00',

                    // ---- Commission ----
                    'vendor_commission_type'       => 'percentage',
                    'vendor_commission_value'      => 10.00,
                    'salesperson_commission_type'  => 'percentage',
                    'salesperson_commission_value' => 2.00,

                    // ---- Shipping ----
                    'weight'     => rand(100, 2000) / 100,
                    'length'     => rand(5, 30),
                    'width'      => rand(5, 30),
                    'height'     => rand(5, 30),
                    'sort_order' => 0,

                    // ---- Analytics ----
                    'total_sold'      => $totalSold,
                    'total_purchased' => $totalPurchased,
                    'views_count'     => rand(100, 5000),
                    'avg_rating'      => rand(30, 50) / 10,
                    'review_count'    => rand(0, 100),
                    'last_sale_at'    => $lastSaleAt,
                    'last_purchase_at' => $lastPurchase,

                    // ---- SEO ----
                    'meta_title'       => $productName,
                    'meta_description' => 'Buy ' . $productName . ' at best price.',
                    'meta_keywords'    => 'sanitary, bathroom, ' . strtolower($item['name']),
                ]);

                // ---------------------------------------------
                // 3.6 INSERT VARIATIONS
                // ---------------------------------------------
                foreach ($variationsData as $v) {
                    $varAttrJson = json_encode($v['attribute']);
                    $varSell     = $v['sell_price'];

                    DB::table('variations')->insert([
                        'product_id'      => $productId,
                        'name'            => $v['name'],
                        'attribute'       => $varAttrJson,
                        'attribute_hash'  => md5($varAttrJson),
                        'slug'            => Str::slug($productName . ' ' . $v['name']) . '-' . $i,
                        'sub_sku'         => $sku . '-' . $v['sort_order'],
                        'barcode'         => $barcode . $v['sort_order'],
                        'mpn'             => 'MPN-' . strtoupper(Str::random(6)),
                        'custom_code'     => $customCd . '-' . $v['sort_order'],

                        // Pricing
                        'purchase_price'           => round($varSell * 0.7, 2),
                        'sell_price'               => $varSell,
                        'mrp'                      => $mrp,
                        'retail_price'             => $varSell,
                        'dealer_price'             => round($varSell * 0.85, 2),
                        'wholesale_price'          => round($varSell * 0.9, 2),
                        'vendor_purchase_price'    => round($varSell * 0.65, 2),
                        'vendor_base_price'        => round($varSell * 0.7, 2),
                        'vendor_wholesale_price'   => round($varSell * 0.8, 2),
                        'vendor_mrp'               => $mrp,
                        'last_price_updated_date'  => $now,

                        // Media
                        'image'      => $item['image'],
                        'image_size' => 1024,

                        // Flags
                        'is_single_type'                => $v['is_default'] ? 1 : 0,
                        'is_default_selected_variation' => $v['is_default'] ? 1 : 0,
                        'status'                        => 1,

                        // Return/Discount
                        'return_available'     => 1,
                        'allow_discount'       => 1,
                        'max_discount_percent' => 10.00,
                        'min_sale_price'       => round($varSell * 0.8, 2),

                        // Tax
                        'tax_type'     => 'exclusive',
                        'tax_rate'     => 5.00,
                        'tax_category' => 'Standard 5%',
                        'hs_code'      => '8481.80.00',

                        // Stock
                        'stock_quantity' => $v['stock'],
                        'stock_reserved' => 0,
                        'stock_alert'    => 5.00,
                        'track_batch'    => $trackBatch,
                        'track_expiry'   => $trackExpiry,
                        'track_serial'   => $trackSerial,

                        // Shipping
                        'weight'     => rand(100, 500) / 100,
                        'length'     => 10,
                        'width'      => 10,
                        'height'     => 10,
                        'sort_order' => $v['sort_order'],

                        // Vendor & Analytics
                        'vendor_id'   => 1,
                        'total_sold'  => 0,

                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                // Progress display
                if ($i % 50 == 0 || $i == $total) {
                    $this->command->info("   ⏳ Progress: {$i}/{$total} products");
                }
            }

            DB::commit();
            $this->command->info("✅ {$total} sanitary products + variations seeded successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Seeder failed: ' . $e->getMessage());
            $this->command->error('   Rolled back — no partial data inserted.');
            throw $e;
        }
    }

    /**
     * Safely pluck IDs from a table (returns [] if table missing/empty)
     */
    private function safePluck(string $table, ?string $whereCol = null, $whereVal = null): array
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
                return [];
            }
            $q = DB::table($table);
            if ($whereCol) $q->where($whereCol, $whereVal);
            return $q->pluck('id')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Safely fetch attributes (handles `valus` typo)
     */
    private function safeAttributes()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('variant_attributes')) {
                return collect();
            }
            return DB::table('variant_attributes')->where('status', 1)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
