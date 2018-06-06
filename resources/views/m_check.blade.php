@extends('layouts.manager')

@section('content')
<h1>訂單管理</h1>
    <div class='row placeholders '>
        <div class='col'>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">訂單編號</th>
                        <th scope="col">店家</th>
                        <th scope="col">顧客</th>
                        <th scope="col">申請原因</th>
                        <th scope="col">詳細</th>
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
                    url: '/rest/api/manger/check',
                    dataType: "json",
                    type: 'get',
                    success:function(data){
                        console.log(data)
                        if(data.success==1){
                            var html=""
                            var d=data.data
                            console.log(d)
                            for(var i=0;i<d.length;i++){
                                var j=i+1
                                if(d[i].exception==-1){
                                    html+="<tr><th scope='row'>"+j+"</th><td>"+d[i].id+"</td><td>"+d[i].shop+"</td><td>"+d[i].user+"</td><td></td><td><a href=/manger/detail/"+d[i].id+" onclick=detail("+d[i].shop+","+d[i].user+")>詳細</a></td></tr>"
                                }else{html+="<tr><th scope='row'>"+j+"</th><td>"+d[i].id+"</td><td>"+d[i].shop+"</td><td>"+d[i].user+"</td><td>"+d[i].exception+"</td><td><a href=/manger/detail/"+d[i].id+" onclick=detail("+d[i].shop+","+d[i].user+")>詳細</a></td></tr>"

                                }
                            }
                            document.getElementById('manager').innerHTML=html;
                        }
                        
                       
                    }
                });
        });
        function detail(shop,user){
            Cookies.set('check',shop)
            Cookies.set('check_u',user)
        }
        
    </script>
@endsection