<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactNextPayment extends Model
{
    
    protected $guarded=[];
    
    public function contact(){

        return $this->belongsTo(Contact::class, 'contact_id');
    }
    
}
