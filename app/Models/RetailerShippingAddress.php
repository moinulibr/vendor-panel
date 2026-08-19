<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerShippingAddress extends Model
{
    protected $fillable = [
        'retailer_id',
        'title',
        'contact_person',
        'contact_mobile',
        'address',
        'area',
        'division',
        'district',
        'upazila',
        'division_id',
        'district_id',
        'upazila_id',
        "deleted_at",
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
