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

    <title>编辑回收商</title>
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
        <div>编辑回收商</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="area-title">编号 :  <span class=" col-12" ><?= $recyclers['id']?></span></div>
        </div>
        <div class="row">
            <div class="area-title">回收商姓名</div>
            <input type="text" id="nick_name" value="<?= $recyclers['nick_name']?>" class="form-control col-12"  />
        </div>
        <div class="row">
            <div class="area-title">手机号</div>
            <input type="text" id="phone_num" maxlength="11" value="<?= $recyclers['phone_num']?>" class="form-control col-12"/>
        </div>

        <div class="row ">
            <div class="area-title">账号状态</div>
            <div class="form-control col-12" style=" border: 0px">
                <label class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="status" <?php if ($recyclers['status'] == 0) {?> checked="" <?php }?> value="0" class="custom-control-input"><span class="custom-control-label">失效</span>
                </label>
                <label class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="status" <?php if ($recyclers['status'] == 1) {?> checked="" <?php }?> value="1" class="custom-control-input"><span class="custom-control-label">生效</span>
                </label>
            </div>
            <input type="hidden"  id="id" value="<?= $recyclers['id']?>" class="form-control col-12"  />
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
    function cancel() {
        window.parent.ifr_close();
    }

    //保存
    function save() {
        var data = {
            id : $('#id').val().trim(),
            nick_name : $('#nick_name').val().trim(),
            phone_num : $('#phone_num').val().trim(),
            status : $('input[name=status]:checked').val(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.nick_name == '') {
            Dialog.error("错误", '请输入姓名');
            return;
        }

        if (!(/^1[0-9]\d{9}$/).test(data.phone_num)) {
            Dialog.error("错误", '请输入正确的手机号');
            return;
        }


        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定编辑</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/external/external-recycler-edit',
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
