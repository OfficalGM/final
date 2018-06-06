@extends('layouts.manager')

@section('content')
<h1>店家管理</h1>
    <div class='row placeholders '>
        <div class='col'>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">帳號</th>
                        <th scope="col">城市</th>
                        <th scope="col">地址</th>
                        <th scope="col">帳號啟用</th>
                        <th scope='col'>BAN</th>
                    </tr>
                </thead>
                <tbody id='manager'>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>Otto</td>
                        <td>123</td>
                        <td><a class="btn btn-primary" href="#" role="button">del</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $.ajax({
                    url: '/rest/api/manger/shop',
                    dataType: "json",
                    type: 'get',
                    success:function(data){
                        console.log(data)
                        if(data.success==1){
                            var html=""
                            var d=data.data
                            for(var i=0;i<d.length;i++){
                                var j=i+1
                                html+="<tr><th scope='row'>"+j+"</th><td>"+d[i].email+"</td><td>"+d[i].city+"</td><td>"+d[i].address+"</td>"
                                if(d[i].active==1){
                                    html+="<td>啟用</td><td><a class='btn btn-danger text-light' role='button' onclick=ban("+d[i].id+",0)>BAN</a></td></tr>"
                                }else{
                                    html+="<td>ＢＡＮ</td><td><a class='btn btn-success text-light' role='button' onclick=ban("+d[i].id+",1)>解BAN</a></td></tr>"
                                }
                                // html+="<td><a class='btn btn-primary text-light' role='button'>BAN</a></td></tr>"
                            }
                            document.getElementById('manager').innerHTML=html;
                        }
                        
                       
                    }
                });
        });
        function ban(id,n){
            console.log(id,n)
            var url='/rest/api/manger/ban_shop/'+id+'/'+n

            $.ajax({
                url: url,
                dataType: "json",
                type: 'patch',
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
    </script>
@endsection