<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ==========================================
    // NEW RELATIONSHIPS (As per new schema)
    // ==========================================

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function defaultVendor()
    {
        return $this->belongsTo(User::class, 'default_vendor_id');
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function batchSummaries()
    {
        return $this->hasMany(BatchSummary::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /* public function purchaseItems()
    {
        return $this->hasMany(PurchaseBillItem::class);
    } */

    // ==========================================
    // EXISTING RELATIONSHIPS (Keep for backward compatibility)
    // ==========================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    /** @deprecated Use batches() or batchSummaries() for inventory instead */
    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    /** @deprecated Use saleItems() instead */
    public function sell_lines()
    {
        return $this->hasMany(TransactionLine::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->image_size;
        if (!$bytes || $bytes <= 0) return 'N/A';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1024, 1) . ' KB';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
