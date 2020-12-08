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
<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-header">
        <h5>
            <b>提交到账回执</b>
            <i id="close" style="float:right;cursor:pointer"><svg viewBox="0 0 1024 1024" version="1.1" width="16" height="16"><path d="M806.4 172.8l-633.6 633.6c-12.8 12.8-12.8 32 0 44.8 12.8 12.8 32 12.8 44.8 0l633.6-633.6c12.8-12.8 12.8-32 0-44.8-12.8-12.8-32-12.8-44.8 0z" fill="#000"></path><path d="M172.8 172.8c-12.8 12.8-12.8 32 0 44.8l633.6 633.6c12.8 12.8 32 12.8 44.8 0 12.8-12.8 12.8-32 0-44.8L217.6 172.8c-12.8-12.8-32-12.8-44.8 0z" fill="#000"></path></svg></i>
            <div style="clear:both"></div>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                        <tr>
                            <th>联营方名称</th>
                            <th>联营方编号</th>
                            <th>汇款银行</th>
                            <th>汇款金额</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$agent['company_name']?></td>
                            <td><?=get_shot_guid($agent['uuid'])?></td>
                            <td><?=$srAgentTradeHistory['bank_name']?></td>
                            <td><?=$srAgentTradeHistory['amount']?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row row-input">
            <label>到账日期</label>
            <input type="text" id="" value="<?=date('Y-m-d')?>" readonly class="form-control col-12" />
        </div>
        <div class="row row-input">
            <label>银行流水号(选填)</label>
            <input type="text" id="" placeholder="输入银行流水号" class="form-control col-12" />
        </div>

        <div class="row row-input">
            <a href="javascript:void(0)" onclick="show_img()">
                <img id="image" style="height:200px" src="<?=$srAgentTradeHistory['receipt_url']?>" />
            </a>
        </div>

        <div class="row row-input">
            <label>到账金额</label>
            <input type="text" id="amount" placeholder="输入实际到账金额" class="form-control col-12" />
        </div>

        <div class="row row-button">
            <input type="hidden" id="id" value="<?=$srAgentTradeHistory['id']?>" />
            <input type="hidden" id="amount_num" value="<?=$srAgentTradeHistory['amount']?>" />
            <div id="cancel" class="col-6 text-center butt">驳回</div>
            <div id="ok" class="col-6 text-center butt">确定</div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
function show_img(){
    var src = $('#image').attr('src');
    window.parent.open(src);
}

//审核操作
jQuery(document).ready(function($){
    //关闭操作框
    $('#close').click(function(){
        window.parent.close_iframe();
    });

    //驳回
    $('#cancel').click(function(){
        var id = $('#id').val();
        var data = {
            id: id,
            status: 2,
            '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
        };
        verify('是否确认驳回', data);
    });

    //过审
    $('#ok').click(function(){
        var id = $('#id').val();
        var amount = $('#amount').val();
        var amount_num = $('#amount_num').val();
        if(amount == ''){
            Dialog.error('错误', '请输入到账金额');
            return;
        }
        if(amount != amount_num){
            Dialog.error('错误', '到账金额与转账金额不符');
            return;
        }

        var data = {
            id: id,
            amount: amount,
            status: 1,
            '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
        };
        verify('是否确认过审', data);
    });

    //审核操作
    function verify(content, data){
        Dialog({
            showTitle: false,
            content: "<h5><b>" + content + "</b></h5>",
            ok: {
                callback: function () {
                    $('.mini-dialog-container').remove();
                    Dialog.waiting( "处理中，请等待..." );
                    $.ajax({
                        url:'/manager/ajax_verify',
                        type:'post',
                        data:data,
                        dataType:'json',
                        success:function(res){
                            $('.mini-dialog-container').remove();
                            if(res.res) {
                                Dialog.success("提示", '操作成功').ok(function () {
                                    window.parent.location.href = '/manager/recharge_list';
                                });
                            }
                            else{
                                Dialog.error('错误', '请联系管理员');
                            }
                        }
                    });
                }
            },
            cancel:{}
        });
    }
});
</script>
</body>
</html>