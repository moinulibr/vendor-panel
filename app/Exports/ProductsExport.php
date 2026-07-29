<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // উদাহরণ হিসেবে ৫টি প্রোডাক্ট পাঠাতে পারেন বা খালি রাখতে পারেন
        return Product::with(['variations'])->latest()->take(10)->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'name_bangla',
            'category_id',
            'user_id',
            'sub_category_id',
            'brand_id',
            'unit_id',
            'status',
            'sku',
            'type',
            'description',
            'stock_alert',
            'is_ecom',
            'is_feature',
            'is_reco',
            'purchase_price',
            'sell_price',
            //'wholesale_price',
            'warranty_note',
            'return_note',
            'specification',
            'return_days',
            'warranty_days',
            'estimate_delivery_day',
            'stock_manage',
            'warranty_available',
            'return_available',
            'image', // Image URL or filename from ZIP
            'images', // Comma-separated gallery image URLs
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->name_bangla,
            $product->category_id,
            $product->user_id,
            $product->sub_category_id,
            $product->brand_id,
            $product->unit_id,
            $product->status ?? 1,
            $product->sku,
            $product->type ?? 'single',
            $product->description,
            $product->stock_alert,
            $product->is_ecom ?? 1,
            $product->is_feature ?? 0,
            $product->is_reco ?? 0,
            $product->purchase_price,
            $product->sell_price,
            //$product->wholesale_price,
            $product->warranty_note,
            $product->return_note,
            $product->specification,
            $product->return_days,
            $product->warranty_days,
            $product->estimate_delivery_day,
            $product->stock_manage,
            $product->warranty_available,
            $product->return_available,
            $product->image ? asset('uploads/products/' . $product->image) : '',
            '', // Gallery Images Format
        ];
    }
}
