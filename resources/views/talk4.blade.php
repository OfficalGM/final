@extends('layouts.shopadmin')

@section('content')

    <h1>聊天室</h1>
    <div class="row " style="overflow-y: hidden;">
        <div class="col-2 " id="shoplist" >
            <div id="list1" class="list-group" >
               
                
            </div>
        </div>

        <div class="col-10">
            <div class="row" id="chat_div" style="height: 500px;overflow-y: scroll;">
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
    //static
        $(document).ready(function(){
            add2()
            getuser();
            get_talk();
            var height=$(document).height();
            document.getElementById('chat_div').style.height=(height-190)+"px";
             setInterval("get_talk()",3000);
        });
        //未完
        function get_talk(){
            var url=location.href.split('/')
            url=url[url.length-1]
            var member=Cookies.get('shop');
            console.log(member,url)
            $.ajax({
                url:'/rest/api/shop/get_chat',
                dataType: "json",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{id:member,shop_id:url},
                success:function(data) {
                    console.log(data)
                    if(data.success==1){
                        var d=data.data
                        var html=""
                        var html2=""
                        for(var i=0;i<d.length;i++){
                            if(d[i].chat2!="null"){
                                html+="<br><div class='tooltip bs-tooltip-left bs-tooltip-left-docs mt-1' role='tooltip' style='opacity: 1;right:10px;'><div class='arrow mt-3'></div><div class='tooltip-inner'>"+d[i].chat2+"</div></div><br>"
                                html2+="<br><br>"
                            }
                            if(d[i].chat1!="null"){
                                html2+="<br><div class='tooltip bs-tooltip-right bs-tooltip-right-docs mt-1' role='tooltip' style='opacity: 1;left:10px;'><div class='arrow mt-3'></div><div class='tooltip-inner'>"+d[i].chat1+"</div></div><br>"
                                html+="<br><br>"
                            }
                            
                        }
                        document.getElementById('chatyou').innerHTML=html2
                        document.getElementById('chatme').innerHTML=html;
                    }
                }
            });
        }
        //chat send
        function send(){
            var url=location.href.split('/')
            url=url[url.length-1]
            var userid=url
            var a=document.getElementById('chat');
            var member=Cookies.get('shop');

            $.ajax({
                url:'/rest/api/shop/chat',
                dataType: "json",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{id:member,chatText:a.value,userid:userid},
                success:function(data) {
                    if(data.success==1){
                        var html="<br><div class='tooltip bs-tooltip-left bs-tooltip-left-docs mt-1' role='tooltip' style='opacity: 1;right:10px;'><div class='arrow mt-3'></div><div class='tooltip-inner'>"+a.value+"</div></div><br>"
                        document.getElementById('chatme').innerHTML+=html;
                    }
                }
            });
        }
        //list
        function add2(){
            var x = document.getElementsByTagName("BODY")[0];
            //console.log(x)
            x.style.overflow  = "hidden";
            
            url=location.href.split('/')
            url=url[url.length-1]
            //console.log(url)
            var name=Cookies.get('talk_user')
            var html="<a class='list-group-item list-group-item-action' href='/store/admin/talk/"+url+"' onclick=set_talk('"+name+"')>"+name+"</a>"
            document.getElementById('list1').innerHTML+=html
        }
        function set_talk(id){
            //console.log(id)
            Cookies.set('talk_user',id)
        }
        //list
        function getuser(){
            var member=Cookies.get('shop');
            var url=location.href.split('/')
            url=url[url.length-1]
            console.log(member)
            $.ajax({
                url:'/rest/api/shop/getuser',
                dataType: "json",
                type: 'get',
                data:{id:member},
                success:function(data) {
                    if(data.success==1){
                        var data=data.data;
                       // console.log(data)
                        var html=""
                        if(data==0){
                            
                        }
                        else{
                            var html='';
                            for(var i=0;i<data.length;i++){
                                if(data[i].user_id!=url)
                                    html+="<a class='list-group-item list-group-item-action' href='/store/admin/talk/"+data[i].user_id+"' onclick=set_talk('"+data[i].user+"')>"+data[i].user+"</a>"
                            }
                           // console.log(data)
                             document.getElementById("list1").innerHTML+=html
                        }
                    }
                }
            }) ;
        }
        
       
    </script>
@endsection