@extends('layouts.shopadmin')

@section('content')

<div class='row'>
    <div class='col-9'>
        <div id='map'>

        </div>
    </div>
    <div class='col-3'>
        <div id="right-panel"></div>
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
    function initMap(end,start) {
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
        directionsDisplay.setPanel(document.getElementById('right-panel'));
        directionsService.route(request, function(response, status) {
                //規畫路徑回傳結果
                
                if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                }
        });
    }
</script>
<script type = "text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvzZj_o6PJSFi0GStAwPeDTBGjl4qIBM4">
    </script>
@endsection