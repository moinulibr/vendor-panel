<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded=[];
    
    public function pdistrict(){

        return $this->belongsTo(District::class,'p_district');
    }
    
    public function sdistrict(){

        return $this->belongsTo(District::class,'s_district');
    }
    
    public function vendor(){

        return $this->belongsTo(User::class,'user_id');
    }
    
    
    public function pthana(){

        return $this->belongsTo(Upazila::class,'p_upazila');
    }
    
    public function sthana(){

        return $this->belongsTo(Upazila::class,'s_upazila');
    }
    
    public function contact_address(){

        return $this->hasMany(UserAddress::class, 'contact_id')->latest();
    }
    
    
    public function transactions(){

        return $this->hasMany(Transaction::class, 'contact_id');
    }
    
    
    
    
}
