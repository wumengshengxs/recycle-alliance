<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">财务管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">财务管理</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="card step1" style="width:100%">
        <div class="card-header">
            <h3 class="text-primary"><b>小松鼠环保金收款信息</b></h3>
        </div>
        <div class="card-body">
            <label>收款企业名称</label>
            <input class="form-control" id="company_name" placeholder="收款企业名称" type="text" value="小松鼠（上海）环保科技有限公司" />
        </div>
        <div class="card-body">
            <label>收款企业账号</label>
            <input class="form-control" id="bank_account" placeholder="收款企业账号" type="text" value="<?=$bank_account?>" />
        </div>
        <div class="card-body">
            <label>收款企业开户行</label>
            <input class="form-control" id="bank_name" placeholder="收款企业开户行" type="text" value="<?=$bank_name?>" />
        </div>
        <div class="card-body">
            <label>收款企业开户行行号</label>
            <input class="form-control" id="bank_number" placeholder="收款企业开户行行号" type="text" value="<?=$bank_number?>" />
        </div>
        <div class="card-body">
            <input type="hidden" id="finance_id" value="<?=$id?>" />
            <a href="javascript:void(0);" class='btn btn-primary' onclick="save()">保存</a>
        </div>
    </div>
</div>

<script>
function save(){
    var data = {
        company_name:$('#company_name').val(),
        bank_account:$('#bank_account').val(),
        bank_name:$('#bank_name').val(),
        bank_number:$('#bank_number').val(),
        finance_id:$('#finance_id').val(),
        '_csrf-gm':'<?=Yii::$app->request->csrfToken?>'
    };
    //校验数据
    if(data.company_name == ''){
        Dialog.error("错误", '请输入收款企业名称');
        return;
    }
    if(data.bank_account == ''){
        Dialog.error("错误", '请输入收款企业账号');
        return;
    }
    if(data.bank_name == ''){
        Dialog.error("错误", '请输入收款企业开户行');
        return;
    }
    if(data.bank_number == ''){
        Dialog.error("错误", '请输入收款企业开户行行号');
        return;
    }

    Dialog({
        showTitle: false,
        content: "<h5><b>是否确定保存</b></h5>",
        ok: {
            callback: function () {
                $('.mini-dialog-container').remove();
                Dialog.waiting( "处理中，请等待..." );
                $.ajax({
                    url:'/manager/ajax_finance',
                    type:'post',
                    data:data,
                    dataType:'json',
                    success:function(res){
                        $('.mini-dialog-container').remove();
                        console.log(res)
                        if(res.res) {
                            Dialog.success("提示", '操作成功');
                        }
                        else{
                            Dialog.error("错误", '请联系管理员');
                        }
                    }
                });
            }
        }
    });
}
</script>