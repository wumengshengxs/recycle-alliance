<!DOCTYPE html>
<html>

<head>
    <title>提现详情</title>
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
                    <div class="form-horizontal">
                        <div class="form-group row no-margin-bottom ">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label ">
                                累计收益:
                            </label>
                            <div class="col-sm-4 col-md-4 col-lg-4 ">
                                <?= $income[$user_id] ?>
                            </div>
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">
                                当前可用环保金:
                            </label>
                            <div class="col-sm-4 col-md-4 col-lg-4 ">
                                <?= $amount[$user_id] ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" style=" width: 100%;overflow-x: scroll;">
                        <table class="datatable-machine-withdraw table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>提现金额</th>
                                <th>提现状态</th>
                                <th>申请提现时间</th>
                                <th>提现到账时间</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>提现金额</th>
                                <th>提现状态</th>
                                <th>申请提现时间</th>
                                <th>提现到账时间</th>
                            </tr>
                            </tfoot>
                        </table>
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

    //当user_id不为空的时候,才执行
    <?php if (!empty($user_id)) { ?>
    window.dataTable_param = {
        'user_id': <?=$user_id?>
    }
    <?php } ?>

</script>