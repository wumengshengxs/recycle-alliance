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
                <form action="/site/restpassword" method="post">
                    <p>重置密码</p>
                    <?php if($msg){?>
                    <p style="color:red">手机号或验证码有误</p>
                    <?php }?>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="input" id="mobile" name="mobile" pattern="^1[3-9]\d{9}$" required placeholder="手机号" value="<?=$mobile?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg col-md-7" style="float:left" type="input" id="vcode" name="vcode" required placeholder="验证码" autocomplete="off">
                        <input class="form-control form-control-lg btn btn-primary col-md-5" style="height: 46px;" type="input" value="获取验证码" />
                    </div>
                    <div class="form-group pt-1">
                        <input type="submit" name="next" id="next" class="btn btn-block btn-primary" value="下一步">
                    </div>
                    <input name="_csrf-gm" type="hidden" value="<?=Yii::$app->request->csrfToken?>" />
                    <input name="step" type="hidden" value="1" />
                </form>
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