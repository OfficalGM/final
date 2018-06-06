<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    public function foodlist(){
        return $this->hasMany('App\Http\Model\Foodlist');
    }
}
