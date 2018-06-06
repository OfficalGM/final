<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
class Flowchart extends Model
{
    //
    public $timestamps = false;
    public function orderlist(){
        return $this->hasMany('App\Http\Model\Orderlist');
}
    
}
