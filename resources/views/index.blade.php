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
        var flag=false;
        var flag2=false;
        var flag3=false;
        $(document).ready(function(){
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
                            if(i==0)
                                html+="<div class='carousel-item active'><img class='d-block w-100' src='img/"+data[i]+"'></div>"
                            else{
                                html+="<div class='carousel-item'><img class='d-block w-100' src='img/"+data[i]+"'></div>"
                            }
                        }
                    document.getElementById('run').innerHTML=html
                    }
                }            
            });
            // <div class="carousel-item active">
            //     <img class="d-block w-100" src="img/1.jpg" alt="First slide">
            // </div>
        });
        function reg(){
            
            document.getElementById('warning').innerHTML=''
            var id=document.getElementById('account').value;
            var pas = document.getElementById('password').value
            var pas2= document.getElementById("password2").value
            var city=document.getElementById('city').value
            var address=document.getElementById('address').value
            var emailRule = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
            console.log(city,address)
            if(id.search(emailRule)!= -1){
                flag=true;
            }
            else{
                document.getElementById('warning').innerHTML='帳號格式有錯誤!!';
                flag=false;
            }
            if (pas!=''&&pas==pas2){
                flag2=true;
            }
            else{
                 document.getElementById('warning').innerHTML+="  密碼未正確!!";
                 flag2=false;
            }
            if (address!=''){
                flag3=true;
            }
            else{
                 document.getElementById('warning').innerHTML+="  地址未填寫!!";
                 flag3=false;
            }
            if(flag==true&&flag2==true&&flag3==true)
                register(id,pas,city,address);
        }
        function register(account,pas,c,addr){
            console.log(account,pas,c,addr)
            $.ajax({
                    url: 'rest/api/register',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: account,password:pas ,city:c,address:addr},
                    success: function (data) {
                        if(data.success==1){
                            console.log("success")
                            var y=document.getElementById('register');
                            y.removeChild(document.getElementById('register2'))
                            var html="<div class=modal-dialog role=document><div class=modal-content><div class=modal-header><h5 class=modal-title>註冊成功</h5><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class=modal-body id='timer'></div><div class=modal-footer></div></div></div>";
                            y.innerHTML=html;
                            reload_timer()

                        }
                        else{
                            console.log("fail")
                            document.getElementById('warning').innerHTML+=" 註冊失敗!!";
                        }
                    }
            });
        }
        var time=5;
        function reload_timer(){
            time-=1;
            document.getElementById('timer').innerHTML=time;
            document.getElementById('timer').innerHTML+="秒自動跳轉登錄頁面"
            if (time==0){
                location.href='/login';
            }
            setTimeout("reload_timer()",1000);
        }
        function golink(){
            var locate=document.getElementById("locate").value;
            if (locate=="台北" || locate=="台北市"){
                locate="台北"
            }

                location.href="/stores/"+locate;
        }
        var cook=Cookies.get('shop');
        $(document).ready(function(){
            var cook=Cookies.get('member');
            console.log(cook)
            if (typeof cook != 'undefined' &&cook!='' && cook!='undefined'){    
                var html="<div class=dropdown><a class='btn btn-outline-success text-success' role=button id=member data-toggle=dropdown aria-haspopup='true' aria-expanded=false>"+cook+"</a><div class='dropdown-menu bg-dark' aria-labelledby='dropdownMenuButton'><a class='dropdown-item bg-dark text-success' href='member/admin'>詳細資料</a><a class='dropdown-item bg-dark text-success' onclick=logout()>登出</a></div></div>"
                document.getElementById('members').innerHTML=html;
            }   
        });
        function logout(){
            Cookies.remove('member');
            location.href='/';
        }
    </script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="#">EATs</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <nav class="navbar navbar-dark bg-dark">
                        <form class="form-inline">
                            <input class="form-control mr-sm-2" type="text" placeholder="請輸入外送地址" aria-label="Search" id="locate" >
                            <a class="btn btn-outline-success my-2 my-sm-0 text-success" role="button" onclick="golink()">搜尋</a>
                        </form>
                    </nav>
                </ul>
                <ul class="navbar-nav" id="members">
                    <button class="btn btn-outline-success ml-sm-2" data-toggle="modal" data-target="#login">登入</button>
                    <button class="btn btn-outline-danger ml-sm-2" data-toggle="modal" data-target="#register">註冊</button>
                </ul>
            </div>

        </nav>
    </header>
    <div class="container-fluid  bg-light mt-5">
        <div class="row ">
            <div class="col-lg-7">
                <div class="jumbotron bg-light mt-5">
                    <h4 class="display-3 ">這是EATs</h4>
                    <p class="text-success display-4 ">讓您品嚐到百道佳餚</p>
                    <p>從您在地方最愛餐廳，訂購您最愛的美食</p>
                </div>
            </div>
            <div class="col-lg-5">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" id="run">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="img/1.jpg" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="img/2.jpg" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="img/3.jpg" alt="Third slide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class='col-lg-4'>
                <figure class="figure">
                    <img src='img/5.jpg' class="figure-img img-fluid" width='270' height="220">
                    <figcaption class=" text-left mt-5 h3">百大熱門餐廳供您挑選</figcaption>
                    <figcaption class=" text-left w-75">我們和台灣上千家最優質的餐廳合作，集結最棒的當地美食。如果您的饕客靈魂蠢蠢欲動，到 EATS 搜尋嘗鮮吧！</figcaption>
                </figure>
            </div>
            <div class='col-lg-4'>
                <figure class="figure">
                    <img src='img/road.gif' class="figure-img img-fluid">
                    <figcaption class=" text-left mt-5 h3">包您滿意的速度</figcaption>
                    <figcaption class=" text-left w-75">只要輕鬆點選進入 Eats 網站，您即可在我們提供的當地餐廳選單中挑選，快速外送美食到府。從訂購到領取，平均只須短短 30 分鐘。</figcaption>
                </figure>
            </div>
            <div class='col-lg-4'>
                <figure class="figure">
                    <img src='img/100.png' class="figure-img img-fluid">
                    <figcaption class=" text-left mt-5 h3">深得您心的服務</figcaption>
                    <figcaption class=" text-left w-75">送出訂單前，您會看到包含美食價格及外送費用的總金額，無需支付小費。您可使用 Uber 帳戶付款，並在網站上直接追蹤外送過程。</figcaption>
                </figure>

            </div>
        </div>
    </div>
    <footer class="py-5 bg-dark mt-5 fixed-buttom">
        <div class="container-fluid">
            <p class="m-0 text-center text-white">Copyright © EATs 2018</p>
        </div>
    </footer>
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">登入</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
                </div>
                <div class="modal-body">
                    <form >
                        <div class="row ">
                                 <a class="btn btn-outline-success ml-sm-2 text-success" role='button' href='login'>登入</a>
                                 <a class="btn btn-outline-primary ml-sm-2 text-primary" role='button' href='login2'>店家登入</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" id='register2'>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">會員註冊</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">帳號</label>
                            <input type="email" class="form-control" id="account" aria-describedby="emailHelp" placeholder="輸入email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">密碼</label>
                            <input type="password" class="form-control" id="password" placeholder="密碼">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">再次確認密碼</label>
                            <input type="password" class="form-control" id="password2" placeholder="輸入剛剛輸入的密碼">
                        </div>
                        <div class='form-group'>
                            <label for="address">城市</label>
                            <select class="form-control" id="city">
                                <option value="台北">台北</option>
                                <option value="台中">台中</option>
                                
                            </select>
                            </div>
                        <div class='form-group'>
                            <label for="address">地址</label>
                            <input type="text" class="form-control" id="address" placeholder="輸入地址">
                        </div>
                        <p class="h6 text-danger" id="warning"></p>
                        <a role="button" class="btn btn-danger text-light" onclick="reg()">註冊</a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>