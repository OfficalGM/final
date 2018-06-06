@extends('layouts.shopadmin')

@section('content')

<h1>訂單總覽</h1>
<div class='row placeholders' id='content'>
       <div class='col-6 mt-3'>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">訂單</h4>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <a href="#" class="card-link"> link</a>
                </div>
            </div>
       </div>
</div>
<script>
     $(document).ready(function(){
        var member=Cookies.get('shop')
        $.ajax({
            url: '/rest/api/shop/check',
            dataType: "json",
            type: 'get',
            data: { id: member},
            success: function (data) {
                if(data.success==1){
                    data=data.data
                    console.log(data)
                    
                    var html=""
                    for(var i=0;i<data.length;i++){
                         console.log(data);
                         if(data[i].exception==-1){
                            html+="<div class='col-6 mt-3'><div class='card'>"
                            html+="<div class='card-body'><h4 class='card-title'>訂單編號："+data[i].id+"</h4><h6 class='card-subtitle mb-2 text-muted'>"+data[i].created_at.date.split('.')[0]+"</h6>"
                            html+="<a href=check/"+data[i].id+" onclick=detail("+data[i].user_id+")>詳細</a></div></div></div>"
                        }
                    }
                    
                    document.getElementById('content').innerHTML=html;
                    
                }   
                
                
            }
        });
    });
    function detail(user){
        Cookies.set('check_u',user)
    }
    
</script>
@endsection