<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?=Html::encode($this->title)?></title>
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
        .card{height:460px;}
        #signin{padding-top:45px;}
        #contact{display:none;}
        #qrcode{text-align:center;}
        .help-block-error{color:#ff407b;}
        .password{margin-bottom:10px;}
    </style>
</head>

<body>
<!-- ============================================================== -->
<!-- login page  -->
<!-- ============================================================== -->
<div class="splash-container">
    <div class="card">
        <div class="card-header text-center"><a href="/"><img class="logo-img" src="/concept/images/logo.png" alt="logo"></a></div>
        <div id="signin" class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <div class="form-group">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => '登录账号', 'required' => '', 'oninvalid' => 'setCustomValidity("请输入账号")', 'oninput' => 'setCustomValidity("")'])->label(false) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg password', 'placeholder' => '登录密码', 'required' => '', 'oninvalid' => 'setCustomValidity("请输入密码")', 'oninput' => 'setCustomValidity("")'])->label(false) ?>
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox"><span class="custom-control-label">保存登录信息</span>
                    </label>
                </div>
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div id="contact" class="card-body">
            <div id="qrcode" class="form-group required">
                <div><img width="150" src="/static/qrcode.jpeg" /></div>
                <label class="control-label"><b>扫码关注公众号</b></label>
            </div>
            <div class="form-group required">
                <label class="control-label">联系电话：021-61172936</label>
                <label class="control-label">联系地址：上海市宝山区华滋奔腾大厦508室</label>
            </div>
        </div>
        <div class="card-footer bg-white p-0">
            <div class="card-footer-item card-footer-item-bordered"><a href="javascript:void(0)" onclick="signin()" class="footer-link">用户登录</a></div>
            <div class="card-footer-item card-footer-item-bordered"><a href="/site/restpassword" class="footer-link">重置密码</a></div>
            <div class="card-footer-item card-footer-item-bordered"><a href="javascript:void(0)" onclick="contact()" class="footer-link">关注我们</a></div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- end login page  -->
<!-- ============================================================== -->
<!-- Optional JavaScript -->
<script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="/concept/vendor/bootstrap/js/bootstrap.bundle.js"></script>

<script>
    function contact(){
        $('.card-body').each(function(){
            $(this).hide();
        });
        $('#contact').show();
    }

    function signin(){
        $('.card-body').each(function(){
            $(this).hide();
        });
        $('#signin').show();
    }
</script>
</body>

</html>
