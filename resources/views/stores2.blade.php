<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content={{csrf_token()}}>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
    crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
    crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.js"></script>
    <style>
        button {
            cursor: pointer;
        }
        a{
            cursor:pointer;
        }
    </style>
    <script>
    var cook=Cookies.get('shop');
        $(document).ready(function(){
            GetShop();
            var cook=Cookies.get('shop');
            //Cookies.remove('');
            console.log(Cookies.get('shopcart'))
            console.log(Cookies.get('shop'))
            console.log(cook)

            if (typeof cook != 'undefined' &&cook!='' && cook!='undefined'){    
                //var html="<div class=dropdown><a class='btn btn-outline-success text-success' role=button id=member data-toggle=dropdown aria-haspopup='true' aria-expanded=false>"+cook+"</a><div class='dropdown-menu bg-dark' aria-labelledby='dropdownMenuButton'><a class='dropdown-item bg-dark text-success' onclick=logout()>登出</a></div></div>"
                //document.getElementById('members').innerHTML=html;
            }

        });
        function logout(){
            Cookies.remove('shop');
            location.href='/';
        }
    var shopcart=Array();
    function Add_shopCart(id){
        var d=document.getElementById('shopcart_'+id)
        
        var shopcart2=[];
        if(d.innerHTML=="加入購物車"){
            shopcart.push(id)
            d.innerHTML="已加入"    
        }
        else if(d.innerHTML=="已加入"){
            for(var i=0;i<shopcart.length;i++){
                if(shopcart[i]!=id)
                    shopcart2.push(shopcart[i])
            }
            shopcart=shopcart2
            d.innerHTML="加入購物車"
        }
        console.log(shopcart)
       
        
    }
    function GetShop(){
        $.ajax({
                url: '/rest/api/shop/get_ShopAll_goods',
                dataType: "json",
                type: 'post',
                data: { shop_id:{{$shop}}},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    //console.log(data)
                    var shop=document.getElementById('shop');
                    var html="";
                    if(data.success==1){
                        var data=data.data
                        console.log(data)
                        
                        for(var i=0;i<data.length;i++){
                            
                            if(data[i].amount>0){
                                
                                if(data[i].img=="-1"){
                                    html+="<div class='col-4 mt-5' style='width:50rem'><div class='card'>"
                                }
                                else{
                                    html+="<div class='col-4 mt-5' style='width:30rem'><div class='card'>"
                                    html+="<img class='card-img-top' src=/image/"+data[i].img+">"
                                }

                                html+="<div class=card-body><h4 class='card-title'>"+data[i].food+"</h4>"
                                if(data[i].content!=null){
                                    html+="<p class='card-text'>"+data[i].content+"</p><p class='card-text'>價錢： "+data[i].money+"元</p><a class='card-link text-primary' id='shopcart_"+data[i].id+"' onclick=Add_shopCart("+data[i].id+")>加入購物車</a></div></div></div>"
                                }
                                else{
                                    html+="<p class='card-text'>價錢： "+data[i].money+"元</p><a class='card-link text-primary' id='shopcart_"+data[i].id+"' onclick=Add_shopCart("+data[i].id+")>加入購物車</a></div></div></div>"
                                }
                            }
                             
                        }
                        shop.innerHTML+=html;
                    }
                    // <ul class='list-group list-group-flush'>
                    //     <li class="list-group-item">Cras justo odio</li>
                    //     
                    // </ul>

                    // <div class='col-4 mt-5'style='width: 20rem;'>
		            //         <div class='card'>
			        //             <div class='card-body'>
				    //                 <h4 class='card-title'>"+d[i].shop_name+"</h4>
				    //                 <a class='btn btn-primary text-light' onclick='link_click("+d[i].id+")'>點擊</a>
			        //             </div>
		            //         </div>
	                //     </div>
                }        
            });
    }
    function Checkout(){
        url=location.href.split('/')
        url=url[url.length-1]
        
        Cookies.set('Buy_shop',url)
        Cookies.set('shopcart',shopcart)
        location.href="/checkout"
    }
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top mb-5 ">
            <a class="navbar-brand" href="/">EATs</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <nav class="navbar navbar-dark bg-dark">
                       
                    </nav>
                </ul>
                <ul class="navbar-nav" id="members">
                    <a class="btn btn-outline-success ml-sm-2 text-success" onclick="Checkout()">結帳</a>
                    
                </ul>
            </div>
        </nav>
    </header> 
    <div class="container mt-5">
            <div class='row'>
                <div class="mt-5">
                    <h2 id="title"></h2>
                    <div class="row" id="shop">
                    
                    
                   
                </div>
            </div>
    </div>
         
</body>

</html>