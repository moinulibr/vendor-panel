<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPrint extends Model
{
    protected $guarded=[];
    
    public function contact(){

        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function location(){

        return $this->belongsTo(Location::class);
    }
    


    public function lines(){

        return $this->hasMany(TransactionPrintProduct::class, 'transaction_print_id');
    }
}
