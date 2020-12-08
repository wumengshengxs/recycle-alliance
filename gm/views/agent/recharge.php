<style>
.time_round{
    padding:10px 19px;
    border-radius:50%;
    border:solid 3px rgb(229 229 229);
    background-color: rgb(210 210 227);
    color: #fff !important;
}
.time_line{
    border: solid 2px rgb(210 210 227);
    height: 0px;
    margin-top: 22px;
}
.time_round_active{
    padding:10px 19px;
    border-radius:50%;
    border:solid 3px rgb(150 205 250);
    background-color: #05aafe !important;
    color: #fff !important;
}
.time_line_actvie{
    border: solid 2px #05aafe ;
    height: 0px;
    margin-top: 22px;
}
.step2, .step3{
    display: none;
}
#t_blank, #t_amount{
    color: #05aafe;
}
</style>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">账户充值</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">账户充值</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row text-center step1" style="margin:auto;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
            <div class="col-lg-2"></div>
            <span class="time_round_active">1</span>
            <div class="col-3 time_line"></div>
            <span class="time_round">2</span>
            <div class="col-3 time_line"></div>
            <span class="time_round">3</span>
        </div>
    </div>
</div>
<div class="row text-center step2" style="margin:auto;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
            <div class="col-lg-2"></div>
            <span class="time_round_active">1</span>
            <div class="col-3 time_line_actvie"></div>
            <span class="time_round_active">2</span>
            <div class="col-3 time_line"></div>
            <span class="time_round">3</span>
        </div>
    </div>
</div>
<div class="row text-center step3" style="margin:auto;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
            <div class="col-lg-2"></div>
            <span class="time_round_active">1</span>
            <div class="col-3 time_line_actvie"></div>
            <span class="time_round_active">2</span>
            <div class="col-3 time_line_actvie"></div>
            <span class="time_round_active">3</span>
        </div>
    </div>
</div>

<div class="row" style="margin-top:50px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
            <div class="card step1" style="width:100%">
                <div class="card-header">
                    <h3 class="text-primary"><b>1. 小松鼠环保金收款信息</b></h3>
                </div>
                <div class="card-body">
                    <label>收款企业名称</label>
                    <input class="form-control" type="text" onclick="copyText(this)" readonly value="小松鼠（上海）环保科技有限公司" />
                </div>
                <div class="card-body">
                    <label>收款企业账号</label>
                    <input class="form-control" type="text" onclick="copyText(this)" readonly value="" />
                </div>
                <div class="card-body">
                    <label>收款企业开户行</label>
                    <input class="form-control" type="text" onclick="copyText(this)" readonly value="交通银行股份有限公司上海三门路支行" />
                </div>
                <div class="card-body">
                    <label>收款企业开户行行号</label>
                    <input class="form-control" type="text" onclick="copyText(this)" readonly value="" />
                </div>
                <div class="card-body">
                    <a href="javascript:void(0);" class='btn btn-primary' onclick="next(2)">已汇款，进入下一步</a>
                </div>
            </div>

            <div class="card step2" style="width:100%">
                <div class="card-header">
                    <h3 class="text-primary"><b>2. 提交汇款通知</b></h3>
                </div>
                <div class="card-body">
                    <label><span style="color:red"> * </span>汇款金额</label>
                    <input class="form-control" id="amount" type="text" value="" />
                </div>
                <div class="card-body">
                    <label><span style="color:red"> * </span>汇款银行</label>
                    <input class="form-control" id="bank_name" type="text" value="" />
                </div>
                <div class="card-body">
                    <label>转账附言</label>
                    <input class="form-control" placeholder="如所属企业开户行与打款银行不符需填写说明" id="content" type="text" value="" />
                </div>
                <div id="container" class="card-body">
                    <a id="selectfiles" href="javascript:void(0);" class='btn btn-info'>选择转账凭证图片</a>
                    <a id="postfiles" href="javascript:void(0);" class='btn btn-info'>上传转账凭证图片</a>
                    <div id="ossfile"></div>
                    <input id="dirname" type="hidden" value="" />
                    <input id="receipt_url" type="hidden" value="" style="width:100%" />
                    <img id="receipt_img" src="" width="150" />
                </div>
                <div class="card-body">
                    <input id="agent" type="hidden" value="<?=$id?>" />
                    <input id="csrf" type="hidden" value="<?=yii::$app->request->csrfToken;?>" />
                    <a href="javascript:void(0);" class='btn btn-primary' onclick="next(3)">提交汇款通知</a>
                </div>
            </div>

            <div class="card step3" style="width:100%">
                <div class="card-header">
                    <h3 class="text-primary"><b>1. 等待汇款到账</b></h3>
                    <p><?=date('Y年m月d日')?>从<span id="t_blank"></span>汇款金额<span id="t_amount"></span>元，工作人员将在3个工作日内处理，请耐心等待</p>
                </div>
                <div class="card-body">
                    <a href="/agent/recharge" class='btn btn-primary'>我要填写新通知</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="/oss/lib/crypto1/crypto/crypto.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/hmac/hmac.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/sha1/sha1.js"></script>
<script type="text/javascript" src="/oss/lib/base64.js"></script>
<script type="text/javascript" src="/oss/lib/plupload-2.1.2/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/oss/upload.js"></script>

<script>
function next(id){
    var result = true;
    if(id == 3){
        var amount =  $('#amount').val();
        var bank_name =  $('#bank_name').val();
        var content =  $('#content').val();
        var receipt_url =  $('#receipt_url').val();
        var agent =  $('#agent').val();
        var csrf = $('#csrf').val();
        if(!amount || isNaN(amount)){
            Dialog.error( "错误", "请正确填写汇款金额" );
            return;
        }
        if(!bank_name){
            Dialog.error( "错误", "请正确填写汇款银行" );
            return;
        }
        if(!receipt_url){
            Dialog.error( "错误", "请上传转账凭证" );
            return;
        }
        $.ajax({
            url:'/agent/ajax_recharge',
            type:'post',
            data:{'agent':agent,'amount':amount,'bank_name':bank_name,'content':content,'receipt_url':receipt_url,'_csrf-gm':csrf},
            async:false,
            dataType:'json',
            success:function(res){
                result = res.res;
                if(!result){
                    Dialog.error( "错误", "申请失败，请联系管理员" );
                    return;
                }
                $('#t_blank').html(bank_name);
                $('#t_amount').html(amount);
            }
        });
    }
    if(result) {
        for (var i = 1; i <= 3; i++) {
            $('.step' + i).hide();
        }
        $('.step' + id).show();
    }
}

function copyText(is) {
    $(is).select();
    document.execCommand('Copy'); // 执行浏览器复制命令
    Dialog.success("提示", '复制成功');
}
</script>