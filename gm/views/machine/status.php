<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">


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
<body style="background-color:#fff;">
<div class="card" style="margin-bottom:0px">
    <div class="card-body">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>分类</th>
                        <th>容量状态</th>
                        <th>设备状态</th>
                        <th>重量</th>
                        <th>数量</th>
                        <th>容量</th>
                        <th>温度</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($srTrashCan as $v){?>
                    <tr>
                        <td><?=$v['can_name']?></td>
                        <?php if ($v['can_full'] == 0) {?>
                            <td>正常</td>
                        <?php }else if ($v['can_full'] == 1) {?>
                            <td>容量满</td>
                        <?php }else if ($v['can_full'] == 2) {?>
                            <td>超重</td>
                        <?php }?>
                        <?php if ($v['activation_status']  == 0 || $v['activation_status'] == 1 ) {?>
                        <td>正常</td>
                        <?php }else if ($v['activation_status']  == 2) {?>
                        <td>重量满箱</td>
                        <?php }else if ($v['activation_status']  == 3) {?>
                        <td>箱体故障</td>
                        <?php } ?>

                        <td><?=$v['weight']?></td>
                        <td><?=$v['count']?></td>
                        <td><?=$v['quantity']?></td>
                        <td><?=$v['temperature']?></td>
                        <td><a style="color: #05AAFE" href="javascript:(0)" onclick="restoration(<?= $v['id']?>)">复位</a></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <h4>&nbsp;&nbsp;回收统计</h4>
    <div  class="card-body">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>分类</th>
                        <th>重量/数量（公斤/个）</th>
                        <th>金额（元）</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $v){?>
                        <tr>
                            <td><?=$v['can_name']?></td>
                            <td><?=$v['recycling_amount']?></td>
                            <td><?=$v['recycling_pay']?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    function restoration(id){
        Dialog({
            showTitle: false,
            content: "<h5><b>是否要复位</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/machine/restoration',
                        type: 'post',
                        data: {id:id, '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'},
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", '复位成功');
                                parent.location.reload();
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
</body>
</html>