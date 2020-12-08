<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>重置密码</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link href="/concept/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- forgot password  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card">
            <div class="card-header text-center"><img class="logo-img" src="/concept/images/logo.png" alt="logo"></div>
            <div class="card-body">
                <p style="color:green">保存成功，请您妥善保管账号密码！</p>
            </div>
            <div class="card-footer text-center">
                <span><a href="/">返回登录</a></span>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end forgot password  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="/concept/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        //校验文本框
        document.querySelector('#next').addEventListener('click',function(){
            var mobile = document.querySelector('#mobile');
            var vcode = document.querySelector('#vcode');
            mobile.setCustomValidity('');
            vcode.setCustomValidity('');
            if(mobile.validity.valueMissing){
                mobile.setCustomValidity('手机号不能为空')
            }
            else if(mobile.validity.patternMismatch){
                mobile.setCustomValidity('手机号格式错误');
            }

            if(vcode.validity.valueMissing){
                vcode.setCustomValidity('验证码不能为空')
            }
        },false);

    </script>
</body>

 
</html>