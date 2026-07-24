<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded=[];

    public function category(){

        return $this->belongsTo(Category::class);
    }

    public function brand(){

        return $this->belongsTo(Brand::class);
    }

    public function subcategory(){

        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function unit(){

        return $this->belongsTo(Unit::class);
    }
    
    public function user(){

        return $this->belongsTo(User::class);
    }

    public function variations(){

        return $this->hasMany(Variation::class);
    }
    
    public function stocks(){

        return $this->hasMany(ProductStock::class,'product_id');
    }
    

    public function sell_lines()
    {
        return $this->hasMany(TransactionLine::class, 'product_id');
    }
    
    public function images() {

        return $this->hasMany(ProductImage::class);
    }
    
    

}
