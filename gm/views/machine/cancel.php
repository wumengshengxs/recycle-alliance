<!DOCTYPE html>
<html>

<head>
    <title>派单</title>
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
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="switch" style="">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <div class="col-sm-10 col-md-8 col-lg-9">
                                                    请选择取消原因:
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="cancelInfo" id="optionsRadios1" value="好奇试试/不小心点错了" checked>
                                                            好奇试试/不小心点错了
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="cancelInfo" id="optionsRadios2" value="信息填错了">
                                                            信息填错了
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="cancelInfo" id="optionsRadios3" value="迟迟没有回收员联系我，不想等了">
                                                            迟迟没有回收员联系我，不想等了
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="cancelInfo" id="optionsRadios3" value="平台关闭订单">
                                                            平台关闭订单
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <button onclick="cancel();" class="btn btn-lg btn-success">确认</button>
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
    function cancel() {
        var cancelInfo = $("input[name='cancelInfo']:checked").val()
        var data = {
            cancel_info: cancelInfo,
            order_id: '<?=$order_id?>',
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url:'/bookoption/cancel',
            type:'post',
            data:data,
            dataType:'json',
            success:function(res){
                window.parent.dataTable({});
                alert(res.msg)
            }
        });

    }
</script>