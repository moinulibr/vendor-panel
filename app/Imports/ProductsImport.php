<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Variation;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Utils\ProductUtil; // আপনার প্রজেক্টের Util Class

class ProductsImport implements ToModel, WithHeadingRow
{
    protected $unzippedPath;

    public function __construct($unzippedPath = null)
    {
        $this->unzippedPath = $unzippedPath;
    }

    public function model(array $row)
    {
        if (empty($row['name']) || empty($row['category_id']) || empty($row['user_id'])) {
            return null; // Skip invalid rows
        }

        // 1. Image handling (URL download or ZIP Temp folder)
        $mainImageName = null;
        $mainImageSize = 0;

        if (!empty($row['image'])) {
            $imgData = $this->processImage($row['image']);
            if ($imgData) {
                $mainImageName = $imgData['name'];
                $mainImageSize = $imgData['size'];
            }
        }

        // 2. Slug logic (Same as your update method)
        $slug = Str::slug($row['name']);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        $finalSlug = $count ? "{$slug}-{$count}" : $slug;

        // 3. Product Create
        $product = Product::create([
            'name'                  => $row['name'],
            'name_bangla'           => $row['name_bangla'] ?? null,
            'category_id'           => $row['category_id'],
            'user_id'               => $row['user_id'],
            'sub_category_id'       => $row['sub_category_id'] ?? null,
            'brand_id'              => $row['brand_id'] ?? null,
            'unit_id'               => $row['unit_id'] ?? null,
            'status'                => $row['status'] ?? 1,
            'sku'                   => $row['sku'] ?? Str::random(8),
            'type'                  => $row['type'] ?? 'single',
            'description'           => $row['description'] ?? null,
            'stock_alert'           => $row['stock_alert'] ?? 0,
            'is_ecom'               => $row['is_ecom'] ?? 1,
            'is_feature'            => $row['is_feature'] ?? 0,
            'is_reco'               => $row['is_reco'] ?? 0,
            'purchase_price'        => $row['purchase_price'] ?? 0,
            'sell_price'            => $row['sell_price'] ?? 0,
            'wholesale_price'       => $row['wholesale_price'] ?? 0,
            'warranty_note'         => $row['warranty_note'] ?? null,
            'return_note'           => $row['return_note'] ?? null,
            'specification'         => $row['specification'] ?? null,
            'return_days'           => $row['return_days'] ?? null,
            'warranty_days'         => $row['warranty_days'] ?? null,
            'estimate_delivery_day' => $row['estimate_delivery_day'] ?? null,
            'stock_manage'          => isset($row['stock_manage']) && $row['stock_manage'] == 1 ? 1 : null,
            'warranty_available'    => isset($row['warranty_available']) && $row['warranty_available'] == 1 ? 1 : null,
            'return_available'      => isset($row['return_available']) && $row['return_available'] == 1 ? 1 : null,
            'is_new'                => 0,
            'image'                 => $mainImageName,
            'image_size'            => $mainImageSize,
            'slug'                  => $finalSlug,
        ]);

        // 4. Variations Logic (Matching your controller logic)
        $variations = [];
        if (($row['type'] ?? 'single') == 'variable') {
            // If variations JSON/Array string provided in excel
            if (!empty($row['variants'])) {
                $variations = json_decode($row['variants'], true) ?? [];
            }
        } else {
            // Dummy variation for single product
            $variations[] = [
                'name'           => 'dummy',
                'sub_sku'        => $product->sku . '-1',
                'purchase_price' => $product->purchase_price,
                'sell_price'     => $product->sell_price,
            ];
        }

        foreach ($variations as $variation) {
            $vname = $variation['name'];
            unset($variation['name']);
            Variation::create(array_merge([
                'product_id' => $product->id,
                'name'       => $vname
            ], $variation));
        }

        // 5. Gallery Images Logic (Comma-separated URL or filenames in excel)
        if (!empty($row['images'])) {
            $galleryList = explode(',', $row['images']);
            foreach ($galleryList as $gImg) {
                $gImg = trim($gImg);
                $gData = $this->processImage($gImg);
                if ($gData) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $gData['name'],
                        'image_size' => $gData['size'],
                    ]);
                }
            }
        }

        return $product;
    }

    private function processImage($fileNameOrUrl)
    {
        $destinationPath = public_path('/products');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Scenario A: Image is a URL
        if (filter_var($fileNameOrUrl, FILTER_VALIDATE_URL)) {
            $imageContent = @file_get_contents($fileNameOrUrl);
            if ($imageContent) {
                $name = time() . '_' . uniqid() . '.jpg';
                file_put_contents($destinationPath . '/' . $name, $imageContent);
                return ['name' => $name, 'size' => strlen($imageContent)];
            }
        }

        // Scenario B: Image in Unzipped Temp Folder
        if ($this->unzippedPath && file_exists($this->unzippedPath . '/' . $fileNameOrUrl)) {
            $filePath = $this->unzippedPath . '/' . $fileNameOrUrl;
            $name = time() . '_' . uniqid() . '_' . $fileNameOrUrl;
            copy($filePath, $destinationPath . '/' . $name);
            return ['name' => $name, 'size' => filesize($filePath)];
        }

        return null;
    }
}