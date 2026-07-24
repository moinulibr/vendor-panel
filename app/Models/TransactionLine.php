<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLine extends Model
{
    protected $guarded=[];

    public function product(){

        return $this->belongsTo(Product::class);
    }

    public function variation(){

        return $this->belongsTo(Variation::class);
    }
    
    public function discount_object(){

        return $this->belongsTo(Discount::class);
    }

}
