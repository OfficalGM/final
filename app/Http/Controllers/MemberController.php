<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use \Exception;
use App\Http\Requests;
use Crypt;

class MemberController extends Controller
{
    public function index(){
        return view('admin_index');
        
    }
    public function setting(){
        return view('setting');
    }
    public function talk(){
        return view("talk");
    }

    public function check(){
        return view('check');
    }
    public function detailcheck($id){
        return view('detail_check',['id'=>$id]);
    }
    public function map(){
        return  view('google_map');
    }
    public function talk2($id){
        return view('talk3',['id',$id]);
    }
}
