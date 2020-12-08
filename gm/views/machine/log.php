<!DOCTYPE html>
<html>

<head>
    <title>创建假期</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
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
    <link rel="stylesheet" type="text/css" href="/lib/css/jquery-ui.min.css">
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
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="switch" style="">
                                        <div class="form-horizontal">

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期一.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期一
                                                        </button>
                                                    </a>
                                                    &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期二.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期二
                                                        </button>
                                                    </a>
                                                    &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期三.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期三
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期四.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期四
                                                        </button>
                                                    </a>
                                                    &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期五.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期五
                                                        </button>
                                                    </a>
                                                    &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期六.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期六
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <a href="https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/<?= $device_id ?>_星期天.txt"
                                                       target="_blank"><!--{{item.name}}</a>-->
                                                        <button onclick="recycl_change();"
                                                                class="btn btn-lg btn-success">
                                                            星期天
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
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
<script>
    function recycl_change() {
        //获取时间数据input的value
        //var datepicker = $('#datepicker').val()
        //if (!datepicker) {
        //    alert('请选择日期')
        //    return
        //}
        //var data = {
        //    datepicker: datepicker,
        //    recycler_id: '<?//=$recycler_id?>//',
        //    //表单提交的yii验证
        //    "_csrf-gm": "<?//=Yii::$app->request->csrfToken?>//"
        //}
        //$.ajax({
        //    url: '/recycler/addrest',
        //    type: 'post',
        //    data: data,
        //    dataType: 'json',
        //    success: function (res) {
        //        alert(res.msg);
        //        setTimeout(function (){
        //            parent.location.reload();
        //        },500)
        //
        //        //
        //        // if (!res.res) {
        //        //     alert(res.msg);
        //        //     setTimeout(function () {
        //        //         parent.location.reload();
        //        //     }, 500);
        //        // } else {
        //        //     alert(res.msg);
        //    }
        //});
    }

    // function get_table() {
    //     var delivery_time = $('#datepicker').val();
    //     var phone_num = $('#phone_num').val();
    //     var machine_id = $('#machine_id').val();
    //     var params = {
    //         'delivery_time': delivery_time,
    //         'phone_num': phone_num,
    //         'machine_id': machine_id
    //     };
    //     window.dataTable(params);
    // }
</script>