<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content={{csrf_token()}}>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
        var flag=false
        var flag2=false
        function warn(){
            document.getElementById('warning').innerHTML=''
            var id=document.getElementById('account').value;
            var pas = document.getElementById('password').value
            var emailRule = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
            if(id.search(emailRule)!= -1){
                flag=true;
            }
            else{
                document.getElementById('warning').innerHTML='帳號有錯誤!!';
                flag=false;
            }
            if (pas!='' ){
                flag2=true;
            }
            else{
                 document.getElementById('warning').innerHTML+="  密碼未填寫!!";
                 flag2=false;
            }
        }

        function login() {
            warn();
            var account = document.getElementById('account').value
            var pas = document.getElementById('password').value
            if(flag==true&&flag2==true){
                $.ajax({
                    url: 'rest/api/shop_login',
                    dataType: "json",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: account,password:pas },
                    success: function (data) {
                        if(data.success==1){
                            console.log("AA")
                            console.log(Cookies.set('shop', account,{ expires: 1 }));
                            //console.log(Cookies.set('shop',account,{expries:1}));
                            location.href='/store/admin';
                        }
                        else if(data.success==-1){
                            document.getElementById('warning').innerHTML='帳號未啟用'
                        }
                        else{
                            document.getElementById('warning').innerHTML='帳號密碼有錯誤'
                        }
                    }
                });
            }


        }
    </script>
    <style>
        body {
            overflow: hidden;
        }

        a {
            cursor: pointer;
        }
    </style>

</head>

<body class='bg-light'>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="/">EATs</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">

                </ul>
                <ul class="navbar-nav ">
                   <!--button class="btn btn-outline-danger ml-sm-2" data-toggle="modal" data-target="#register">註冊</button-->
                </ul>
            </div>

        </nav>
    </header>

    <div class="container mx-auto mt-5 align-items-center">
        <div class="row">
            <div class="col-lg-12">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">帳號</label>
                        <input type="email" class="form-control" id="account" aria-describedby="emailHelp" placeholder="輸入email">
                        
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密碼</label>
                        <input type="password" class="form-control" id="password" placeholder="密碼">
                        
                    </div>
                    <p class="h6 text-danger" id="warning"></p>
                    <a role="button" class="btn btn-primary text-light" onclick="login()">登入</a>
                </form>
            </div>
        </div>
    </div>
       <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                            <input type="password" class="form-control" id="password" placeholder="輸入剛剛輸入的密碼">
                        </div>
                        <button type="submit" class="btn btn-danger">註冊</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>