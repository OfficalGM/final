@extends('layouts.admin')

@section('content')

    <h1>聊天室</h1>
    <div class="row " style="overflow-y: hidden;">
        <div class="col-2 " id="shoplist" >
            <div id="list1" class="list-group" >
                <a class='list-group-item list-group-item-action text-primary' data-toggle="modal" data-target="#exampleModalLong">新增聯絡</a>
                
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



    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">新增聯絡</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="sizing-addon2">@</span>
                        <input type="text" class="form-control" id='addshopname' placeholder="店家名稱" aria-label="Username" aria-describedby="sizing-addon2">
                    </div>
                    <div class='text-right' id='fail'>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <a role="button" class="btn btn-primary text-light" onclick='add_talk()'>新增</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    //static
        $(document).ready(function(){
            add2()
            get_shop();
            get_talk();
            var height=$(document).height();
            document.getElementById('chat_div').style.height=(height-190)+"px";
            setInterval("get_talk()",3000);
        });
        
        function get_talk(){
            var url=location.href.split('/')
            url=url[url.length-1]
            var member=Cookies.get('member');
            console.log(member,url)
            $.ajax({
                url:'/rest/api/get_chat',
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
                            if(d[i].chat1!="null"){
                                console.log("AA")
                                html+="<br><div class='tooltip bs-tooltip-left bs-tooltip-left-docs mt-1' role='tooltip' style='opacity: 1;right:10px;'><div class='arrow mt-3'></div><div class='tooltip-inner'>"+d[i].chat1+"</div></div><br>"
                                html2+="<br><br>"
                            }
                            if(d[i].chat2!="null"){
                                
                                html2+="<br><div class='tooltip bs-tooltip-right bs-tooltip-right-docs mt-1' role='tooltip' style='opacity: 1;left:10px;'><div class='arrow mt-3'></div><div class='tooltip-inner'>"+d[i].chat2+"</div></div><br>"
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
            var shopid=url
            var a=document.getElementById('chat');
            var member=Cookies.get('member');

            $.ajax({
                url:'/rest/api/chat',
                dataType: "json",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{id:member,chatText:a.value,shop_id:shopid},
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
            var name=Cookies.get('talk_shop')
            var html="<a class='list-group-item list-group-item-action' href='/member/admin/talk/"+url+"' onclick=set_talk('"+name+"')>"+name+"</a>"
            document.getElementById('list1').innerHTML+=html
        }
        function set_talk(id){
            console.log(id)
            Cookies.set('talk_shop',id)
        }
        //list
        function get_shop(){
            var member=Cookies.get('member');
            var url=location.href.split('/')
            url=url[url.length-1]
            $.ajax({
                url:'/rest/api/getshop',
                dataType: "json",
                type: 'get',
                data:{id:member},
                success:function(data) {
                    if(data.success==1){
                        var data=data.data;
                        var html=""
                        if(data==0){
                        //省略
                        }
                        else{
                            var html='';
                            for(var i=0;i<data.length;i++){
                                if(data[i].shop_id!=url)
                                    html+="<a class='list-group-item list-group-item-action' href='/member/admin/talk/"+data[i].shop_id+"' onclick=set_talk('"+data[i].shop_name+"')>"+data[i].shop_name+"</a>"
                            }
                            //console.log(data)
                             document.getElementById("list1").innerHTML+=html
                        }   
                    }
                }
            }) ;
        }
        //list
       function add_talk(){
            var addshopname=document.getElementById('addshopname').value;
            if(addshopname.length>0){
                $.ajax({
                    url:'/rest/api/addshop_name',
                    dataType: "json",
                    type: 'get',
                    data:{shop:addshopname},
                    success:function(data) {
                        if(data.success==1){
                            data=data.data
                            var html="<a class='list-group-item list-group-item-action' href='/member/admin/talk/"+data[0].id+"' onclick=set_talk('"+data[0].shop_name+"')>"+data[0].shop_name+"</a>"
                            
                            document.getElementById("list1").innerHTML+=html
                        }
                        else{
                            document.getElementById('fail').innerHTML="<small class='text-danger'>新增失敗</small>"
                        }
                    }
                 });
            }
            
        }
       
    </script>
@endsection