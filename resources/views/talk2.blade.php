@extends('layouts.shopadmin')

@section('content')

    <h1>聊天室</h1>
    <div class="row " style="overflow-y: hidden;">
        <div class="col-2 " id="shoplist" >
            <div id="list1" class="list-group" >
               
                
            </div>
        </div>

        <div class="col-10">
            <div class="row" id="chat_div" style="height: 600px;overflow-y: scroll;">
                <div class="col-md-6" id="chatyou"></div>
                <div class="col-md-6 " id="chatme">
                    <div class="tooltip bs-tooltip-left bs-tooltip-left-docs mt-1" role="tooltip" style='opacity: 0;right:10px;'>
                        <div class="arrow mt-3"></div>
                        <div class="tooltip-inner"></div>
                    </div>
                    <br>
                </div>
            </div>


            <div class="col-12">
            <form id="sendBox" >
                <div class="form-row">
                    <div class="col-10">
                        <input type="text" id="chat" class="form-control" placeholder="輸入訊息">
                    </div>
                    <div class="col-2">
                        <a class="btn btn-success text-light" role="button" onclick="send()">傳送</a>
                    </div>
                </div>
            </form></div>
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
            getuser();
            var x = document.getElementsByTagName("BODY")[0];
            x.style.overflow  = "hidden";
            var height=$(document).height();
            document.getElementById('chat_div').style.height=(height-190)+"px";
        });
        
        
        function getuser(){
            var member=Cookies.get('shop');
            console.log(member)
            $.ajax({
                url:'/rest/api/shop/getuser',
                dataType: "json",
                type: 'get',
                data:{id:member},
                success:function(data) {
                    if(data.success==1){
                        var data=data.data;
                        var html=""
                        if(data==0){
                            
                        }
                        else{
                            var html='';
                            for(var i=0;i<data.length;i++){
                                html+="<a class='list-group-item list-group-item-action' href='/store/admin/talk/"+data[i].user_id+"' onclick=set_talk('"+data[i].user+"')>"+data[i].user+"</a>"
                            }
                            console.log(data)
                             document.getElementById("list1").innerHTML+=html
                        }
                    }
                }
            }) ;
        }
        
        function set_talk(id){
            console.log(id)
            Cookies.set('talk_user',id)
        }
        
    </script>
@endsection