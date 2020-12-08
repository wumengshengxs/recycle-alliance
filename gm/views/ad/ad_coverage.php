<!DOCTYPE html>
<html>

<head>
<!--    <title>投递明细</title>-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/themes/flat-blue.css">
</head>
<body>
<div class="container-fluid">
    <div class="side-body">

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="">
<!--                                <div class="sub-title">-->
<!--                                    <b>昵称：</b>--><?//= $nick_name ?><!--<br/>-->
<!--                                    <b>手机：</b>--><?//= $phone_num ?>
<!--                                </div>-->
                                <table class="table table-bordered table-striped responsive-utilities">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>
                                            省市
                                            <small>Phones (&lt;768px)</small>
                                        </th>
                                        <th>
                                            区域/街镇
                                            <small>Tablets (&ge;768px)</small>
                                        </th>
                                        <th>
                                            小区
                                            <small>Desktops (&ge;992px)</small>
                                        </th>
                                        <th>
                                            设备
                                            <small>Desktops (&ge;1200px)</small>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row"><code>.visible-xs-*</code></th>
                                        <td class="is-visible">Visible</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><code>.visible-sm-*</code></th>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-visible">Visible</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><code>.visible-md-*</code></th>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-visible">Visible</td>
                                        <td class="is-hidden">Hidden</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><code>.visible-lg-*</code></th>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-hidden">Hidden</td>
                                        <td class="is-visible">Visible</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <input class="_csrf-gm" type="hidden" value="<?= Yii::$app->request->csrfToken ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script type="text/javascript" src="/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/lib/js/Chart.min.js"></script>
<script type="text/javascript" src="/lib/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="/lib/js/select2.full.min.js"></script>
<script type="text/javascript" src="/lib/js/ace/ace.js"></script>
<script type="text/javascript" src="/lib/js/ace/mode-html.js"></script>
<script type="text/javascript" src="/lib/js/ace/theme-github.js"></script>
<script type="text/javascript" src="/lib/js/jquery-ui.min.js"></script>
<!-- Javascript -->
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/index.js"></script>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>

<script type="text/javascript" src="/oss/lib/crypto1/crypto/crypto.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/hmac/hmac.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/sha1/sha1.js"></script>
<script type="text/javascript" src="/oss/lib/base64.js"></script>
<script type="text/javascript" src="/oss/lib/plupload-2.1.2/js/plupload.full.min.js"></script>
<script>

</script>