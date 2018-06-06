<?php

namespace App\Http\Controllers;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Model\Foodlist;
use App\Http\Model\Shop;
use App\Http\Model\Orderlist;
use App\Http\Model\Flowchart;
use App\Http\Model\User;
use App\Http\Model\Lists;
use App\Http\Model\Manager;
use App\Http\Requests;
use \Exception;
use Storage;
class MController extends Controller
{
    //
    public function reg(Request $request){
        $email=$request['email'];
        $pas=$request['password'];
        try{
            $manger=new Manager;
            $manger->email=$email;
            $manger->password=Crypt::encrypt($pas);
            $manger->save();
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1']);
    }
    public function login(Request $request){
        $email=$request['email'];
        $pas=$request['password'];
        
        
        try{
            $manager=new Manager;
            $manager=$manager::where('email',$email)->first();
            //echo $manager;
            $pas2=Crypt::decrypt($manager['password']);
            
            if($pas==$pas2){
                return response()->json(['success' => '1']);
            }
            
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success'=>'0']);
        }
    }
    public function get_user(){
        $user=new User;
        
        try{
            $user=$user::all();
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$user]);
        
    }
    public function ban($id,$n){
        $user=new User;
        
        $user=$user->where('id',$id)->update(['active'=>$n]);
        
        return response()->json(['success'=>'1']);
    }
    public function get_shop(){
        $user=new Shop;
        
        try{
            $user=$user::all();
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$user]);
    }
    public function ban_shop($id,$n){
        $user=new Shop;
        
        $user=$user->where('id',$id)->update(['active'=>$n]);
        
        return response()->json(['success'=>'1']);
    }
    public function check(){
        $order=new Orderlist;
        try{
            $order=$order::all();
            $flow=new Flowchart;
            $user=new User;
            $shop=new Shop;
            foreach($order as $a){
                $flow=$flow::where('id',$a->id)->first();
                $user=$user::where('id',$a->user_id)->first();
                $shop=$shop::where('id',$a->shop_id)->first();
                $data[]=array('id'=>$a->id,'exception'=>$flow->exception,'user'=>$user->email,'shop'=>$shop->email);
            }
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$data]);
    }
    public function get_foodName($id){
        $foodlist=new Foodlist;
        $f=foodlist::where('food_id',$id)->first();
        return $f['food'];
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
            $user=new User;
            $user=$user::where('id',$request['user_id'])->get();
            foreach($user as $s){
                $users[]=array('email'=>$s->email,'addr'=>$s->address);
            }
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success' => '0']);
        }
        return response()->json(['success'=>'1','data'=>$data,'order'=>$order,'food'=>$ll,'shop'=>$shops,'user'=>$users]);
    }
    public function setting(){
        try{
            $files = Storage::disk('local2')->allFiles();
            $photo=[];
            foreach($files as $f){
                $p=explode(".",$f);
                if($p[1]=="jpg"){
                    array_push($photo,$f);
                }
            }
        }
        catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success'=>'1','data'=>$photo]);
        
    }
    public function update(Request $request){
        $url=$request['url'];
        try{
            $img=str_replace('data:image/jpeg;base64,', '', $url);
            $img = str_replace(' ', '+', $img);
            $img = base64_decode($img);
            $path='img_'.time().'.jpg';
            $exists = Storage::disk('local2')->exists($path);
            if($exists==1){
                return response()->json(['success' => '0']);
            }
            else{
                Storage::disk('local2')->put($path, $img);
            }
        }catch(\Exception $e){
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success' => '1']);
    }
    public function del(Request $request){
        $url=$request['url'];
        try{
           
            
            $exists = Storage::disk('local2')->delete($url);
            
        }catch(\Exception $e){
            // echo $e;
            return response()->json(['success'=>'0']);
        }
        return response()->json(['success' => '1']);
    }
    
}