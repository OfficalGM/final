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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" idropdownMenuButtonntegrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.js"></script>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script>
            $(document).ready(function(){
                var cook=Cookies.get('admin');
                console.log(cook)
                //console.log(cook)
                if (typeof cook != 'undefined' &&cook!='' && cook!='undefined'){
                    
                    document.getElementById('member').innerHTML=cook;
                }
                else{
                    location.href='/manger/login';
                }
                
            });
            function logout(){
                Cookies.remove('admin');
                location.href='/';
            }
        </script>
    <style>
        body {
            padding-top: 3.5rem;
        }

        h1 {
            margin-bottom: 20px;
            padding-bottom: 9px;
            border-bottom: 1px solid #eee;
        }

        a {
            cursor: pointer;
        }

        .sidebar {
            position: fixed;
            top: 51px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar {
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar .nav {
            margin-bottom: 20px;
        }

        .sidebar .nav-item {
            width: 100%;
        }

        .sidebar .nav-item+.nav-item {
            margin-left: 0;
        }

        .sidebar .nav-link {
            border-radius: 0;
        }


        .placeholders {
            padding-bottom: 3rem;
        }

        .placeholder img {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        #map{
            height:1000px;
            width:100%;
        }
        figcaption{
            cursor:pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
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
            <ul class="navbar-nav ">
                <div class="dropdown">
                    <a class="btn btn-outline-success text-success" role="button" id="member" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        
                    </a>

                    <div class="dropdown-menu bg-dark " aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item bg-dark text-success" href="#" onclick="logout()">登出</a>
                    </div>
                </div>

            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-2 d-none d-sm-block bg-light sidebar">
                <ul class="nav nav-pills flex-column ">
                    <li class="nav-item">
                        <a class="nav-link" href="/manger">會員管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/manger/shop">店家管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/manger/check">訂單管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/manger/setting">輪播設定</a>
                    </li> 
                </ul>
            </nav>
            <main class='col-10 pt-3 ml-sm-auto' role="main">
                
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>