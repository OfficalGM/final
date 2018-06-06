<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Exception;
use App\Http\Requests;


class StoreController extends Controller
{
    //
    public function index(){
        return view('shop_index');
    }
    public function goods_management(){
        return view('shop_management');
    }
    public function goods_update(){
        return view('shop_edit');
    }
    public function talk(){
        return view("talk2");
    }
    public function setting(){
        return view('shop_setting');
    }
    public function check(){
        return view('shop_check');
    }
    public function detailcheck($id){
        return view('shop_detail',['id'=>$id]);
    }
    public function shop_revenue_details(){
        return view('shop_revenue_details');
    }
    public function notic(){
        return view('shop_notic');
    }
    public function map(){
        return view('shop_map');
    }
    public function talk2(){
        return view('talk4');
    }
}
