@extends('layouts.shopadmin')

@section('content')
    <h1>商店設定</h1>
    <div class='row placeholders'>
         <div class='col-6'>
            <div class="card">
                <div class='card-header'>商店名稱</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0" id='shop'>
                        <input type="text" class="form-control" id="shop_name" value=''>
                        <p class='alert alert-danger mt-3'>(商店名稱不可再更改哦)</p>
                    </blockquote>
                    <a role="button" class="btn btn-primary text-light " id='shop_button' onclick="shop_name()">確定</a>
                </div>
            </div>
         </div>
         <div class='col-6'>
            <div class="card">
                <div class='card-header'>開店時間</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0" >
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="1" id="Radio1" value="1"> 早上
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="2" id="Radio2" value="2"> 下午
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="3" id="Radio3" value="3"> 晚上
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="4" id="Radio4" value="4"> 宵夜
                        </label>                        
                    </blockquote>
                    <a role="button" class="btn btn-primary text-light mt-3" onclick="shop_time()">確定開店</a>
                </div>
            </div>
         
         </div>
    </div>
    <script>
        function shop_name(){
            var name=document.getElementById('shop_name').value;
            console.log(name)
            $.ajax({
                    url: '/rest/api/shop/setting_name',
                    dataType: "json",
                    type: 'get',
                    data:{email:cook,id:name},
                    success:function(data){
                        location.reload();
                    }
            });
        }
        function shop_time(){
            var data=[];
            var Radio1=document.getElementById('Radio1')
            var Radio2=document.getElementById("Radio2")
            var Radio3=document.getElementById('Radio3')
            var Radio4=document.getElementById('Radio4')
            console.log(Radio1.checked)
            if(Radio1.checked==true)
                data.push(1)
            else
                data.push(0)
            if(Radio2.checked==true)
                data.push(1)
            else
                data.push(0)
            if(Radio3.checked==true)
                data.push(1)
            else
                data.push(0)
            if(Radio4.checked==true)
                data.push(1)
            else
                data.push(0)
            console.log(data)
            
            $.ajax({
                url:'/rest/api/shop/setting_time',
                type:'get',
                dataType:'json',
                data:{email:cook,time1:data[0],time2:data[1],time3:data[2],time4:data[3]},
                success:function(d){
                    console.log(d)
                }
            });
        }
        $(document).ready(function(){
            //console.log(cook)
            $.ajax({
                    url: '/rest/api/shop/setting',
                    dataType: "json",
                    type: 'get',
                    data:{email:cook},
                    success:function(data){
                        if(data.success==1){
                            var data=data.data;
                            console.log("data",data)
                            var shop=document.getElementById('shop_name')
                            
                            shop.value=data.shop_name;
                            
                            if(shop.value!="") {
                                document.getElementById('shop_button').className+=' disabled'
                            }
                                
                            var Radio1=document.getElementById('Radio1')
                            var Radio2=document.getElementById("Radio2")
                            var Radio3=document.getElementById('Radio3')
                            var Radio4=document.getElementById('Radio4')
                            if(data.moring==1)
                                Radio1.checked="checked"
                            if(data.afternoon==1)
                                Radio2.checked='checked'
                            if(data.night==1)
                                Radio3.checked='checked'
                            if(data.midnight==1)
                                Radio4.checked='checked'
                        }
                    }
            });
        });
    </script>
    
@endsection