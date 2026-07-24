<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded=[];
    
    protected static function booted(){
        static::addGlobalScope('active', function ($builder) {
            $builder->where('status', 1);
        });
    }

}
