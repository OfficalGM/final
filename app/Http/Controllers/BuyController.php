<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Exception;
use App\Http\Model\Foodlist;
use App\Http\Model\Shop;
use App\Http\Model\Orderlist;
use App\Http\Model\Flowchart;
use App\Http\Model\User;
use App\Http\Model\Lists;

class BuyController extends Controller
{
    public function getTime(){
        date_default_timezone_set('Asia/Taipei');
        return date("Y-m-d H:i:s");
    }
    public function getTime2(){
        date_default_timezone_set('Asia/Taipei');
        return date("Y");
    }
    public function get_shop_name($email){
        $shop=new Shop;
        $id=$shop->where('email',$email)->first()->id;
        return $id;
    }
    public function getUser($email){
        $user=new User;
        $user=$user::where('email',$email)->first();
        return $user['id'];
    }
    public function detail(Request $request,Flowchart $flow,Lists $lists){
        $id=$request['orderlist'];
        
        try{
            $flow=$flow::where('id',$id);
            foreach($flow->get() as $o){
                $data[]=array('one'=>$o->flowchart_set,'two'=>$o->flowchart_make,'three'=>$o->flowchart_way,'four'=>$o->flowchart_done,
                'time1'=>$o->time_set,'time2'=>$o->time_make,'time3'=>$o->time_way,'time4'=>$o->time_done);
            }
            $or=new OrderList;
            $or=$or::where('id',$id);
            foreach($or->get() as $o){
                $order[]=array('total_money'=>$o->total_money,'service'=>$o->reserve,'time'=>$o->time);
            }
            //echo $id;
            $lists=$lists::where('orderlists_id',$id)->get();
            for($i=0;$i<count($lists);$i++){
                $name=$this->get_foodName($lists[$i]->food_id);
                $ll[]=array('food'=>$name,'amount'=>$lists[$i]->amount,'money'=>$lists[$i]->money);
            }  
            $shop=new Shop;
            $shop=$shop::where('id',$request['shop_id'])->get();
            foreach($shop as $s){
                $shops[]=array('email'=>$s->email,'addr'=>$s->address);
            }
            
        }catch(\Exception $e){
            //echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1','data'=>$data,'order'=>$order,'food'=>$ll,'shop'=>$shops]);
    }
    public function get_foodName($id){
        $foodlist=new Foodlist;
        $f=foodlist::where('food_id',$id)->first();
        return $f['food'];
    }
    public function get_goods(Request $request,Foodlist $food,Shop $shop){
        $all=$request['Shop'];
        try{
            for($i=0;$i<count($all);$i++){
                $ans=$food::where("food_id",$all[$i])->first();
                $data[]=array("food_id"=>$ans->food_id,"amount"=>$ans->amount,"food"=>$ans->food,"money"=>$ans->money);
                $s=$ans->shop_id;
            }
            $time=$shop::find($s)->first();
            $reserve[]=array('morning'=>$time['moring'],'afternoon'=>$time['afternoon'],'night'=>$time['night'],'midnight'=>$time['midnight']);     
        }catch(\Exception $e){
            #echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1','data'=>$data,'time'=>$reserve]);
    }
    public function checkout(Request $request,Orderlist $order,Foodlist $foodlist){
        #service 0=>內用 1=>外送  2=>外帶
        
        try{
            $shop=$request['shop'];
            $goods=$request['goods'];
            $user=$request['user'];
            $service=$request['service'];
            $reserve_time=$request['reserve_time'];
            $money=$request['money'];
            $flow=new Flowchart;
            $flow->flowchart_set=1;
            $flow->flowchart_make=0;
            $flow->flowchart_way=0;
            $flow->flowchart_done=0;
            $flow->exception="-1";
            $flow->time_set=$this->getTime();
            
            $flow->save();
            $order->user_id=$this->getUser($request['user']);
            $order->shop_id=$shop;
            $order->total_money=$money;
            $order->reserve=$service;
            $order->time=$reserve_time;
            
            $order->flowchart_id=$flow['id'];
            $order->save();
            for($i=0;$i<count($goods);$i++){
                $lists=new Lists;
                $lists->orderlists_id=$order['id'];
                $lists->amount=$goods[$i]['amount'];
                $lists->food_id=$goods[$i]['shopcart'];
                $foodlist=$foodlist::where('food_id',$goods[$i]['shopcart'])->first();
                $lists->money=$foodlist['money']*$goods[$i]['amount'];
                $lists->save();
            }
        }catch(\Exception $e){
            
            return response()->json(['success' => '0']);
        }
        
       
        return response()->json(['success' => '1']);
    }
    public function checkdetial(Request $request){
        $id=$request['orderlist'];
        $shop=$request['shop_id'];
        try{
            $order=new Orderlist;
            $o=$order::where(['id'=>$id,'shop_id'=>$shop])->first();
            $flow=new Flowchart;
            $time=$this->getTime();
            $flow->where('id',$o->flowchart_id)->update(['flowchart_done'=>'1','time_done'=>$time]);
            
        }catch(\Exception $e){
            
            return response()->json(['success' => '0']);
        }
        $ans=$this->goods_done($id);
        return response()->json(['success'=>'1']);
    }
    public function shop_detial(Request $request,Flowchart $flow,Lists $lists){
        $id=$request['orderlist'];
        try{
            $flow=$flow::where('id',$id);
            
            foreach($flow->get() as $o){
                $data[]=array('one'=>$o->flowchart_set,'two'=>$o->flowchart_make,'three'=>$o->flowchart_way,'four'=>$o->flowchart_done,
                'time1'=>$o->time_set,'time2'=>$o->time_make,'time3'=>$o->time_way,'time4'=>$o->time_done);
            }
            $or=new OrderList;
            $or=$or::where('id',$id);
            foreach($or->get() as $o){
                $order[]=array('total_money'=>$o->total_money,'service'=>$o->reserve,'time'=>$o->time);
            }
            
            $lists=$lists::where('orderlists_id',$id)->get();
            for($i=0;$i<count($lists);$i++){
                $name=$this->get_foodName($lists[$i]->food_id);
                $ll[]=array('food'=>$name,'amount'=>$lists[$i]->amount,'money'=>$lists[$i]->money);
            }
             $user=new User;
             $user=$user::where('id',$request['user_id'])->get();
             foreach($user as $s){
                 $us[]=array('email'=>$s->email,'addr'=>$s->address);
             }
        }catch(\Exception $e){
            //echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1','data'=>$data,'order'=>$order,'food'=>$ll,'user'=>$us]);
    }
    public function shop_check($check,$order){
        
        try{
            if($check==1){
                $flow=new Flowchart;
                $time=$this->getTime();
                $flow=$flow::where('id',$order)->update(['flowchart_make'=>'1','time_make'=>$time]);
            }
            if($check==2){
                $flow=new Flowchart;
                $time=$this->getTime();
                $flow->where('id',$order)->update(['flowchart_way'=>'1','time_way'=>$time]);
            }
            
        }catch(\Exception $e){
            //echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1']);
    }
    public function goods_done($id){
        $lists=new Lists;
        $food=new Foodlist;
        $lists=$lists::where('orderlists_id',$id)->get();
        foreach($lists as $l){
            $am=$l->amount;
            $f=$food->where('food_id',$l->food_id);
            foreach($f->get()as $a){
                $c=$a->amount;
                $b=$c-$am; 
            }
            $f=$f->update(['amount'=>$b]);
        }
        return $f;
    }
    public function revenue_details($shop_id,Orderlist $order){
        $f=new Flowchart;
        $id=$this->get_shop_name($shop_id);
        $o=$order::where('shop_id',$id)->get();
        
        foreach($o as $s){
            $a=$f->where('id',$s->id)->first();
            $data[]=array('id'=>$s->id,'money'=>$s->total_money,'time'=>$a->time_done);
        }
        
        return response()->json(['success'=>'1','data'=>$data]);
    }
    public function revenue_dashboard($shop_id,Orderlist $order,Lists $lists,Foodlist $f){
        $id=$this->get_shop_name($shop_id);
        $time=$this->getTime2();
        $total=[];
        try{
            for($i=1;$i<10;$i++){
                $j='0'.$i;
                $o=$order::where('shop_id',$id)->where('created_at','like','%'.$time.'-'.$j.'%')->sum('total_money');
                if($o==null ||$o==""){
                    $o=0;
                }
                array_push($total,$o);
            }
            for($i=10;$i<=12;$i++){
                $o=$order::where('shop_id',$id)->where('created_at','like','%'.$time.'-'.$i.'%')->sum('total_money');
                if($o==null ||$o==""){
                    $o=0;
                }
                array_push($total,(int)$o);
            }
            $total2=[];
            $l=$f::where('shop_id',$id)->get();
            foreach($l as $s){
                $ll=$lists::where('food_id',$s->food_id)->sum('amount');
                array_push($total2,[$s->food,(int)$ll]);
            }
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$total,'list'=>$total2]);
    }
    public function return_good($order){
        try{
            $flow=new Flowchart;
            $time=$this->getTime();
            $flow=$flow::where('id',$order)->update(['exception'=>"退貨,店家申請"]);
            return response()->json(['success'=>'1']);
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1']);
    }
    public function return_good2($order){
        try{
            $flow=new Flowchart;
            $time=$this->getTime();
            $flow=$flow::where('id',$order)->update(['exception'=>"退貨,顧客申請"]);
            return response()->json(['success'=>'1']);
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1']);
    }
}
