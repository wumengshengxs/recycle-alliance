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

    <title>添加备注</title>
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
        <div>添加备注</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="area-title">备注</div>
            <textarea id="remark" class="form-control col-12"><?= $user['remarks']?></textarea>
        </div>

        <div class="row row-button">
            <input type="hidden" id="ids" value="<?=$user['id']?>">
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

    //保存
    function save() {
        var data = {
            id : $('#ids').val().trim(),
            remark : $('#remark').val().trim(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.remark == '') {
            Dialog.error("错误", '请输入备注');
            return;
        }

        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定添加备注</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/data/user-remark',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", res.msg);
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
