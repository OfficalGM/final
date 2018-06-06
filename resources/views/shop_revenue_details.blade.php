@extends('layouts.shopadmin')

@section('content')
<h1>營收總覽</h1>
<section class="row text-center placeholders">
    <div class="col-6 col-sm-6 placeholder">
        <canvas id="myChart" width="700" height="400"></canvas>
        <h4>各月營收</h4>
    </div>
    <div class="col-6 col-sm-6 placeholder">
        <canvas id="myChart2" width="700" height="400"></canvas>
        <h4>銷售情況</h4>
        
    </div>
</section>
<div class='col-12'>
    <div class='row'>
        <h2 class='mr-5'>營收明細</h2>
     
    </div>
<div class="table-responsive mt-3 table-bordered">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>id</th>
                  <th>訂單編號</th>
                   <th>總價</th>
                  <th>完成時間</th>
                  <th>詳細</th>
                </tr>
              </thead>
              <tbody id='tbody'>
                <tr>
                  <td>1</td>
                  <td>torquent</td>
                  <td>per</td>
                  <td>conubia</td>
                  <td>nostra</td>
                </tr>
                
              </tbody>
            </table>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.js"></script>
<script>
$(document).ready(function(){ 
    console.log(cook)
    get_dashboard();
    var url='/rest/api/buy/revenue_details/'+cook
    $.ajax({
        url: url,
        dataType: "json",
        type: 'get',
        success: function (data) {
            if(data.success==1){
                var data=data.data
                var html="";
                for(var i=0;i<data.length;i++){
                    var j=i+1
                    html+="<tr><td>"+j+"</td><td>"+data[i].id+"</td><td>"+data[i].money+"</td><td>"+data[i].time+"</td><td><a class='btn btn-outline-success text-success' onclick='go_link("+data[i].id+")'>詳細</a></td></tr>"
                }
                document.getElementById('tbody').innerHTML=html
            }

        }
    });
});

function go_link(id){
    var url="/store/admin/check/"+id
    location.href=url;
}
function get_dashboard(){
    var url='/rest/api/buy/revenue_dashboard/'+cook
    $.ajax({
        url:url,
        dataType:"json",
        type:'get',
        success:function(data){
            console.log(data)
            if(data.success==1){
                var d=data.data
                var ctx = document.getElementById("myChart").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["1月", "2月", "3月", "4月", "5月", "6月","7月", "8月", "9月", "10月", "11月", "12月"],
                        datasets: [{
                            data: d,
                            label:"月",
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
                //var ctx = document.getElementById("myChart").getContext('2d');
                var d=data.list
                
                d.sort(function(a,b){
                    return b[1]-a[1]
                });
                var name=[]
                var n=[]
                var j=0
                if(d.length>6){
                    j=6
                }
                else{
                    j=d.length
                }
                for(var i=0;i<j;i++){
                    name.push(d[i][0]);
                    n.push(d[i][1]);
                }
                console.log(name)
                console.log(n)
                var ctx = document.getElementById("myChart2").getContext('2d');
                
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: name,
                        datasets: [{
                            label: "食物賣出統計前六名",
                            data: n,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            }
        }
    });
    
}



</script>
@endsection