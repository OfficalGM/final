<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ManagerController extends Controller
{
    //
    public function index(){
        return view('m_index');
    }
    public function login(){
        return view('m_login');
    }
    public function shop(){
        return view('m_shop');
    }
    public function check(){
        return view('m_check');
    }
    public function detail($id){
        return view('m_detail',['id'=>$id]);
    }
    public function setting(){
        return view('m_setting');
    }
}
