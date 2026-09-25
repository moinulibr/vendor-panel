<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_code',
        'coupon_id',
        'discount_amount',
        'coupon_discount_amount',
        'coupon_discount_type',
        'discount_type',
        'cart_from',
        'created_by',
        "session_id",
        "guest_token",
        "sub_total",
        "shipping_charge",
        "tax_amount",
        "final_amount",
        "expire_at",
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
