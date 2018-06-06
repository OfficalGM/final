<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Foodlist extends Model
{
    protected $primaryKey = 'food_id';
    public function shop(){
        return $this->belongsTo('App\Http\Model\Shop');
    }
    public function lists(){
        return $this->hasMany('App\Http\Model\List');
    }
}
