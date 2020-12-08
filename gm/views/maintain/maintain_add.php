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

    <title>添加维修人员</title>
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
    </style>
</head>

<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-header">
        <div>添加维修人员</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="area-title" style="margin-top: 0;">维修人员姓名</div>
            <input type="text" value="" id='nick_name' class="form-control col-12" />
        </div>
        <div class="row">
            <div class="area-title">维修人员手机</div>
            <input type="number" id="phone_num" value="" class="form-control col-12" maxlength="11"/>
        </div>
        <div class="">
            <div class="area-title">维修权限</div>
            <div class='radio-type'>
                <label class="radio-inline" style="margin-right: 30px;">
                    <input type="radio" name="type" id="limitCommon" value="0" checked> 普通维修员
                </label>
                <label class="radio-inline" style="margin-right: 30px;">
                    <input type="radio" name="type" id="limitSuper" value="1"> 超级维修员
                </label>
                <label class="radio-inline">
                    <input type="radio" name="type" id="limitMove" value="2"> 机动维修员权限
                </label>
            </div>
        </div>
        <div class="">
            <div class="area-title">账号状态</div>
            <div class='radio-type'>
                <label class="radio-inline" style="margin-right: 30px;">
                    <input type="radio" name="status" id="statusSuccess" value="1" checked> 生效
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" id="statusFail" value="0"> 失效
                </label>
            </div>
        </div>
        <div class="">
            <div class='flex-between'>
                <div class="area-title">请选择回收机</div>
            </div>
            <ul class='machine list-unstyled'>
                <?php foreach ($machine as $v) { ?>
                    <li>
                        <label class="checkbox-inline">
                            <input type="checkbox" data-community='<?= $v['community_name'] ?>' data-name="<?= $v['maintain'] ?>" name='machine' value="<?= $v['id'] ?>"> <?= $v['community_name'] ?> — <?= $v['location'] ?>
                        </label>
                    </li>
                <?php } ?>

            </ul>
        </div>
        <ul class='machine-used list-unstyled'>
            <li></li>
        </ul>
        <div class="row row-button">
            <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
            <div id="save" class="col-6 text-center butt" onclick="save()">确定</div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript">
    function cancel() {
        window.parent.ifr_close();
    }
    var $machine = $('input[name=machine]')
    //全选
    $('#selectAll').click(function() {
        var checked = $(this).prop('checked')
        $machine.prop('checked', checked)
    })
    //checkbox选择
    $machine.click(function() {
        var $machineChecked = $('input[name=machine]:checked')
        var name = []
        var str=''
        var machineId = []
        $machineChecked.each(function(i, e) {
            machineId.push($(this).val())
            name.push($(this).attr('data-community')+','+$(this).attr('data-name'))

        })
        for(var j=0;j<name.length;j++){
            if(name[j].split(",")[1]!=''){
                str+='<li>已选设备（'+name[j].split(",")[0]+'）将从维修人员（'+name[j].split(",")[1]+'）管理设备中移除。</li>'
            }
        }
        $('.machine-used').html(str)

        if (machineId.length == $machine.length) {
            $('#selectAll').prop('checked', true)
        } else {
            $('#selectAll').prop('checked', false)
        }
    })
    //普通权限
    $('#limitCommon').click(function() {
        $machine.prop('checked', false)
        $machine.attr('disabled', false)
    })
    //超级权限
    $('#limitSuper').click(function() {
        $machine.prop('checked', true)
        $machine.attr('disabled', true)
        $('.machine-used').html('')
    })
    //机动权限
    $('#limitMove').click(function() {
        $machine.prop('checked', true)
        $machine.attr('disabled', true)
        $('.machine-used').html('')
    })
    //保存
    function save() {
        var id = []
        var $machineChecked = $('input[name=machine]:checked')
        $machineChecked.each(function(i, e) {
            id.push($(this).val())
        })
        var data = {
            id: id,
            nick_name: $('#nick_name').val().trim(),
            phone_num: $('#phone_num').val().trim(),
            type: $('input[name=type]:checked').val().trim(),
            maintain_status: $('input[name=status]:checked').val().trim(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.nick_name == '') {
            Dialog.error("错误", '请输入姓名');
            return;
        }
        if (data.phone_num == '') {
            Dialog.error("错误", '请输入手机号');
            return;
        }
        if (data.id == '') {
            Dialog.error("错误", '请选择回收机');
            return;
        }
        if (!(/^1[3456789]\d{9}$/.test(data.phone_num))) {
            Dialog.error("手机号码有误，请重填");
            return;
        }
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定保存</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/maintain/maintain_add',
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
</script><?php
