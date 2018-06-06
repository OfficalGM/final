<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Orderlist extends Model
{
    //
    public function flowchart(){
        return $this->belongsTo('App\Http\Model\Flowchart');
    }
    public function get_flowchart_time(){
        return $this->belongsTo('App\Http\Model\Flowchart','id');
    }
}
