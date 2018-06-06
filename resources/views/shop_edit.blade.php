@extends('layouts.shopadmin')

@section('content')
<h1 id="title"></h1>
<form class='border border-info rounded p-4'> 
    <div class="form-group row">
        <label  class="col-sm-2 col-form-label">商品名稱</label>
        <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="goods" value="">
        </div>
     </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">商品價錢</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="goods_money" value=''>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">商品數量</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="goods_amount" value=''>
        </div>
    </div>
    <div class="form-group">
        <label>介紹</label>
        <textarea class="form-control" id="goods_content" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label>圖片</label>
        <img id='image'  class="rounded mx-auto d-block preview" style="max-width: 200px; max-height: 200px;">
        <input type="file" class="form-control-file upl" id="goods_image">
        <div class="size"></div>
    </div>
    <p class="h6 text-danger" id="warning"></p>
     <a role="button" class="text-light btn btn-primary" onclick="goods_upload()">更新</a>
</form>

<script>
    var img_base_url="";
    var url=window.location.href
    url=url.split('/')
    var fid=parseInt(url[url.length-1])
    $(document).ready(function(){
        function format_float(num, pos){
            var size = Math.pow(10, pos);
            return Math.round(num * size) / size;
        }
        function preview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.preview').attr('src', e.target.result);
                        var KB = format_float(e.total / 1024, 2);
                        img_base_url=e.target.result
                        $('.size').text("檔案大小：" + KB + " KB");
                    }
                        
                    reader.readAsDataURL(input.files[0]);
            }
        }
        $("body").on("change", ".upl", function (){
            preview(this);
        })
       
        $.ajax({
            url: '/rest/api/shop/goods_one',
            dataType: "json",
            type: 'get',
            data:{food_id:fid},
            success:function(data){
                if(data.success==1){
                   var data=data.data
                   console.log(data)
                   document.getElementById("title").innerHTML="編輯食品："+data[0].food
                   document.getElementById("goods").value=data[0].food
                   document.getElementById('goods_money').value=data[0].money
                   document.getElementById("goods_content").value=data[0].content
                   document.getElementById('goods_amount').value=data[0].amount
                   if(data[0].url!='-1')
                        document.getElementById("image").src='/image/'+data[0].url
                   img_base_url=data[0].url
                }
            }
        });
    });
    function goods_upload(){
        
        var goods=document.getElementById("goods").value;
        var goods_money=document.getElementById('goods_money').value;
        var goods_amount=document.getElementById('goods_amount').value
        var goods_content=document.getElementById('goods_content').value;
        
        if (goods!='' &&goods_money!=''&&parseInt(goods_money)>1 &&parseInt(goods_amount)>-1){
            $.ajax({
                url: '/rest/api/shop/goods_update',
                dataType: "json",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{food_id:fid,id:goods,money:goods_money,content:goods_content,amount:goods_amount,url:img_base_url},
                success:function(data){
                    console.log(data)
                    if(data.success==1){
                        location.href='/store/admin/goods_management';
                    }
                    else{
                        document.getElementById('warning').innerHTML='發生錯誤,請重新上傳'
                    }
                }
            });
            
        }
        else{
            document.getElementById('warning').innerHTML='請填寫商品名稱和價錢和數量'  
        }
                
                
    }    
</script>
@endsection