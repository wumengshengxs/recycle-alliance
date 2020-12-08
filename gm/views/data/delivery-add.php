<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="/concept/libs/css/style.css"> -->
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="/concept/vendor/layui/css/layui.css"  media="all">

    <title>添加投递记录</title>
    <style>
        .row {
            padding: 0;
            margin: 0;
        }
        .area-select {
            margin-bottom: 30px;
            /* padding:0; */
        }

        .area-title {
            margin: 20px 0 10px;
        }

        .card-header {
            padding: 1rem 1.25rem;
        }

        .butt {
            border: solid 1px #ced4da;
            cursor: pointer;
        }

        .butt:last-child {
            border-left: none;
        }

        .butt:hover {
            background-color: #05aafe;
            color: #fff;
        }

        .row-button {
            line-height: 62px;
            margin: -20px;
            margin-top: 30px;
        }

        label {
            margin-bottom: 0;
        }

        .machine {
            height: 269px;
            border: 1px solid #D2D2E3;
            padding: 10px 20px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .machine-used {
            min-height: 24px;
            margin-top: 15px;
            font-size:12px;
            font-family:PingFangSC-Bold,PingFang SC;
            font-weight:bold;
            color:rgba(239,24,44,1);
            max-height: 220px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .flex-between {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
        }
        .layui-form-select{
            width: 100%;
        }
    </style>
</head>

<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-header">
        <div>添加投递记录</div>
    </div>
    <div class="card-body">
        <div class="row layui-form">
            <div class="area-title" style="margin-top: 0;">投递小区</div>
            <select id="machine_id" class="form-control" style="height: 42px" onchange="community()" lay-filter="machine"  lay-verify="required" lay-search="">
                <option value="">请选择小区</option>
                <?php foreach ($machine as $v) { ?>
                    <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['machine_id']) ? '' : $_GET['machine_id'])) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row">
            <div class="area-title">投递品类</div>
            <select id="trash_id" class="form-control" style="height: 42px" >
                <option value="">请先选择小区</option>
            </select>
        </div>
        <div class="">
            <div class="area-title">投递手机号</div>
            <input type="text" id="phone_num" value="" class="form-control col-12"/>
        </div>
        <div class="">
            <div class="area-title">投递时间</div>
            <input type="text" id="datetime"  value="" class="form-control layui-input col-12" readonly />
        </div>
        <div class="">
            <div class="area-title">投递重量kg/数量个</div>
            <input type="text" id="delivery_count" value="" class="form-control col-12"/>

        </div>
        <div class="row row-button">
            <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
            <div id="save" class="col-6 text-center butt" onclick="save()">确定</div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){
        layui.use(['form','laydate'], function(){
            var form = layui.form;
            var laydate = layui.laydate;
            //日期时间选择器
            laydate.render({
                elem: '#datetime'
                , type: 'datetime'
                , trigger: 'click'//呼出事件改成click,,去掉这个会闪退
            });
            form.on('select(machine)', function(data){
                community()
            });
        });
    });
    function cancel() {
        window.parent.ifr_close();
    }

    //获取小区垃圾类别
    function community()
    {
        var id = $("#machine_id").find("option:selected").val();
        $.ajax({
            url: '/data/get-community-list',
            type: 'post',
            data: {id:id,'_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'},
            dataType: 'json',
            success: function(res) {
                if (res.status == 200){
                    var html = '<option value="">请选择品类</option>';
                    for (var i in res.data) {
                        html += '<option value="' + res.data[i].id + '">' + res.data[i].can_name + '</option>';
                    }
                    $('#trash_id').html(html);
                }
            },

        });
    }

    //保存
    function save() {
        var data = {
            machine_id: $("#machine_id").find("option:selected").val().trim(),
            trash_id: $("#trash_id").find("option:selected").val().trim(),
            phone_num: $('#phone_num').val().trim(),
            datetime: $('#datetime').val().trim(),
            delivery_count: $('#delivery_count').val().trim(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.machine_id == '') {
            Dialog.error("错误", '请选择小区');
            return;
        }
        if (data.trash_id == '') {
            Dialog.error("错误", '请选择品类');
            return;
        }

        if (data.phone_num == '') {
            Dialog.error("错误", '请输入手机号');
            return;
        }
        if (!(/^1[3456789]\d{9}$/.test(data.phone_num))) {
            Dialog.error("手机号码有误，请重填");
            return;
        }
        if (data.datetime == '') {
            Dialog.error("错误", '请选择时间');
            return;
        }
        if (data.delivery_count == '') {
            Dialog.error("错误", '请输入重量');
            return;
        }

        if (data.category_id == 1 && !(/(^[0-9]\d*$)/.test(data.delivery_count))) {
            Dialog.error("错误", '请输入正确的饮料瓶个数');
            return;
        }
        if (data.category_id > 1 && data.delivery_count < 0) {
            Dialog.error("错误", '请输入正确重量信息');
            return;
        }

        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定保存</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/data/delivery-add',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", '操作成功');
                                setTimeout(function(){
                                    parent.location.reload();
                                },1500);
                            } else {
                                Dialog.error("错误", res.msg);
                            }
                        },
                        beforeSend: function() {
                            Dialog.waiting("处理中，请等待...");
                        }
                    });
                }
            }
        });
    }
</script>
