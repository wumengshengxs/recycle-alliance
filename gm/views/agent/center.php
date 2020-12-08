<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">企业中心</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">企业中心</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="justify-content-between text-center mb-0">
                            <img src="/concept/images/agent_logo.png">
                        </h4>
                        <h3 class="text-center"><?=$company_name?></h3>
                        <h5 class="text-center"><i class="far fa-building"></i> <?=$contact_address?></h5>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12"><h5><b>账户</b></h5></div>
                            <div class="col-12">
                                <h5><b>账户余额（元）</b></h5>
                                <h2 id="balance">计算中……</h2>
                            </div>
                            <div class="col-12">
                                <h5><b>累计发放环保金（元）</b></h5>
                                <h2 id="income">计算中……</h2>
                            </div>
                            <div class="col-12">
                                <h5>
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                    <span>（当<span style="color:red">余额低于100时</span>，回收机将全部离线，无法运营，请及时充值）</span>
                                </h5>
                            </div>
                            <div class="col-12" style="margin: 20px 0px">
                                <a href="/agent/recharge" class="btn btn-rounded btn-primary">点击充值</a>
                                <a href="/agent/remit" class="btn btn-rounded btn-primary">汇款通知记录</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="row">
                            <div class="col-12"><h5><b>设备</b></h5></div>
                            <div class="col-12">
                                <h3>机器数量: <?= $machine_num?></h3>
                                <h3>已激活机器数量: <?= $machine_activation_num?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="row">
                            <div class="col-12"><h5><b>公司信息</b></h5></div>
                        </div>
                        <p>统一社会信用代码：<b><?=$credit_code?></b></p>
                        <p>法人姓名：<b><?=$corporation?></b></p>
                        <p>法人身份证号码：<b><?=$id_card?></b></p>
                        <p>联系方式：<b><?=$mobile?></b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card" style="min-height:927px;">
                    <div class="card-header">
                        <h5 class="mb-0 col-12 col-md-2"><b>交易明细</b></h5>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <input type='text' class="" id='datetimepicker1' />
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="search_trade()">查询</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="all_trade()">全部</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="income_trade()">收入</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="expenses_trade()">支出</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" style="min-width:500px" class="table table-striped table-bordered second">
                                <thead id="dataTitle">
                                <tr>
                                    <th>操作</th>
                                    <th>金额</th>
                                    <th>明细</th>
                                    <th>状态</th>
                                    <th>操作时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function account(){
    $.ajax({
        url: '/data/ajax_account',
        type: 'get',
        data: {},
        dataType: 'json',
        success: function (data) {
            $('#balance').html(data.balance);
            $('#income').html(data.income);
        }
    });
}
window.onload = function() {
    window.account();
    var url = '/data/ajax_agent_trade';
    var params = {};
    window.dataTable(params, url);
}
var date = '';
function search_trade(){
    date = $('#datetimepicker1').val();
    var url = '/data/ajax_agent_trade';
    var params = {'date':date};
    window.dataTable(params, url);
}
function all_trade(){
    date = '';
    $('#datetimepicker1').val('');
    var url = '/data/ajax_agent_trade';
    var params = {};
    window.dataTable(params, url);
}
function income_trade(){
    var url = '/data/ajax_agent_trade';
    var params = {'type':1,'date':date};
    window.dataTable(params, url);
}
function expenses_trade(){
    var url = '/data/ajax_agent_trade';
    var params = {'type':2,'date':date};
    window.dataTable(params, url);
}
</script>