@extends('layouts.manager')



@section('content')
<div class='row'>
    <div class='col-12'>
    <form class='border border-info rounded p-4 mt-4 mb-3'> 
            <div class="form-group">
                <label>圖片</label>
                <input type="file" class="form-control-file upl" id="goods_image" >
                <img  class="rounded mx-auto d-block preview" style="max-width: 350px; max-height: 350px;">
                <div class="size"></div>
            </div>
            <p class="h6 text-danger" id="warning"></p>
            <a role="button" class="text-light btn btn-primary" onclick="goods_upload()">上傳</a>
        </form>
    </div>
    <div class='col-12 mt-4'>
        <div class='row' id='content'>
            <div class='col-3'>
                <figure class="figure">
                    <img src="/image/img_1512899833.jpg" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption text-right">img_1512899833.jpg</figcaption>
                </figure>
            </div>
            <div class='col-3'>
                <figure class="figure">
                    <img src="/image/img_1512899833.jpg" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption text-right text-danger" onclick="del()">刪除</figcaption>
                </figure>
            </div>
            
        </div>
    </div>
</div>
    


</div>
<script>
function del(id){
    
    
    $.ajax({
        url: '/rest/api/manger/del',
        dataType: "json",
        type: 'post',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        data:{url:id},
        success:function(data){
            console.log(data)
            if(data.success==1){
                location.href='/manger/setting';
            }  
        }
                        
    });
}
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
    $.ajax({
        url: '/rest/api/manger/setting',
        dataType: "json",
        type: 'get',
        success:function(data){
            if(data.success==1){
                var data=data.data
                console.log(data)
                var html=""
                for(var i=0;i<data.length;i++){
                    html+="<div class='col-3'> <figure class='figure'><img src='/img/"+data[i]+"' class='figure-img img-fluid rounded'><figcaption class='figure-caption text-right text-danger' onclick=del('"+data[i]+"')>刪除</figcaption></figure></div>"
                }
                document.getElementById('content').innerHTML=html
            }
        }
                        
    });
});
function goods_upload(){
    if (img_base_url!=''){
        $.ajax({
            url: '/rest/api/manger/update',
            dataType: "json",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{url:img_base_url},
            success:function(data){
                console.log(data)
                if(data.success==1){
                    location.href='/manger/setting';
                }          
            }
        });    
    }
}

</script>
@endsection