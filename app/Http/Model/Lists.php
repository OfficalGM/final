<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    //
    public $table='lists';
    public $timestamps = false;
    public function food(){
        return $this->belongsTo('App\Http\Model\Foodlist');
    }
    
}
