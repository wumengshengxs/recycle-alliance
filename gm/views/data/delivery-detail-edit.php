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

    <title>修改投递信息</title>
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
    <div class="card-body" style="padding: 10px 20px 10px 20px;">
        <div class="row" >
            <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0">&nbsp;&nbsp;&nbsp; 投递ID: <?= $detail['id']?></div>
        </div>
        <div class="row">
            <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0">投递金额: <?= $detail['delivery_income']?></div>
        </div>
    </div>
    <div class="card-body"  style="padding-top: 0">
        <div class="row">
            <div class="area-title">投递品类</div>
            <select  id="trash_id"  class="form-control"  >
                <?php if ($detail['declarable_status'] == 0){  ?>
                    <?php foreach ($category as $item) { ?>
                        <option data-category="<?= $item['category']?>" value="<?= $item['id'] ?>" <?php if ($detail['delivery_type'] == $item['category'] && $detail['can_name'] == $item['can_name']) {?> selected <?php } ?>><?= $item['can_name'] ?></option>
                    <?php } ?>
                <?php }?>
            </select>
        </div>
        <div class="row">
            <div class="area-title">投递重量kg/数量个</div>
            <input type="number" id="delivery_count" maxlength="10" value="<?= $detail['delivery_count'] ?>" class="form-control col-12"/>
        </div>

        <div class="row row-button">
            <input type="hidden" id="old_trash_id"  value="" class="form-control col-12"/>
            <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
            <div id="save" class="col-6 text-center butt" onclick="save(<?=  $detail['id'] ?>)">确定</div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>

<script type="text/javascript">

    $(function(){
        $("#old_trash_id").val($("#trash_id").find("option:selected").val())
    });
    function cancel() {
        window.parent.ifr_close();
    }

    //保存
    function save(id) {
        var data = {
            id : id,
            old_trash_id: $("#old_trash_id").val(),
            trash_id: $("#trash_id").find("option:selected").val(),
            delivery_count: $("#delivery_count").val(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        if (data.delivery_count == '' || data.delivery_count <= 0){
            Dialog.error("错误", '请输入重量');
            return;
        }

        if ($("#trash_id").find("option:selected").attr('data-category') == 1){
            data.delivery_count = parseInt(data.delivery_count);
        }
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定将本记录修改为: "+$("#trash_id").find("option:selected").html()+" 重量/数量为: "+data.delivery_count+"</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/data/delivery-detail-edit',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", '操作成功');
                                setTimeout(function(){
                                    parent.location.reload();
                                    // window.parent.ifr_close();
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
