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

    <title>编辑维修记录</title>
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
        <div>编辑维修记录</div>
    </div>
    <div class="card-body">
        <div class="row layui-form">
            <div class="area-title" style="margin-top: 0;">选择机器</div>
            <select id="machine_id" class="form-control" style="height: 42px" onchange="maintain()" lay-filter="machine"  lay-verify="required" lay-search="">
                <option value="">请选择机器</option>
                <?php foreach ($machine as $v) { ?>
                    <option data-maintain="<?php if ($v['maintain_id']){echo $v['maintain_id'];} else {echo 0;}?>" value="<?= $v['id']?>" <?php if ($v['id'] == $info['machine_id']) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row">
            <div class="area-title">选择维修员</div>
            <select id="maintain_id" class="form-control" style="height: 42px" >
                <?php foreach ($maintain as $val) { ?>
                    <option  value="<?= $val['id']?>" <?php if ($val['id'] == $info['maintain_id']) { ?> selected<?php } ?>><?= $val['nick_name']?></option>
                <?php } ?>
            </select>
        </div>
        <div class="">
            <div class="area-title">状态</div>
            <select id="status" class="form-control" style="height: 42px" >
                <option  value="1" <?php if ($info['status'] == 1) { ?> selected<?php } ?>>进行中</option>
                <option  value="2" <?php if ($info['status'] == 2) { ?> selected<?php } ?>>已完成</option>
            </select>
        </div>
        <div class="row">
            <div class="area-title" style="margin-top: 0;">故障类型</div>
            <select id="type" class="form-control" style="height: 42px" >
                <option value="1" <?php if ($info['type'] == 1) { ?> selected<?php } ?>>日常检修</option>
                <option value="2" <?php if ($info['type'] == 2) { ?> selected<?php } ?>>机器故障</option>
            </select>
        </div>
        <div class="">
            <div class="area-title">故障原因</div>
            <input type="text" id="cause" value="<?= $info['cause']?>" class="form-control col-12"/>
        </div>
        <div class="">
            <div class="area-title">管理员备注</div>
            <textarea id="admin_mark" class="form-control col-12"><?= $info['admin_mark']?></textarea>
        </div>

        <div class="">
            <div class="area-title">故障原因以及解决方案</div>
            <textarea id="maintain_mark" class="form-control col-12"><?= $info['maintain_mark']?></textarea>
        </div>
        <input type="hidden" id="id" value="<?= $info['id']?>"/>

        <div class="row row-button">
            <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
            <?php if ($info['status'] == 1) { ?>
            <div id="save" class="col-6 text-center butt" onclick="save()">确定</div>
            <?php }?>
        </div>
    </div>
</div>
</body>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript">
    $(function(){
        layui.use(['form'], function(){
            var form = layui.form;
            form.on('select(machine)', function(data){
                maintain()
            });
        });
    });
    function cancel() {
        window.parent.ifr_close();
    }

    //获取维修员
    function maintain()
    {
        var id = $("#machine_id").find("option:selected").attr("data-maintain");
        $.ajax({
            url: '/machine/get-maintain-list',
            type: 'post',
            data: {id:id,'_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'},
            dataType: 'json',
            success: function(res) {
                $("#maintain_id").html(res)
            },

        });
    }

    //保存
    function save() {
        var data = {
            machine_id: $('#machine_id').val().trim(),
            maintain_id: $('#maintain_id').val().trim(),
            cause: $('#cause').val().trim(),
            type: $('#type').val().trim(),
            admin_mark: $('#admin_mark').val().trim(),
            status : $('#status').val().trim(),
            maintain_mark : $('#maintain_mark').val().trim(),
            id : $('#id').val().trim(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.machine_id == '') {
            Dialog.error("错误", '请选择机器');
            return;
        }
        if (data.maintain_id == '') {
            Dialog.error("错误", '请选择维修员');
            return;
        }

        if (data.cause == '') {
            Dialog.error("错误", '请输入维修原因');
            return;
        }

        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定修改</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/machine/maintain-edit',
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
