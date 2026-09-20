<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'user_detail_id',
        'type',
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
        'status'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userDetail()
    {
        return $this->belongsTo(UserDetail::class);
    }
}
