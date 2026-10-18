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
        $this->command->info('Fetching Brands, Categories, and Attributes...');

        // 1. Fetch Real IDs from Database
        $brandIds = DB::table('brands')->where('status', 1)->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->where('status', 1)->pluck('id')->toArray();

        // Fetch attributes (Color, Size, etc.)
        $dbAttributes = DB::table('variant_attributes')->where('status', 1)->get();

        // Fallbacks if tables are empty (to prevent seeding failure)
        if (empty($brandIds)) $brandIds = [1];
        if (empty($categoryIds)) $categoryIds = [1];
        if ($dbAttributes->isEmpty()) {
            // Fallback attribute if table is empty
            $dbAttributes = collect([
                (object) ['id' => 1, 'name' => 'Color', 'valus' => 'Red,Blue,Green']
            ]);
        }

        // Parse attributes into a usable array format
        $parsedAttributes = [];
        foreach ($dbAttributes as $attr) {
            $values = $attr->valus ?? ($attr->values ?? 'Default');
            $parsedAttributes[] = [
                'id' => $attr->id,
                'name' => $attr->name,
                'values' => array_map('trim', explode(',', $values))
            ];
        }

        $this->command->info('Seeding 200 Products using Real Data...');

        // Sanitary Products Data Pool (Aligned with your categories)
        $sanitaryItems = [
            ['name' => 'Wall Tap', 'bn' => 'ওয়াল ট্যাপ', 'image' => 'https://images.unsplash.com/photo-1584820927498-cafe3c1579c1?w=500'],
            ['name' => 'Towel Hanger', 'bn' => 'তোয়ালে হ্যাঙ্গার', 'image' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?w=500'],
            ['name' => 'Soap Case', 'bn' => 'সোপ কেস', 'image' => 'https://images.unsplash.com/photo-1585837575652-267c041d77d4?w=500'],
            ['name' => 'Commode', 'bn' => 'কমোড', 'image' => 'https://images.unsplash.com/photo-1585671963294-6e1a4d4779ed?w=500'],
            ['name' => 'Toilet Paper Holder', 'bn' => 'টয়লেট পেপার হোল্ডার', 'image' => 'https://images.unsplash.com/photo-1584556812952-905ffd0c611a?w=500'],
            ['name' => 'Hand Shower', 'bn' => 'হ্যান্ড শাওয়ার', 'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500'],
            ['name' => 'Water Tank', 'bn' => 'ওয়াটার ট্যাংক', 'image' => 'https://images.unsplash.com/photo-1600857544200-b2f666a9a2ec?w=500'],
            ['name' => 'Mirror', 'bn' => 'আয়না', 'image' => 'https://images.unsplash.com/photo-1585670210634-f7b7d238588a?w=500'],
            ['name' => 'Basin', 'bn' => 'বেসিন', 'image' => 'https://images.unsplash.com/photo-1556940623-902ee19d487b?w=500'],
            ['name' => 'Shower', 'bn' => 'শাওয়ার', 'image' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=500'],
            ['name' => 'Kitchen Sink', 'bn' => 'কিচেন সিঙ্ক', 'image' => 'https://images.unsplash.com/photo-1608528577891-eb055944f2e7?w=500'],
            ['name' => 'Bathroom Shelf', 'bn' => 'বাথরুম শেলফ', 'image' => 'https://images.unsplash.com/photo-1586942543784-eecaa23dfe8b?w=500'],
        ];

        $now = Carbon::now();

        for ($i = 1; $i <= 200; $i++) {
            // 1. Determine Product Type Ratio
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

            // 2. Base Data Preparation
            $item = $sanitaryItems[array_rand($sanitaryItems)];
            $productName = $item['name'] . ' - ' . Str::random(4);
            $banglaName = $item['bn'] . ' - ' . Str::random(4);
            $sku = 'SKU-' . strtoupper(Str::random(8));
            $slug = Str::slug($productName) . '-' . time() . rand(10, 99);
            $sellPrice = rand(10000, 500000) / 100; // 100.00 to 5000.00
            $mrp = $sellPrice + (rand(1000, 5000) / 100);

            // Pick random Real IDs
            $brandId = $brandIds[array_rand($brandIds)];
            $categoryId = $categoryIds[array_rand($categoryIds)];

            // Stock settings
            $trackStock = $isService ? false : true;
            $stockManage = $isService ? false : true;
            $stockQuantity = $isService ? 0 : rand(10, 500);

            // 3. Insert Product
            $productId = DB::table('products')->insertGetId([
                // Basic Info
                'name' => $productName,
                'invoice_name' => $productName,
                'name_bangla' => $banglaName,
                'invoice_name_bangla' => $banglaName,
                'image' => $item['image'],
                'slug' => $slug,
                'sku' => $sku,
                'barcode' => rand(100000000000, 999999999999),
                'mpn' => 'MPN-' . strtoupper(Str::random(6)),
                'custom_code' => 'CST-' . rand(1000, 9999),
                'description' => "High-quality {$item['name']} for daily use.",
                'specification' => "Material: Premium quality\nOrigin: Bangladesh",
                'tags' => implode(',', [strtolower($item['name']), 'sanitary', 'bathroom']),

                // Type & Behavior
                'type' => $legacyType,
                'product_type' => $productType,
                'has_variants' => $hasVariants,
                'is_combo' => $isCombo,
                'is_service' => $isService,
                'is_digital' => false,
                'track_stock' => $trackStock,
                'track_batch' => true,
                'track_expiry' => true,
                'track_serial' => false,
                'is_purchasable' => true,
                'is_sellable' => true,

                // Relations (Using Real IDs)
                'stock_alert' => 10.00,
                'brand_id' => $brandId,
                'unit_id' => 1, // Assuming unit 1 exists (pcs)
                'category_id' => $categoryId,
                'sub_category_id' => null,
                'category_path' => 'Sanitary > ' . ucfirst($productType),

                // Status
                'status' => 1,
                'is_ecom' => 1,
                'is_mobile_app' => 1,
                'is_feature' => rand(0, 1),
                'is_reco' => rand(0, 1),
                'is_new' => 0,

                // Audit
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 1,

                // Pricing
                'purchase_price' => $sellPrice * 0.7,
                'sell_price' => $sellPrice,
                'min_price' => $sellPrice,
                'max_price' => $sellPrice,
                'wholesale_price' => $sellPrice * 0.9,
                'retail_price' => $sellPrice,
                'dealer_price' => $sellPrice * 0.85,
                'mrp' => $mrp,
                'vendor_purchase_price' => $sellPrice * 0.65,
                'vendor_base_price' => $sellPrice * 0.7,
                'vendor_wholesale_price' => $sellPrice * 0.8,
                'vendor_mrp' => $mrp,
                'last_price_updated_date' => $now,

                // Extra & Policies
                'user_id' => 1,
                'vendor_id' => 1,
                'stock_manage' => $stockManage,
                'stock_quantity' => $stockQuantity,
                'pack_size' => 1,
                'warranty_available' => 0,
                'return_available' => 1,
                'allow_discount' => 1,
                'max_discount_percent' => 10.00,
                'min_sale_price' => $sellPrice * 0.8,

                // Tax & VAT
                'tax_type' => 'exclusive',
                'tax_rate' => 5.00,
                'tax_category' => 'Standard 5%',
                'hs_code' => '8481.80.00',

                // Commission
                'vendor_commission_type' => 'percentage',
                'vendor_commission_value' => 10.00,
                'salesperson_commission_type' => 'percentage',
                'salesperson_commission_value' => 2.00,

                // Shipping
                'weight' => rand(100, 2000) / 100,
                'length' => rand(5, 30),
                'width' => rand(5, 30),
                'height' => rand(5, 30),
                'sort_order' => 0,

                // Analytics
                'total_sold' => rand(0, 1000),
                'views_count' => rand(100, 5000),
                'avg_rating' => rand(30, 50) / 10,
                'review_count' => rand(0, 100),

                // SEO
                'meta_title' => $productName,
                'meta_description' => 'Buy ' . $productName . ' at best price.',
                'meta_keywords' => 'sanitary, bathroom, ' . strtolower($item['name']),
            ]);

            // 4. Insert Variations
            if ($productType === 'variant') {
                // Pick a random attribute (e.g., Color, Size, Material)
                $attr = $parsedAttributes[array_rand($parsedAttributes)];

                // Pick 2 to 3 random values from that attribute
                $attrValues = $attr['values'];
                shuffle($attrValues);
                $selectedValues = array_slice($attrValues, 0, min(3, count($attrValues)));

                $v = 1;
                foreach ($selectedValues as $val) {
                    $varName = $val;
                    $attributeData = [$attr['name'] => $val];
                    $varSellPrice = $sellPrice + (rand(50, 300) / 10);

                    DB::table('variations')->insert([
                        'product_id' => $productId,
                        'name' => $varName,
                        'attribute' => json_encode($attributeData),
                        'attribute_hash' => md5(json_encode($attributeData)),
                        'slug' => Str::slug($productName . ' ' . $varName),
                        'sub_sku' => $sku . '-' . $v,
                        'barcode' => rand(100000000000, 999999999999),

                        'purchase_price' => $varSellPrice * 0.7,
                        'sell_price' => $varSellPrice,
                        'mrp' => $mrp,
                        'retail_price' => $varSellPrice,
                        'dealer_price' => $varSellPrice * 0.85,
                        'wholesale_price' => $varSellPrice * 0.9,
                        'vendor_purchase_price' => $varSellPrice * 0.65,
                        'vendor_base_price' => $varSellPrice * 0.7,
                        'vendor_wholesale_price' => $varSellPrice * 0.8,
                        'vendor_mrp' => $mrp,
                        'last_price_updated_date' => $now,

                        'image' => $item['image'],
                        'image_size' => 1024,

                        'is_single_type' => 0,
                        'is_default_selected_variation' => ($v === 1) ? 1 : 0,
                        'status' => 1,
                        'return_available' => 1,
                        'allow_discount' => 1,
                        'max_discount_percent' => 10.00,
                        'min_sale_price' => $varSellPrice * 0.8,

                        'tax_type' => 'exclusive',
                        'tax_rate' => 5.00,
                        'tax_category' => 'Standard 5%',
                        'hs_code' => '8481.80.00',

                        'stock_quantity' => rand(10, 200),
                        'stock_reserved' => 0,
                        'stock_alert' => 5.00,
                        'track_batch' => true,
                        'track_expiry' => true,
                        'track_serial' => false,

                        'weight' => rand(100, 500) / 100,
                        'length' => 10,
                        'width' => 10,
                        'height' => 10,
                        'sort_order' => $v,

                        'vendor_id' => 1,
                        'total_sold' => 0,

                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $v++;
                }
            } else {
                // For Simple, Combo, Service -> Create 1 Default Variation
                DB::table('variations')->insert([
                    'product_id' => $productId,
                    'name' => 'Default',
                    'attribute' => json_encode(['Type' => 'Default']),
                    'attribute_hash' => md5(json_encode(['Type' => 'Default'])),
                    'slug' => $slug,
                    'sub_sku' => $sku . '-DEF',
                    'barcode' => rand(100000000000, 999999999999),

                    'purchase_price' => $sellPrice * 0.7,
                    'sell_price' => $sellPrice,
                    'mrp' => $mrp,
                    'retail_price' => $sellPrice,
                    'dealer_price' => $sellPrice * 0.85,
                    'wholesale_price' => $sellPrice * 0.9,
                    'vendor_purchase_price' => $sellPrice * 0.65,
                    'vendor_base_price' => $sellPrice * 0.7,
                    'vendor_wholesale_price' => $sellPrice * 0.8,
                    'vendor_mrp' => $mrp,
                    'last_price_updated_date' => $now,

                    'image' => $item['image'],
                    'image_size' => 1024,

                    'is_single_type' => 1,
                    'is_default_selected_variation' => 1,
                    'status' => 1,
                    'return_available' => 1,
                    'allow_discount' => 1,
                    'max_discount_percent' => 10.00,
                    'min_sale_price' => $sellPrice * 0.8,

                    'tax_type' => 'exclusive',
                    'tax_rate' => 5.00,
                    'tax_category' => 'Standard 5%',
                    'hs_code' => '8481.80.00',

                    'stock_quantity' => $stockQuantity,
                    'stock_reserved' => 0,
                    'stock_alert' => 10.00,
                    'track_batch' => false,
                    'track_expiry' => false,
                    'track_serial' => false,

                    'weight' => rand(100, 1000) / 100,
                    'length' => 10,
                    'width' => 10,
                    'height' => 10,
                    'sort_order' => 0,

                    'vendor_id' => 1,
                    'total_sold' => 0,

                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($i % 50 == 0) {
                $this->command->info("Inserted {$i} products...");
            }
        }

        $this->command->info('✅ 200 Sanitary Products seeded successfully using real IDs!');
    }
}
