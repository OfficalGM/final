@extends('layouts.shopadmin')

@section('content')

<h1>{{"訂單編號：$id"}}</h1>
<div class='row'>
    <div class='col-12'>
        <div class="jumbotron jumbotron-fluid bg-muted">
            <div class="container">
                <div class="progress" style="height:30px;">
                    <div id='progress' class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                </div>
                <div class='row mt-4'>
                    <div class='col text-center'>
                        <p class="lead text-success check">訂單建立</p>
                        <small id='one'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單製作</p>
                        <small id='two'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單運送</p>
                        <small id='three'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單完成</p>
                        <small id='four'></small>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class='col-12'>
        <div class='row'>
            <div class='col-8'>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">訂單內容</h4>
                        <div class='row' id='check_content'>
                            <div class='col-4 mt-3 text-center'>商品</div>
                            <div class='col-4 mt-3 text-center'>數量</div>
                            <div class='col-4 mt-3 text-center'>價錢</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-4'>
            
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">顧客訊息</h4>
                            <p>地址：<small id="addr"></smaill></p>
                            <p>Emial：<small id="email"></smaill></p>
                            <div class='text-right'>
                                <a href="/store/admin/talk" class="card-link">聯絡顧客</a>
                            </div>
                    </div>
                </div>
                <a class="btn btn-success btn-lg btn-block mt-2" href="/store/admin/googleMap" role="button">Google MAP</a>
                
                <a class="btn btn-warning btn-lg btn-block" role="button" onclick="check_detail(this.id)" id='check_detail'>訂單製作中</a> 
                <a class="btn btn-danger btn-lg btn-block text-light" role="button" onclick="return_good()" id="return_good">申請退貨</a> 
            </div>
        </div>
    
    </div>
    


       
</div>
<script>
    function return_good(){
        var order="{{$id}}"
        $.ajax({
            url: '/rest/api/buy/return_good/'+order,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
                if(data.success==1){
                    location.href='/store/admin/check';
                }
            }
        });
    }
    $(document).ready(function(){
        var id="{{$id}}"
        var user=Cookies.get('check_u')
        Cookies.set('order',id);
        console.log(id)
        $.ajax({
            url: '/rest/api/buy/shop_detial',
            dataType: "json",
            type: 'get',
            data: {orderlist:id,user_id:user},
            success: function (data) {
                if(data.success==1){
                    var d=data.data
                    var element=document.getElementsByClassName('check')
                    var progress=document.getElementById('progress')
                    if(d[0].one==1){
                        progress.style.width='25%';
                        progress.innerHTML='25%'
                        element[0].className+=' text-success';
                        document.getElementById('one').innerHTML=d[0].time1
                    }
                    if(d[0].two==1){
                        console.log(d)
                        progress.style.width='50%'
                        progress.innerHTML='50%'
                        element[1].className+=' text-success';
                        console.log(d[0])
                        document.getElementById('two').innerHTML=d[0].time2
                        document.getElementById('check_detail').innerHTML="訂單運送中"
                        document.getElementById('return_good').className+=' disabled'
                    }
                    if(d[0].three==1){
                        progress.style.width='75%';
                        progress.innerHTML='75%'
                        element[2].className+=' text-success';
                        document.getElementById('three').innerHTML=d[0].time3
                        document.getElementById('check_detail').innerHTML="訂單完成"
                        document.getElementById('check_detail').className+=' disabled'
                    }
                    if(d[0].four==1){
                        progress.style.width='100%';
                        progress.innerHTML='100%'
                        element[3].className+=' text-success';
                        document.getElementById('four').innerHTML=d[0].time4
                        document.getElementById('check_detail').className+=' disabled'
                       
                        

                    }
                    var d=data.food
                    var html=""
                    var money=0
                    for(var i=0;i<d.length;i++){
                        // <div class='col-4 mt-3'>123</div>
                        html+="<div class='col-4 mt-3 text-center'>"+d[i].food+"</div>"
                        html+="<div class='col-4 mt-3 text-center'>"+d[i].amount+"</div>"
                        html+="<div class='col-4 mt-3 text-center'>"+d[i].money+"</div>"
                        money+=d[i].money
                    }
                    var d=data.order
                    switch(d[0].service){
                        case "0":
                            service="內用"
                            break;
                        case "1":
                            service="外送"
                            break;
                        case "2":
                            service="外帶"
                            break;
                    }
                    html+="<div class='col-3 mt-4 text-center'>預約服務:</div><div class='col-3 mt-4 text-center'>"+service+"</div><div class='col-3 mt-4 text-center'>預約時間:</div><div class='col-3 mt-4 text-center'>"+d[0].time+"</div>"
                    html+="<div class='col-8 mt-4 text-center' style='font-size:2rem;'>總價:</div><div class='col-4 mt-4 text-center text-info' style='font-size:2rem;'>"+money+"</div"
                    document.getElementById('check_content').innerHTML+=html;
                    var d=data.user
                    console.log(d)
                    document.getElementById('email').innerHTML=d[0].email;
                    document.getElementById('addr').innerHTML=d[0].addr;
                }
            }
        });
    });
    function check_detail(id){
        var element=document.getElementById(id)
        
        var order="{{$id}}"//Cookies.get('check_u')
        
        
        if(element.innerHTML=="訂單製作中"){
            
            var a=1
            console.log(a,order)
            $.ajax({
                url: '/rest/api/buy/shop_check/'+a+"/"+order,
                type: 'PATCH',
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    if(data.success==1){
                         location.reload();
                    }
                }
            });
            element.innerHTML="訂單運送中"
            
        }
        else if(element.innerHTML=="訂單運送中"){
            
            var a=2
            $.ajax({
                url: '/rest/api/buy/shop_check/'+a+"/"+order,
                type: 'PATCH',
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    
                    if(data.success==1){
                         location.reload();
                    }
                }
            });
        }

    }
</script>
@endsection