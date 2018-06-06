
@extends('layouts.admin')

@section('content')

<div class='row'>
    <div class='col-12'>
        <div class="jumbotron jumbotron-fluid bg-muted">
            <div class="container">
                <div class="progress" style="height:30px;">
                    <div id='progress' class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                </div>
                <div class='row mt-4'>
                    <div class='col text-center'>
                        <p class="lead text-success check">訂單建立</p>
                        <small id='one'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單製作</p>
                        <small id='two'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單運送</p>
                        <small id='three'></small>
                    </div>
                    <div class='col text-center'>
                        <p class="lead check">訂單完成</p>
                        <small id='four'></small>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class='col-12'>
        <div id='map'></div>

    </div>
</div>
    <script>
    $(document).ready(function() {
        var order=Cookies.get('order')
        console.log(order)
        $.ajax({
            url: '/rest/api/check/map',
            dataType: "json",
            type: 'post',
            data: {flow:order},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                console.log(data)
                if(data.success==1){
                    var d=data.data2
                    initMap(d.user_addr,d.shop_addr,data.data)
                    
                }
            }
        });
    });
    function initMap(end,start,data) {
        //console.log(data)
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay;
        var  map = new google.maps.Map(document.getElementById('map'), {
            zoom: 9,
            center: {lat: 25.0262205 , lng: 121.5236503}
       });
       directionsDisplay = new google.maps.DirectionsRenderer({
                'map': map,
                'preserveViewport': true,
                'draggable': true
        });
        
        
        var request = {
            origin: start,
                destination: end,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
                //規畫路徑回傳結果
                console.log(response)
                var second=Math.ceil((response.routes[0].legs[0].duration.value+60*10)/60)
                create_element(second,data)
                if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                }
        });
    }
    function create_element(second,data){
        console.log(data,second)
        var d=data;
        console.log(d)
        var element=document.getElementsByClassName('check')
        var progress=document.getElementById('progress')
        if(d.flowchart_set==1){
            progress.style.width='25%';
            progress.innerHTML='25%'
            element[0].className+=' text-success';
            document.getElementById('one').innerHTML=d.time_set
        }
        if(d.flowchart_make== 1){
            progress.style.width='50%'
            progress.innerHTML='50%'
            element[1].className+=' text-success';
            document.getElementById('two').innerHTML=d.time_make
        }
        else{
            document.getElementById('two').className+=' text-danger'
            document.getElementById('two').innerHTML="預計製作時間：15-20分鐘"
        }
        if(d.flowchart_way==1){
            progress.style.width='75%';
            progress.innerHTML='75%'
            element[2].className+=' text-success';
           document.getElementById('three').innerHTML=d.time_way
        }
        else{
            document.getElementById('three').className+=' text-danger'
            document.getElementById('three').innerHTML="製作完成後,送達時間約為"+second+"分鐘"
        }
        if(d.flowchart_done==1){
            progress.style.width='100%';
            progress.innerHTML='100%'
            element[3].className+=' text-success';
            document.getElementById('four').innerHTML=d.time_done
           
        }
    }
    </script>
    <script type = "text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvzZj_o6PJSFi0GStAwPeDTBGjl4qIBM4">
    </script>
    @endsection
