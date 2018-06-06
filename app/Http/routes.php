<?php

Route::get('/', function () {
    return view('index');
});
Route::get('/login',function(){
    return view('login');
});
Route::get('/login2',function(){
    return view('login2');
});
#瀏覽店家
Route::get('/stores/{locate}',function($locate){
    return view('stores',['locate'=>$locate]);
});
Route::get('/shop/{id}',function($shopId){
    return view('stores2',['shop'=>$shopId]);
});
Route::get('/checkout',function(){
    return view('checkout');
});
Route::group(['prefix' => 'store/admin'],function(){
    Route::get('/','StoreController@notic');
    Route::get('/goods_management','StoreController@goods_management');
    Route::get('goods_update/{id}','StoreController@goods_update');
    Route::get('/setting','StoreController@setting');
    Route::get('/talk','StoreController@talk');
   Route::get('/talk/{id}','StoreController@talk2');
    Route::get('/check','StoreController@check');
    Route::get('check/{id}','StoreController@detailcheck');
    Route::get('/shop_revenue_details','StoreController@shop_revenue_details');
    Route::get('/update','StoreController@index');
    Route::get('googleMap','StoreController@map');
});
Route::group(['prefix' => 'member/admin'],function(){
    Route::get('/','MemberController@index');#通知
    Route::get('/setting','MemberController@setting');
    Route::get('/talk','MemberController@talk');
    Route::get('/talk/{id}','MemberController@talk2');
    Route::get('/googleMap','MemberController@map');#訂單ＧＯＯＧＬＥ
    Route::get('/check','MemberController@check');#訂單瀏覽
    Route::get('/check/{id}','MemberController@detailcheck');#詳細資訊
});
Route::group(['prefix'=>'manger'],function(){
    Route::get('/','ManagerController@index');
    Route::get('/login','ManagerController@login');
    Route::get('/shop','ManagerController@shop');
    Route::get('/check','ManagerController@check');
    Route::get('/detail/{id}','ManagerController@detail');
    Route::get('setting','ManagerController@setting');
});
#會員api＋登入註冊+ 設定
Route::group(['prefix' => 'rest/api'], function () {
    Route::post('/login','AdminController@login');
    Route::post('/register','AdminController@register');
    Route::post('/shop_login','AdminController@storelogin');#except店登入
    Route::post('/setting','AdminController@setting');
    Route::post('/setting/shop','AdminController@shop');
    Route::post('/setting/recommend','AdminController@recommend');
    Route::post('/setting/disturb','AdminController@disturb');
    Route::get('/check','AdminController@check');#得到該會員訂單
    Route::post('/notic/{id}','AdminController@notic');
    Route::post('check/map','AdminController@map');#map api
    Route::post('/chat','AdminController@chat');#文字聊天訊息
    Route::get('/getshop','AdminController@getshop');#店家
    Route::get('/addshop_name','AdminController@addshop_name');
    Route::post('/get_chat','AdminController@get_chat');#得到詳細訊息
});
 #shop api
Route::group(['prefix'=>'rest/api/shop'],function(){
    Route::get('/get_all','ShopController@get_all');# 商店全部
    Route::post('upload','ShopController@upload');#上架
    Route::post('goods_update','ShopController@goods_update'); # 更新
    Route::get('goods','ShopController@get_goods');#得到貨物
    Route::get('goods_one','ShopController@goods_one');#貨物得到 only_one
    Route::get('setting_time','ShopController@setting_time');
    Route::get('setting_name','ShopController@setting_name');
    Route::get('setting','ShopController@setting');
    Route::post('get_ShopAll_goods','ShopController@get_ShopAll_goods');
    Route::delete('goods_delete','ShopController@goods_delete');#刪除貨物
    Route::get('/check','ShopController@check');
    Route::post('/notic/{id}','ShopController@notic');
    Route::get('/getuser','ShopController@getuser');#顧客
    Route::post('/chat','ShopController@chat');
    Route::post('/get_chat','ShopController@get_chat');
});
Route::group(['prefix'=>'rest/api/buy'],function(){
    Route::post('/get_goods','BuyController@get_goods');//結帳_得到物品
    Route::post('/checkout','BuyController@checkout'); //結帳2
    Route::get('/detail','BuyController@detail');# 顧客細節
    Route::post('/checkdetial','BuyController@checkdetial');#顧客細節2
    Route::get('/shop_detial','BuyController@shop_detial');#店家細節
    Route::patch('/shop_check/{order}/{id}','BuyController@shop_check');#店家確認
    Route::get('/revenue_details/{shop_id}','BuyController@revenue_details');
    Route::get('/revenue_dashboard/{shop_id}','BuyController@revenue_dashboard');
    Route::patch('/return_good/{order}','BuyController@return_good');
    Route::patch('/return_good2/{order}','BuyController@return_good');
});
// 課服 api
Route::group(['prefix'=>'rest/api/manger'],function(){
    Route::post('/reg','MController@reg');
    Route::post('/login','MController@login');
    Route::get('/user','MController@get_user');
    Route::patch('/ban/{id}/{n}','MController@ban');
    Route::get('/shop','MController@get_shop');
    Route::patch('/ban_shop/{id}/{n}','Mcontroller@ban_shop');
    Route::get('/check','MController@check');
    Route::get('/detail','MController@detail');
    Route::get('setting','MController@setting');
    Route::post('update','MController@update');
    Route::post('del','MController@del');
});


