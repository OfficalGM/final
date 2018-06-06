<?php

namespace App\Http\Controllers;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Model\User;
use App\Http\Requests;
use \Exception;
use App\Http\Model\Shop;
use App\Http\Model\Orderlist;
use App\Http\Model\Lists;
use App\Http\Model\Flowchart;
use App\Http\Model\Chat;

class AdminController extends Controller
{
    public function login(Request $request){
        $user=new user;
        $user->email=$request['id'];
        $user->password=$request['password'];
        
        try{
            $ans=$user->where('email',$user->email)->first();
            $pas=Crypt::decrypt($ans['password']);
            $active=$ans['active'];
            if($active==1&&$pas==$user->password){
                return response()->json(['success' => '1']);
            }
            else{
                return response()->json(['success' => '-1']);
            }
        }
        catch(\Exception $e){
                return response()->json(['success' => '0']);
        }
        
    }
    public function storelogin(Request $request){
        $shop=new Shop;
        $shop->email=$request['id'];
        $shop->password=$request['password'];
        try{
            $ans=$shop->where('email',$shop->email)->first();
            #echo $shop;
            $pas=Crypt::decrypt($ans['password']);
            $active=$ans['active'];
            if($active==1&&$pas==$shop->password){
                return response()->json(['success' => '1']);
            }
            else{
                return response()->json(['success' => '-1']);
            }
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
    }
    public function register(Request $request){
        try{
            $user=new User;
            $user->email=$request['id'];
            $user->password=Crypt::encrypt($request['password']);
            $user->city=$request['city'];
            $user->address=$request['address'];
            $user->active=1;
            $user->shop=0;
            $user->disturb=0;
            $user->recommend=0;
            $user->save();
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function setting(Request $request){
        try{
            $user=new User;
            $user->email=$request['id'];
            $ans=$user->where('email',$user->email)->first();
            $output=array(
                'success'=>'1',
                'content'=>array(
                    'shop'=>$ans['shop'],
                    'disturb'=>$ans['disturb'],
                    'recommend'=>$ans['recommend']
                )
                );
            return response()->json($output);
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }

    }
    public function recommend(Request $request){
        try{
            $user=new user;
            $user=$user->where('email',$request['id'])->first();
            $user->recommend=$request['recommend'];
            $user->save();
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function disturb(Request $request){
        try{
            $user=new user;
            $user=$user->where('email',$request['id'])->first();
            $user->disturb=$request['disturb'];
            $user->save();
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function shop(Request $request){
        try{
            $user=new user;
            $user=$user->where('email',$request['id'])->first();
            $shop=new Shop;
            $shop->email=$user->email;
            $shop->password=$user->password;
            $shop->city=$user->city;
            $shop->address=$user->address;
            $shop->active=1;
            $shop->save();
            $user->shop=1;
            $user->save();
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function search_member($email){
        $user=new User;
        $user=$user::where('email',$email)->first();
        return $user->id;
    }
    public function check(Request $request,Orderlist $order){
        $id=$this->search_member($request['id']);
        try{
            $order=$order::where(['user_id'=>$id])->orderBy('updated_at','desc')->take(8)->get();
            $flow=new Flowchart;
            foreach($order as $a){
                $f=$flow::where('id',$a->id)->first();
                
                $data[]=array('id'=>$a->id,'created_at'=>$a->created_at,'exception'=>$f->exception,'user_id'=>$a->user_id,'shop_id'=>$a->shop_id);
            }
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1','data'=>$data]);
    }
    public function notic($id,Request $request){
        $id=$request['id'];
        $order=new Orderlist;
        $id=$this->search_member($id);
        $o=$order->where('user_id',$id)->get();
        $f=[];
        $flow=new Flowchart;
        foreach($o as $s){  
            array_push($f,$s->flowchart_id);
        }
        
        $a=$flow::whereIn('id',$f)->where('flowchart_set','1')->where('flowchart_make','1')->where('flowchart_done','0')->orderBy('time_make','desc')->get();
        $p=$flow::whereIn('id',$f)->where('flowchart_make','1')->where('flowchart_way','1')->where('flowchart_done','0')->orderBy('time_way','desc')->get();
        
        if(count($p)>0){        
            foreach($p as $s){
                $data[]=array('id'=>$s->id,'time'=>$s->time_way);
            }
        }
        else{
            $data=0;
        }
        if (count($a)>0){
            foreach($a as $s){
                $data2[]=array('id'=>$s->id,'time'=>$s->time_make);
            }
        }
        else{
            $data2=0;
        }
       return response()->json(['success' => '1','data'=>$data,'data2'=>$data2]);
        
    }
    public function map(Request $request){
        $id=$request['flow'];
       
        try{
            $flow=new Flowchart;
            $flow=$flow->where('id',$id)->first();
            $order=new Orderlist;
            $o=$order->where('id',$id)->first();
            $user=$o->user_id;
            $shop=$o->shop_id;
            $u=new User;
            $s=new Shop;
            $u=$u->where('id',$user)->first();
            $s=$s->where('id',$shop)->first();
            $data2=['user_addr'=>$u->address,'shop_addr'=>$s->address];
        }catch(\Exception $e){
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1','data'=>$flow,'data2'=>$data2]);
    }
    public function chat(Request $request){
        try{
            $id=$this->search_member($request['id']);
            $chat1=new Chat;
            $chat1->user_id=$id;
            $chat1->chat1=$request['chatText'];
            $chat1->chat2="null";
            $chat1->shop_id=$request['shop_id'];
            $chat1->save();
        }catch(\Exception $e){
            echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success' => '1']);
    }
    public function getshop(Request $request,Chat $chat){
        try{
            $id=$this->search_member($request['id']);
            $chat_id=$chat::where('user_id',$id)->distinct()->pluck('shop_id');
            $shop=new Shop;
            
            if($chat_id->count()>0){
                foreach($chat_id as $c){   
                    $shop=$shop->where('id',$c)->first();
                    $data[]=array('shop_id'=>$c,'shop_name'=>$shop->shop_name);
                }
                
            }
            else{
                return response()->json(['success'=>'1','data'=>'0']);
            }

        }
        catch(\Exception $e){
            echo $e;
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=> $data]);
    }
    public function addshop_name(Request $request,Shop $shop){
        $id=$request['shop'];
        try{
            $shop=$shop->where('shop_name',$id)->first();
            $data[]=array('id'=>$shop->id,'shop_name'=>$shop->shop_name);     
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$data]);
    }
    public function get_chat(Request $request,Chat $chat){
         $id=$this->search_member($request['id']);
         $shop_id=$request['shop_id'];
         try{
            $chat=$chat::where('user_id',$id)->where('shop_id',$shop_id)->orderBy('id','ASC')->get();
         }catch(\Exception $e){
              return response()->json(['success'=>'0']);
         }
         return response()->json(['success'=>'1','data'=>$chat]);
    }
}
