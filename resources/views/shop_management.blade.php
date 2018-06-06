@extends('layouts.shopadmin')

@section('content')
<h1>貨物管理</h1>
    <div class='row placeholders '>
        <div class='col'>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">食物名稱</th>
                        <th scope="col">價錢</th>
                        
                        <th scope="col">數量</th>
                        <th scope="col">圖片</th>
                        <th scope='col'>功能</th>
                    </tr>
                </thead>
                <tbody id='manager'>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>Otto</td>
                        <td><a class="btn btn-primary" href="#" role="button">add</a></td>
                        <td><a class="btn btn-primary" href="#" role="button">del</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $.ajax({
                    url: '/rest/api/shop/goods',
                    dataType: "json",
                    type: 'get',
                    data:{email:cook},
                    success:function(data){
                        if(data.success==1){
                            var manager=document.getElementById('manager')
                            var len=data.data.length;
                            var html=''
                            for(var i=0;i<len;i++){
                                html+="<tr><th scope=1>"+i+"</th><td>"+data.data[i].food+"</td><td>"+data.data[i].money+"</td><td>"+data.data[i].amount+"</td>";
                                //console.log(typeof data.data[i].img)
                                if(data.data[i].img!="-1"){
                                    html+="<td>上傳</td>"
                                }
                                else{
                                    html+="<td>無上傳</td>"
                                }
                                //<a class="btn btn-primary" href="#" role="button">Link</a>
                                html+="<td><a class='btn btn-outline-primary text-primary' role='button' onclick='golink("+data.data[i].id+")'>編輯</a><a class='btn btn-outline-danger text-danger ml-2' role='button' onclick='delete_food("+data.data[i].id+")'>刪除</a></td></tr></tr>";
                                console.log(data.data[i]);
                            }
                            manager.innerHTML=html;
                        }
                        else{

                        }
                        
                       
                    }
                });
        });
        function golink(id){
            location.href='goods_update/'+id;
        }
        function delete_food(food){
            
            $.ajax({
                url:'/rest/api/shop/goods_delete',
                type:'DELETE',
                dataType:'json',
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                data:{id:food},
                success:function(data){
                    location.reload();
                }
            });
        }
    </script>
@endsection