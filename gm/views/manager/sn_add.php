<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
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
        .fa-success{
            margin-top:10px;
            color:#28a745;
            display:none;
        }
        .fa-danger{
            margin-top:10px;
            color:#dc3545;
            display:none;
        }
    </style>
</head>

<body>
<div class="dashboard-main-wrapper">
    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <h5><b>新增激活码</b></h5>
            <div class="row" style="margin:20px">
                <input type="text" id="contract" placeholder="请输入合同编号" class="form-control col-10" />
                <div class="col-2">
                    <i id="contract_success" class="fas fa-check-circle fa-success"></i>
                    <i id="contract_danger" class="fas fa-minus-circle fa-danger"></i>
                </div>
            </div>
            <div class="row" style="margin:20px">
                <input type="text" id="machine_num" placeholder="请输入设备数量" class="form-control col-10" />
                <div class="col-2">
                    <i id="machine_num_success" class="fas fa-check-circle fa-success"></i>
                    <i id="machine_num_danger" class="fas fa-minus-circle fa-danger"></i>
                </div>
                <input type="hidden" id="agent_id" value="<?=$agent_id?>" />
            </div>
            <div class="row" style="line-height:62px;">
                <div id="cancel" class="col-6 text-center butt">取消</div>
                <div id="ok" class="col-6 text-center butt">确定</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>

//初始化表单提交错误信息
var submit = {
    contract:'请输入合同编号',
    machine_num:'请正确输入设备数量'
};

//合同文本框校验
$('#contract').change(function(){
    $('#contract_success').css('display','none');
    $('#contract_danger').css('display','none');
    if($(this).val() == ''){
        $('#contract_danger').css('display','block');
        $('#contract').css('border-color','#dc3545');
        Dialog.error( "错误", '请输入合同编号');
        submit.contract = '请输入合同编号';
    }
    else{
        var data = {'contract':$(this).val()};
        submit.contract = validate(data, 'contract');
    }
});

//设备数量文本框校验
$('#machine_num').change(function(){
    $('#machine_num_success').css('display','none');
    $('#machine_num_danger').css('display','none');
    if($(this).val() == ''
        || isNaN($(this).val())
        || Math.floor($(this).val()) != $(this).val()
        || $(this).val() == 0){
        $('#machine_num_danger').css('display','block');
        $('#machine_num').css('border-color','#dc3545');
        Dialog.error( "错误", '请正确输入设备数量');
        submit.machine_num = '请正确输入设备数量';
    }
    else{
        $('#machine_num').css('border-color', '#28a745');
        $('#machine_num_success').css('display','block');
        submit.machine_num = '';
    }
});

$('#cancel').click(function(){
    window.parent.close_ifr();
});

$('#ok').click(function(){
    //遍历错误信息并弹窗输出
    for(var k in submit){
        if(submit[k] != ''){
            Dialog.error( "错误", submit[k]);
            return;
        }
    }

    Dialog.waiting( "处理中，请等待..." );

    let data = {
        contract:$('#contract').val(),
        machine_num:$('#machine_num').val(),
        agent:$('#agent_id').val(),
        '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
    }

    $.ajax({
        url: '/manager/ajax_sn_add',
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(res){
            if(res.res) {
                Dialog.success("提示", '保存成功').ok(function () {
                    window.parent.close_ifr();
                    window.parent.location.href = '/manager/sn_list/?id=' + $('#agent_id').val();
                });
            }
            else{
                Dialog.error("错误", '系统错误，请联系管理员').ok(function(){
                    Dialog.close();
                });
            }
        }
    });

});

//校验联营方开户内容
function validate(data, id){
    var msg = '';
    $.ajax({
        url:'/manager/ajax_validate',
        type:'get',
        async:false,
        data:data,
        dataType:'json',
        success:function(res){
            if(res.status){
                $('#' + id).css('border-color', '#28a745');
                $('#' + id + '_success').css('display','block');
            }
            else{
                $('#' + id).css('border-color','#dc3545');
                $('#' + id + '_danger').css('display','block');
                Dialog.error( "错误", res.msg);
                msg = res.msg;
            }
        }
    });
    return msg;
}
</script>
</body>
</html>