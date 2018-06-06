@extends('layouts.admin')

@section('content')
    <h1>我的設定</h1>
    <div class='row placeholders'>
       <div class='col-6'>
            <div class="card">
                <div class='card-header'>是否開店</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0" id='shop'>
                        <p clss='alert alert-danger'>(點選後不可再更改哦)</p>
                        
                    </blockquote>
                </div>
            </div>
            <div class="card mt-3">
                <div class='card-header'>是否開啟推薦模式</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0" id='recommend'>
                        <p clss='alert alert-danger'>預設關閉</p>
                        
                    </blockquote>
                </div>
            </div>
       </div>
       <div class='col-6'>
            <div class="card">
                <div class='card-header'>是否勿擾模式</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0" id='disturb'>
                        <p clss='alert alert-danger'>（預設關閉）</p>
                    </blockquote>
                </div>
            </div>
       </div>
       <script>
            var cook=Cookies.get('member');
            $(document).ready(function(){
                
                
                $.ajax({
                    url: '/rest/api/setting',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: cook},
                    success: function (data) {
                        //console.log(data)
                        if(data.success==1){
                            var shop=document.getElementById('shop');
                            var recommend=document.getElementById('recommend');
                            var disturb=document.getElementById('disturb');
                            if(data.content.shop==1){
                                shop.innerHTML+="<button type='button' class='btn btn-secondary btn-lg btn-lg btn-block' disabled>開店</button>"
                            }
                            else{
                                shop.innerHTML+="<a role='button' class='btn btn-primary active text-light btn-lg btn-block' onclick=open_shop()>開店</a>"
                            }
                            if(data.content.recommend==1){
                                
                                recommend.innerHTML+="<a  role='button' class='btn btn-primary active text-light btn-lg btn-block' onclick='open_recommend(0)'>關閉</a>"
                                
                            }
                            else{
                                recommend.innerHTML+="<a role='button' class='btn btn-primary active text-light btn-lg btn-block' onclick='open_recommend(1)'>開啟</a>"
                            }
                            if(data.content.disturb==1){
                                disturb.innerHTML+="<a role='button' class='btn btn-primary active text-light btn-lg btn-block' onclick=open_disturb(0)>關閉</a>"
                            }
                            else{
                                disturb.innerHTML+="<a role='button' class='btn btn-primary active text-light btn-lg btn-block'onclick=open_disturb(1) >開啟</a>"
                            }
                        }
                    }
                });
            });
            function open_shop(){
                
                $.ajax({
                    url: '/rest/api/setting/shop',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{id:cook},
                    success:function(data){
                        location.reload();
                    }
                });
            }
            function open_recommend(i){
                $.ajax({
                    url: '/rest/api/setting/recommend',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{id:cook,recommend:i},
                    success:function(data){
                        //console.log(data)
                        location.reload();
                    }
                });
            }
            
            function open_disturb(i){
                $.ajax({
                    url: '/rest/api/setting/disturb',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{id:cook,disturb:i},
                    success:function(data){
                        //console.log(data)
                        location.reload();
                    }
                });
            }
       </script>
    </div>
@endsection