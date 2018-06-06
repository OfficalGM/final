@extends('layouts.shopadmin')

@section('content')
<h1>上架</h1>
    <div class='row placeholders '>
       <div class='col'>
            
       </div>
       <div class='col-8  mt-5' >
        <form class='border border-info rounded p-4'> 
            <div class="form-group ">
                <label>商品名稱</label>
                <input  class="form-control" id="goods" aria-describedby="emailHelp" placeholder="輸入商品名稱">
            </div>
            <div class="form-group">
                <label>價錢(單一價錢)</label>
                <input  class="form-control" id="goods_money" placeholder="輸入商品價錢">
            </div>
            <div class="form-group">
                <label>介紹</label>
                <input  class="form-control" id="goods_content" placeholder="輸入商品介紹">
            </div>
            <div class="form-group">
                <label>圖片</label>
                <input type="file" class="form-control-file upl" id="goods_image" >
                <img  class="rounded mx-auto d-block preview" style="max-width: 200px; max-height: 200px;">
                <div class="size"></div>
            </div>
            <p class="h6 text-danger" id="warning"></p>
            <a role="button" class="text-light btn btn-primary" onclick="goods_upload()">上架</a>
        </form>
       </div>
       
       <div class='col'>
       </div>

       <script>
            
            var img_base_url="";
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
            });
            function goods_upload(){
                var goods=document.getElementById("goods").value;
                var goods_money=document.getElementById('goods_money').value;
                var goods_content=document.getElementById('goods_content').value;
                if (img_base_url==''){
                    img_base_url= 0
                }
                if (goods!='' &&goods_money!=''){
                    $.ajax({
                        url: '/rest/api/shop/upload',
                        dataType: "json",
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{email:cook,id:goods,money:goods_money,content:goods_content,url:img_base_url},
                        success:function(data){
                            console.log(data)
                            if(data.success==1){
                                location.href='/store/admin';
                            }
                            else{
                                document.getElementById('warning').innerHTML='發生錯誤,請重新上傳'
                            }
                        }
                    });
            
                }
                else{
                    document.getElementById('warning').innerHTML='請填寫商品名稱和價錢'  
                }
                
                
            }    
       </script>
@endsection