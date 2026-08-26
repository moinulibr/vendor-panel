<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'product_id',
        'variation_id',
        'type',
        'created_by', 
        'favorite_from'
        ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
