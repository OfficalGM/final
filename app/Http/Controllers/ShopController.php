<?php

namespace App\Http\Controllers;
use Storage;
use Illuminate\Http\Request;
use App\Http\Model\User;
use App\Http\Requests;
use \Exception;
use App\Http\Model\Shop;
use App\Http\Model\Orderlist;
use App\Http\Model\Lists;
use App\Http\Model\Flowchart;
use App\Http\Model\Foodlist;
use App\Http\Model\Chat;

class ShopController extends Controller
{
    public function Get_Shop_Id($email){
        $user=new shop;
        $user->email=$email;
        $ans=$user->where('email',$user->email)->first()->id;
        return $ans;
    }
    public function goods_update(Request $request){
        $foodlist=new foodlist;
        $food=$foodlist->where('food_id','=',$request['food_id'])->first();
        $path=$request['url'];
        #echo $path,$food->url;
        if($path==$food->url){
            try{
                $food->where('food_id',$request['food_id'])->update(['money'=>$request['money'],'amount'=>$request['amount'],
                'content'=>$request['content']]);
            }catch(\Exception $e){
                return response()->json(['success' => '0']);
            }
        }else{
            
            try{
                $img=str_replace('data:image/jpeg;base64,', '', $path);
                $img = str_replace(' ', '+', $img);
                $img = base64_decode($img);
                $path2='img_'.time().'.jpg';
                $exists = Storage::disk('local')->exists($path2);
                
                if($exists==1){
                    return response()->json(['success' => '0']);
                }
                else{
                    Storage::disk('local')->put($path2, $img);
                }
                echo $path2;
                $food->where('food_id',$request['food_id'])->update(['money'=>$request['money'],'amount'=>$request['amount'],
                'content'=>$request['content'],'url'=>$path2]);
            }catch(\Exception $e){
                
                return response()->json(['success' => '0']);
            }

        }
        return response()->json(['success' => '1']);
        
    }
    public function upload(Request $request){
        
        $id=$this->Get_Shop_Id($request['email']);
        $url=$request['url'];
        if($url!='0'){
            try{
                $img=str_replace('data:image/jpeg;base64,', '', $url);
                $img = str_replace(' ', '+', $img);
                $img = base64_decode($img);
                $path='img_'.time().'.jpg';
                $exists = Storage::disk('local')->exists($path);
                if($exists==1){
                    return response()->json(['success' => '0']);
                }
                else{
                    Storage::disk('local')->put($path, $img);
                }
                $food=new foodlist;
                $food->food=$request['id'];
                $food->money=$request['money'];
                $food->url=$path;
                $food->content=$request['content'];
                $food->amount=0;
                $food->shop_id=$id;
                $food->save();
                return response()->json(['success' => '1']);
            }
            catch(\Exception $e){
                return response()->json(['success' => '0']);
            }
        }
        else{
            
            try{
                $food=new foodlist;
                $food->food=$request['id'];
                $food->money=$request['money'];
                $food->url=-1;
                $food->amount=0;
                $food->content=$request['content'];
                $food->shop_id=$id;
                $food->save();
                return response()->json(['success' => '1']);
            }
            catch(\Exception $e){
                #echo $e;
                return response()->json(['success' => '0']);
            }

        }
        
    }
    public function get_goods(Request $request){
        $id=$this->Get_Shop_Id($request['email']);
        $ans=new Shop;
        $ans=$ans::find($id);
        #echo $ans;
        foreach($ans->foodlist as $shop){
           
            if($shop->count()>0)
                $data[]=array('id'=>$shop->food_id,'food'=>$shop->food,'money'=>$shop->money,'img'=>$shop->url,'content'=>$shop->content,'amount'=>$shop->amount);
            else{
                return response()->json(['success' => '0']); 
            }
        }

        return response()->json(['success'=>'1','data'=>$data]);
    }
    public function goods_delete(Request $request){
        try{
            $food=new foodlist;
            $ans=$food->where('food_id', '=', $request['id'])->delete();
            
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function goods_one(Request $request){
        
        try{
            
            $food=new foodlist;
            $ans=$food->where('food_id','=',$request['food_id'])->get();
            
        }catch(\Exception $e){
        
            return response()->json(['success' => '0']);
        }   
        return response()->json(['success' => '1','data'=>$ans]);
        
    }
    public function setting_name(Request $request){
        $id=$this->Get_Shop_Id($request['email']);
        
        try{
            $shop=new Shop;
            $shop::where('id',$id)->update(['shop_name'=>$request['id']]);
        }
        catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function setting(Request $request,Shop $shop){
        
        $id=$this->Get_Shop_Id($request['email']);
        
        try{
            
            $ans=$shop->where('id',$id)->first();
           
           
            
        }
        catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        
        return response()->json(['success' => '1','data'=>$ans]);
    }
    public function setting_time(Request $request){
        $id=$this->Get_Shop_Id($request['email']);
        try{
            $shop=new Shop;
            $shop::where('id',$id)->update(['moring'=>$request['time1'],'afternoon'=>$request['time2'],
            'night'=>$request['time3'],'midnight'=>$request['time4']]);
        }
        catch(\Exception $e){
            #echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function get_all(Request $request){
        try{
            date_default_timezone_set('Asia/Taipei');
            $shop=new Shop;
            $datetime= date("H");
            $num = (int)$datetime;
            $locate=$request['locate'];
            
            switch($locate){
                case 1:
                    $locate="台北";
                    break;
                case 2:
                    $locate="台中";
                    break;
            }
            if($num>=6&&$num<12){
                $all=$shop::where(['city'=> $locate,'moring'=>'1','active'=>'1'])->get();
            }
            if($num>=12&&$num<18){
               
                $all=$shop::where(['city'=> $locate,'afternoon'=>'1','active'=>'1'])->get();
            }
            if($num>=18&&$num<24){
                $all=$shop::where(['city'=> $locate,'night'=>'1','active'=>'1'])->get();
            }
            if(($num>=0&&$num<6)){
                $all=$shop::where(['city'=> $locate,'midnight'=>'1','active'=>'1'])->get();
            }
            foreach($all as $shop){
                $data[]=array('shop_name'=>$shop->shop_name,'id'=>$shop->id);
            }
            
        }
        catch(\Exception $e){
           
            return response()->json(['success' => '0']);
        }
        
       return response()->json(['success' => '1','data'=>$data]);
        
    }
    public function get_ShopAll_goods(Request $request,Shop $shops){
        $id=$request['shop_id'];
        $shops=$shops::find($id);
        
        foreach($shops->foodlist as $shop){
           
            if($shop->count()>0)
                $data[]=array('id'=>$shop->food_id,'food'=>$shop->food,'money'=>$shop->money,'img'=>$shop->url,'content'=>$shop->content,'amount'=>$shop->amount);
            else{
                return response()->json(['success' => '0']); 
            }
        }
        return response()->json(['success'=>'1','data'=>$data]);
    }
    public function check(Request $request,Orderlist $order){
        $id=$this->Get_Shop_Id($request['id']);
        $flow=new Flowchart;
        try{
            $order=$order::where(['shop_id'=>$id])->orderBy('updated_at','desc')->take(30)->get();
            
            foreach($order as $a){
                $f=$flow::where('id',$a->id)->first();
                // echo $a->create_at;
                 $data[]=array('id'=>$a->id,'created_at'=>$a->created_at,'exception'=>$f->exception,'user_id'=>$a->user_id);
            }
            
        }catch(\Exception $e){
           
            return response()->json(['success' => '0']);
        }
          return response()->json(['success' => '1','data'=>$data]);
    }
    public function notic($id,Request $request){
        $id=$request['id'];
        // echo $id;
        $order=new Orderlist;
        $id=$this->Get_Shop_Id($id);
        $o=$order->where('shop_id',$id)->get();
        $f=[];
        $flow=new Flowchart;
        foreach($o as $s){  
            array_push($f,$s->flowchart_id);
        }
        $a=$flow::whereIn('id',$f)->where('flowchart_set','1')->where('flowchart_make','0')->orderBy('time_set','desc')->get();
        $p=$flow::whereIn('id',$f)->where('flowchart_done','1')->orderBy('time_done','desc')->get();
        if(count($p)>0)
            foreach($p as $s){
                $data[]=array('id'=>$s->id,'time'=>$s->time_done);
            }
        else
            $data=0;
        if (count($a)>0){
            foreach($a as $s){
                $data2[]=array('id'=>$s->id,'time'=>$s->time_set);
            }
        }
        else{
            $data2=0;
        }
       return response()->json(['success' => '1','data'=>$data,'data2'=>$data2]);
        
    }
    public function getuser(Request $request,Chat $chat){
        try{
            $id=$this->Get_Shop_Id($request['id']);
            $chat_id=$chat::where('shop_id',$id)->distinct()->pluck('user_id');
            $user=new user;
            
            if($chat_id->count()>0){
                foreach($chat_id as $c){   
                    $user=$user->where('id',$c)->first();
                    $data[]=array('user_id'=>$c,'user'=>$user->email);
                }
                
            }
            else{
                return response()->json(['success'=>'1','data'=>'0']);
            }

        }
        catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=> $data]);
    }
    public function chat(Request $request,Chat $caht){
         try{
            $id=$this->Get_Shop_Id($request['id']);
            $chat1=new Chat;
            $chat1->shop_id=$id;
            $chat1->chat1="null";
            $chat1->chat2=$request['chatText'];
            $chat1->user_id=$request['userid'];
            $chat1->save();
        }catch(\Exception $e){
            echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function get_chat(Request $request,Chat $chat){
       
         $id=$this->Get_Shop_Id($request['id']);
         $user_id=$request['shop_id'];
        
         try{
            $chat=$chat::where('shop_id',$id)->where('user_id',$user_id)->orderBy('id','ASC')->get();
         }catch(\Exception $e){
              return response()->json(['success'=>'0']);
         }
         return response()->json(['success'=>'1','data'=>$chat]);
    }
}
