<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/concept/vendor/layui/css/layui.css"  media="all">

    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>

    <title>小松鼠运营管理系统</title>
    <style>
        .butt{
            border:solid 1px #ced4da;cursor:pointer;
        }
        .butt:hover{
            background-color:#05aafe;
            color: #fff;
        }
        .row-input{
            margin: 20px 0px 0px 0px;
        }
        .row-button{
            line-height: 62px;
            margin: -20px;
            margin-top: 45px;
        }
    </style>
</head>
</html>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">收货金额统计</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">收货金额统计</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5><b>收货金额统计</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <select id="type" class="form-control" style="height: 42px" >
                            <option value="" >回收员类型</option>
                            <option value="1" <?php if (!empty($_GET['type']) && $_GET['type'] == 1) {?> selected <?php } ?> >散户</option>
                            <option value="2" <?php if (!empty($_GET['type']) && $_GET['type'] == 2) {?> selected <?php } ?> >物业</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input day" id="daystart" style="height: 42px" value="<?= !empty($_GET['daystart']) ? $_GET['daystart'] : '' ?>" placeholder="日期开始时间">
                        <input type="text" class="layui-input month" id="monthstart" style="height: 42px" value="<?= !empty($_GET['monthstart']) ? $_GET['monthstart'] : '' ?>" placeholder="月份开始时间">
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input day" id="dayend" style="height: 42px" value="<?= !empty($_GET['dayend']) ? $_GET['dayend'] : '' ?>" placeholder="日期结束时间">
                        <input type="text" class="layui-input month" id="monthend" style="height: 42px" value="<?= !empty($_GET['monthend']) ? $_GET['monthend'] : '' ?>" placeholder="月份结束时间">
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <select id="status" class="form-control" style="height: 42px" onchange="dayTab()" >
                            <option value="" >请选择日期/月份</option>
                            <option value="1"   <?php if ( !empty($_GET['status']) && intval($_GET['status']) == 1) {?> selected <?php } ?>  >日期</option>
                            <option value="2"   <?php if ( !empty($_GET['status']) && intval($_GET['status']) == 2) {?> selected <?php } ?> >月份</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2  form-group  col-sm-12 ">
                        <button type="button"  class="btn btn-primary" onclick="get_excel()">导出表格</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="card" style="margin-bottom:0px">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive col-12">
                        <table id="dataTable" class="table table-striped table-bordered second">
                            <thead id="dataTitle">
                            <tr>
                                <?php foreach($day as $val) { ?>
                                    <th><?= $val ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php   unset($day[0]);?>
                            <?php foreach($column as $key=>$val) { ?>
                                <tr>
                                    <td><?= $val['品类'] ?></td>
                                    <?php foreach($day as $v) { ?>
                                        <td><?= $val[$v] ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <span style="color: red">默认展示近<?= $today ?>天的数据</span>
                </div>
            </div>

        </div>

    </div>
</div>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>
<script src="/js/region.js"></script>

<script>
    $(function(){
        if ($("#status").val() == 1 || $("#status").val() == ''){
            $(".day").css('display','block');
            $(".month").css('display','none');
        }else{
            $(".day").css('display','none');
            $(".month").css('display','block');
        }

        layui.use(['form','laydate'], function(){
            var form = layui.form;
            var laydate = layui.laydate;
            //常规用法
            laydate.render({
                elem: '#daystart'
            });
            laydate.render({
                elem: '#dayend'
            });
            laydate.render({
                elem: '#monthstart'
                ,type: 'month'
            });
            laydate.render({
                elem: '#monthend'
                ,type: 'month'
            });
        });
    });

    $('#search').click(function(){
        var status = $("#status").val();
        var type = $("#type").val();
        var params = 'type='+type+'&status='+status;
        if (status == ''){
            var daystart = $("#daystart").val();
            var dayend = $("#dayend").val();
            params += '&daystart='+daystart+'&dayend='+dayend;
        }
        if (status == 1 ){
            var daystart = $("#daystart").val();
            var dayend = $("#dayend").val();
            params += '&daystart='+daystart+'&dayend='+dayend;
            if(daystart == ''){
                Dialog.error("错误", '请输入日期');
                return;
            }
            if(dayend == ''){
                Dialog.error("错误", '请输入日期');
                return;
            }
        }
        if (status == 2){
            var monthstart = $("#monthstart").val();
            var monthend = $("#monthend").val();
            params += '&monthstart='+monthstart+'&monthend='+monthend;
            if(monthstart == ''){
                Dialog.error("错误", '请输入月份');
                return;
            }
            if(monthend == ''){
                Dialog.error("错误", '请输入月份');
                return;
            }
        }

        window.location.href = '/external/recycler-order-pay?'+params;
    });

    function get_excel(){
        var status = $("#status").val();
        var type = $("#type").val();
        var params = 'excel=1&type='+type+'&status='+status;
        if (status == ''){
            var daystart = $("#daystart").val();
            var dayend = $("#dayend").val();
            params += '&daystart='+daystart+'&dayend='+dayend;
        }
        if (status == 1){
            var daystart = $("#daystart").val();
            var dayend = $("#dayend").val();
            params += '&daystart='+daystart+'&dayend='+dayend;
            if((daystart != '' && dayend == '') || (daystart == '' && dayend != '')){
                Dialog.error("错误", '请输入日期');
                return;
            }
        }
        if (status == 2){
            var monthstart = $("#monthstart").val();
            var monthend = $("#monthend").val();
            params += '&monthstart='+monthstart+'&monthend='+monthend;
            if((monthstart != '' && monthend == '') || (monthstart == '' && monthend != '')){
                Dialog.error("错误", '请输入月份');
                return;
            }
        }

        window.location.href = '/external/recycler-order-pay?'+params;
    }

    function dayTab(){
        var status = $("#status").val();
        if (status == 1){
            $(".day").css('display','block');
            $(".month").css('display','none');
        }else{
            $(".day").css('display','none');
            $(".month").css('display','block');
        }
    }





</script>

