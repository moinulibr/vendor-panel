<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded=[];
    
    // protected $appends = ['cal_discount'];
    
    
    // public function getCalDiscountAttribute()
    // {
    //     $discount=$this->discount_amount??0;
    //     if ($this->discount_amount>0 && $this->discount_type=='Percentage') {
    //         $discount =  ($this->final_amount * $this->discount_amount) /100;
    //     } 
        
    //     return $discount;
    // }
    

    public function contact(){

        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function location(){

        return $this->belongsTo(Location::class)->withoutGlobalScope('active');
    }
    
    public function locationTo(){

        return $this->belongsTo(Location::class,'location_id_to')->withoutGlobalScope('active');
    }

    public function category(){

        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function unit(){

        return $this->belongsTo(Unit::class);
    }

    public function lines(){

        return $this->hasMany(TransactionLine::class, 'transaction_id');
    }

    public function vendor_orders(){

        return $this->hasMany(VendorOrder::class,'transaction_id');
    }

    public function payments(){

        return $this->hasMany(TransactionPayment::class);
    }
    

    public function user(){

        return $this->belongsTo(User::class);
    }
    
     public function order_from(){

        return $this->belongsTo(OrderFrom::class);
    }
    
    public function vendor(){

        return $this->belongsTo(User::class,'vendor_id');
    }

    public function shipping(){

        return $this->belongsTo(UserAddress::class,'shipping_id');
    }
    
    public function sell_return()
    {
        return $this->hasOne(Transaction::class, 'return_parent_id', 'id');
    }



}
