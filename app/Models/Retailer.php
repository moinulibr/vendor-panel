<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'trade_license',
        'address',
        'area',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}