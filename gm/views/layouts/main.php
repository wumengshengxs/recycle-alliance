<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link href="/concept/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/concept/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="/concept/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/concept/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/concept/vendor/layui/css/layui.css"  media="all">
    <link rel="stylesheet" href="/concept/vendor/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
    <style>
        .layui-input{
            height: 42px;
        }

    </style>

    <title>小松鼠运营管理系统</title>
</head>

<body>
<div class="dashboard-main-wrapper">
    <?php $this->beginContent('@gm/views/layouts/nav.php');?>
    <?php $this->endContent();?>
    <?php $this->beginContent('@gm/views/layouts/menu.php');?>
    <?php $this->endContent();?>
    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content "><?=$content?></div>
    </div>

</div>
<footer class="app-footer">
    <div class="wrapper"></div>
</footer>

<!-- bootstap bundle js -->
<script src="/concept/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<!-- slimscroll js -->
<script src="/concept/vendor/slimscroll/jquery.slimscroll.js"></script>
<!-- main js -->
<script src="/concept/libs/js/main-js.js"></script>
<!-- chart chartist js -->
<script src="/concept/vendor/charts/chartist-bundle/chartist.min.js"></script>
<!-- sparkline js -->
<script src="/concept/vendor/charts/sparkline/jquery.sparkline.js"></script>
<!-- morris js -->
<script src="/concept/vendor/charts/morris-bundle/raphael.min.js"></script>
<script src="/concept/vendor/charts/morris-bundle/morris.js"></script>
<!-- chart c3 js -->
<script src="/concept/vendor/charts/c3charts/c3.min.js"></script>
<script src="/concept/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
<script src="/concept/vendor/charts/c3charts/C3chartjs.js"></script>
<!-- dataTables -->
<script src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
<script src="/concept/vendor/datatables/js/dataTables.js"></script>

<script src="/concept/vendor/datepicker/moment-with-locales.min.js"></script>
<script src="/concept/vendor/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="/concept/vendor/datepicker/app.js"></script>

<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>

</body>
</html>