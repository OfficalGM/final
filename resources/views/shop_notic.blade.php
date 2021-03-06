@extends('layouts.shopadmin')

@section('content')

<h1>通知總覽</h1>
<div class='row placeholders' id='content'>
       
</div>
<script>
     $(document).ready(function(){
        var member=Cookies.get('shop')
        var url='/rest/api/shop/notic/'+member
        $.ajax({
            url: url,
            dataType: "json",
            type: 'post',
            data: { id: member},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.success==1){
                    var d=data.data2
                    if(d!=0){
                        var html=""
                        for(var i=0;i<d.length;i++){
                            html+="<div class='col-6 mt-3'> <div class='card'><div class='card-body'><h4 class='card-title'>訂單編號："+d[i].id+"</h4><h6 class='card-subtitle mb-2 text-danger'>新訂單</h6></div></div></div>"
                        }
                        document.getElementById('content').innerHTML=html;
                        d=data.data
                        create_element(d)
                    }   
                    else{
                        d=data.data
                        create_element(d)
                        
                    }                
                }   
                
                
            }
        });
    });
    function create_element(d){
        var html=""
        console.log(d)
        for(var i=0;i<d.length;i++){
            html+="<div class='col-6 mt-3'> <div class='card'><div class='card-body'><h4 class='card-title'>訂單編號："+d[i].id+"</h4><h6 class='card-subtitle mb-2 text-muted'>已完成</h6></div></div></div>"
        }
        document.getElementById('content').innerHTML+=html;
    }
</script>
@endsection