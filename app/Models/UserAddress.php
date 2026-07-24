<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $guarded=['id'];
    
    public function upazila(){

        return $this->belongsTo(Upazila::class,'upazila_id');
    }
    
    public function district(){

        return $this->belongsTo(District::class,'district_id');
    }
    
}
