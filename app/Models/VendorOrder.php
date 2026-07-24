<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    protected $guarded=['id'];
    
    public function user(){

        return $this->belongsTo(User::class,'vendor_id');
    }

    public function transaction(){

        return $this->belongsTo(Transaction::class);
    }

    public function lines(){

        return $this->hasMany(TransactionLine::class,'vendor_order_id');
    }
    
    public function payments(){

        return $this->hasMany(TransactionPayment::class,'vendor_order_id');
    }
    
    


}
