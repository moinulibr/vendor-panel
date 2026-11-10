<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "cart_id",
        "product_id",
        "variation_id",
        "vendor_id",
        "product_label_snapshot",
        "variant_label_snapshot",
        "quantity",
        "unit_price",
        "sub_total",
        "final_amount",
        "discount_amount",
        "discount_type",
        "discount_id",
        "notes",
    ];

    protected $touches = ['cart']; // this will update the updated_at field on the parent model - carts table
    
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
