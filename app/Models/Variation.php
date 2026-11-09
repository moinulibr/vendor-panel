<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ==========================================
    // NEW RELATIONSHIPS
    // ==========================================

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
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

    // ==========================================
    // EXISTING RELATIONSHIPS
    // ==========================================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /** @deprecated Use batches() instead */
    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'variation_id');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : ($this->product ? $this->product->image_url : null);
    }
}
