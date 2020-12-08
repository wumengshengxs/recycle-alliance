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
        .row-input{
            margin: 0px 0px 0px 20px;
        }
    </style>
</head>
<body style="background-color:#fff;">
<div class="card" style="margin-bottom:0px">
    <div class="card-body">
        <div class="row row-input">
            <table style="width:100%">
                <tr class="something">
                    <td style="width:33%"><b>分类</b></td>
                    <td><b>用户投递单价(元/公斤)</b></td>
                    <td><b>回收取货单价(元/公斤)</b></td>
                </tr>
                <?php foreach ($srTrashCan as $v){?>
                <tr>
                    <td><?=$v['can_name']?></td>
                    <td><input type="text" onchange="recycle_price('<?=$id?>', <?=$v['category']?>, this)" class="form-control" style="width:50%" value="<?=$v['recycle_price']?>" /></td>
                    <td><input type="text" onchange="sale_price('<?=$id?>', <?=$v['category']?>, this)" class="form-control" style="width:50%" value="<?=$v['sale_price']?>" /></td>
                </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
function recycle_price(id, category, obj){
    var price = $(obj).val();
    edit(id, category, price, 'recycle_price');
}
function sale_price(id, category, obj){
    var price = $(obj).val();
    edit(id, category, price, 'sale_price');
}
function edit(id, category, price, type){
    if(isNaN(price)){
        Dialog.error('错误', '价格必须是数字');return;
    }
    if(price < 0){
        Dialog.error('错误', '价格必须大于0');return;
    }
    price = parseFloat(price);
    if(price != price.toFixed(2)){
        Dialog.error('错误', '只能允许2位小数');return;
    }

    var data = {
        id:id,
        category:category,
        price:price,
        type:type,
        '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
    };
    $.ajax({
        url: '/machine/ajax_price',
        type: 'post',
        data: data,
        dataType:'json',
        success:function (res) {
            
        }
    })
}
</script>
</body>
</html>