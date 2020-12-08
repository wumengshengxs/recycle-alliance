<style>
    .md1{min-height:440px}
    .md2{margin:10px 0px;border-bottom: 1px solid #e6e6f2;}
    .md3{margin-top:10px;}
    .md4{margin:5px 0px;width:118px;}
</style>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">首页 </h2>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">首页</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="ecommerce-widget">
    <div class="row">
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card md1">
                <h5 class="card-header"><b>账户</b></h5>
                <div class="card-body">
                    <div class="row">
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
                                <span id="waring"></span>
                            </h5>
                        </div>
                        <div class="col-12" style="margin: 20px 0px">
                            <a href="/agent/center" class="btn btn-rounded btn-primary">企业中心</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card md1">
                <h5 class="card-header">
                    <div class="row">
                        <b class="col-6">运营数据</b>
                        <b class="col-6 text-right">
                            <a id="month" class="btn btn-xs btn-rounded btn-primary text-white">查看月度数据</a>
                            <a id="day" class="btn btn-xs btn-rounded btn-primary text-white" style="display:none">查看7天数据</a>
                        </b>
                    </div>
                    <div class="row md3"></div>
                    <div class="row">
                        <b class="col-12 text-center">
                            <a id="deliver" class="btn btn-xs btn-rounded btn-danger text-white">投递</a>
                            <a id="person" class="btn btn-xs btn-rounded btn-warning text-white">人数</a>
                            <a id="weight" class="btn btn-xs btn-rounded btn-success text-white">重量</a>
                            <a id="recycle" class="btn btn-xs btn-rounded btn-info text-white">清运</a>
                        </b>
                    </div>
                </h5>
                <div id="morris_line" class="text-center"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card md1">
                <h5 class="card-header"><b>运营累计数据</b></h5>
                <div class="card-body">
                    <div class="row md2">
                        <h5 class="col-sm-6"><i class="col-1 fas fa-circle text-danger"></i><b> 总投递次数</b></h5>
                        <h5 id="sum_d" class="col-sm-6">计算中……</h5>
                    </div>
                    <div class="row md2">
                        <h5 class="col-sm-6"><i class="col-1 fas fa-circle text-warning"></i><b> 总投递人数</b></h5>
                        <h5 id="sum_p" class="col-sm-6">计算中……</h5>
                    </div>
                    <div class="row md2">
                        <h5 class="col-sm-6"><i class="col-1 fas fa-circle text-success"></i><b> 总投递重量</b></h5>
                        <h5 id="sum_w" class="col-sm-6">计算中……</h5>
                    </div>
                    <div class="row md2">
                        <h5 class="col-sm-6"><i class="col-1 fas fa-circle text-info"></i><b> 总清运次数</b></h5>
                        <h5 id="sum_r" class="col-sm-6">计算中……</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--折线图效果区-->
    <div class="row">
        <?php $key_id = ['sparkline-revenue0', 'sparkline-revenue1', 'sparkline-revenue2', 'sparkline-revenue3']?>
        <?php $label = ['已铺设设备(组)', '投递总次数（次）', '投递总人数（人）', '投递总重量（公斤）']?>
        <?php $i = 0?>
        <?php foreach ($key_id as $v){?>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted"><?=$label[$i]?></h5>
                    <div class="metric-value d-inline-block">
                        <h1 id="sum_<?=$i;?>" class="mb-1">计算中……</h1>
                    </div>
                    <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                        <!--<span><i class="fa fa-fw fa-arrow-up"></i></span><span>5.86%</span>-->
                    </div>
                </div>
                <div id="<?=$v?>"></div>
            </div>
        </div>
        <?php $i++;}?>
    </div>
    <!--折线图效果区-->

    <!--环保指数效果区-->
    <div class="row">
        <?php $label = ['节约标准煤(公斤）', '减少垃圾焚烧(公斤）', '垃圾无害化总量(公斤）', '减少垃圾填埋(公斤）', '减少炭排放(公斤）', '垃圾减量化总量(公斤）']?>
        <?php $i = 0?>
        <?php foreach ($label as $v){?>
        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
            <div class="card border-3 border-top border-top-primary">
                <div class="card-body">
                    <h5 class="text-muted"><?=$v?></h5>
                    <div class="metric-value d-inline-block">
                        <h1 id="ep_<?=$i?>" class="mb-1">计算中……</h1>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++;}?>
    </div>
    <!--环保指数效果区-->


    <div class="row">
        <!-- ============================================================== -->
        <!-- data table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="get_table('/data/ajax_category')">各品类回收量</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="get_table('/data/ajax_delivery')">实时投递记录</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="get_table('/data/ajax_recycle')">清运记录</a>
                            <a class="btn btn-primary md4" href="javascript:void(0)" onclick="get_table('/data/ajax_full_can')">满箱状态监控</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable_7" class="table table-striped table-bordered second" style="display:none">
                            <thead id="dataTitle_7">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <table id="dataTable_6" class="table table-striped table-bordered second" style="display:none">
                            <thead id="dataTitle_6">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <table id="dataTable_8" class="table table-striped table-bordered second" style="display:none">
                            <thead id="dataTitle_8">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end data table  -->
        <!-- ============================================================== -->
    </div>

</div>
<script>
//列表js逻辑
function get_table(url) {
    var t1 = "<tr>" +
        "<th>品类</th>" +
        "<th>今日</th>" +
        "<th>昨日</th>" +
        "<th>本周</th>" +
        "<th>本月</th>" +
        "<th>年度</th>" +
        "<th>累计</th>" +
        "</tr>";
    var t2 = "<tr>" +
        "<th>用户</th>" +
        "<th>地区</th>" +
        "<th>地区</th>" +
        "<th>投递品类</th>" +
        "<th>环保金</th>" +
        "<th>历史投递</th>" +
        "<th>累计环保金</th>" +
        "</tr>";
    var t3 = "<tr>" +
        "<th>清运时间</th>" +
        "<th>回收员</th>" +
        "<th>地区</th>" +
        "<th>小区</th>" +
        "<th>清运品类</th>" +
        "<th>清运数量/个数</th>" +
        "</tr>";
    var t4 = "<tr>" +
        "<th>机器编号</th>" +
        "<th>地区</th>" +
        "<th>机器地址</th>" +
        "<th>满箱种类</th>" +
        "<th>重量占比</th>" +
        "<th>容量占比</th>" +
        "<th>负责人</th>" +
        "<th>手机号码</th>" +
        "</tr>";
    $('.table').each(function(){
        $(this).hide();
    });
    var column = 0;
    if(url == '/data/ajax_category'){
        column = 7;
        $('#dataTitle_' + column).html(t1);
    }
    else if(url == '/data/ajax_delivery'){
        column = 7;
        $('#dataTitle_' + column).html(t2);
    }
    else if(url == '/data/ajax_recycle'){
        column = 6;
        $('#dataTitle_' + column).html(t3);
    }
    else if(url == '/data/ajax_full_can'){
        column = 8;
        $('#dataTitle_' + column).html(t4);
    }
    $('.dataTables_wrapper .top').remove();
    $('.dataTables_wrapper .bottom').remove();
    var datatable = $('#dataTable_' + column);
    datatable.show();
    var params = {};
    window.dataTable(params, url, datatable);
}



//图表js逻辑
function data(params) {
    //封装为ajax获取后台数据
    $.ajax({
        url:'/data/ajax_chart',
        type:'get',
        data:params,
        dataType:'json',
        success:function(res){
            var ret = [];
            var day;
            for(var i in res.data){
                day = i.split('-').pop();
                ret.push({
                    x:day + '日',
                    a:res.data[i]
                })
            }
            $('#morris_line').html('');
            var morris_line = Morris.Line({
                element: 'morris_line',
                behaveLikeLine: true,
                data: [],
                xkey: 'x',
                ykeys: ['a'],
                labels: charts_label,
                lineColors: charts_color,
                hideHover:true,
                parseTime: false
            });
            morris_line.setData(ret);
            $('#sum_d').html(res.delivery_sum + '<b>（次）</b>');
            $('#sum_p').html(res.person_sum + '<b>（人）</b>');
            $('#sum_w').html(res.weight_sum + '<b>（公斤）</b>');
            $('#sum_r').html(res.recycle_sum + '<b>（次）</b>');
        }
    });
}

function account(){
    $.ajax({
        url: '/data/ajax_account',
        type: 'get',
        data: {},
        dataType: 'json',
        success: function (data) {
            $('#balance').html(data.balance);
            if (data.balance <= 199 && data.balance >= 100){
                $('#waring').html('<i class="fas fa-exclamation-circle text-danger"></i>\n当<span style="color:red">余额低于100元时</span><span>，回收机将全部离线，无法运营。请及时充值</span>');
            }
            if (data.balance <= 99){
                $('#waring').html('<span style="color:red">余额已低于100元</span><span>，回收机已全部离线，无法运营。请及时充值</span>');
            }
            $('#income').html(data.income);
        }
    });
}

function sparkline(){
    $.ajax({
        url:'/data/ajax_sum',
        type:'get',
        data:{},
        dataType:'json',
        success:function(data){
            //循环输出总量数据
            for(var i = 0; i < 4; i++){
                $("#sparkline-revenue" + i).sparkline(data.charts[i], {
                    type: 'line',
                    width: '99.5%',
                    height: '100',
                    lineColor: '#5969ff',
                    fillColor: '#dbdeff',
                    lineWidth: 2,
                    spotColor: undefined,
                    minSpotColor: undefined,
                    maxSpotColor: undefined,
                    highlightSpotColor: undefined,
                    highlightLineColor: undefined,
                    resize: true
                });
                $("#sum_" + i).html(data.sum[i]);
            }
            //循环输出环保数据
            for(var i = 0; i < 6; i++){
                $("#ep_" + i).html(data.ep[i]);
            }
        }
    });
}

//用于记录请求图表数据的全局参数
var period;
var charts_type;
var charts_label;
var charts_color;

//加载完毕后运行
window.onload = function(){
    get_table('/data/ajax_category');

    //加载动图停留毫秒数
    sleep = 1000;
    //默认为总投递次数数据
    period = 'day';
    charts_type = 1;
    charts_label = ['总投递次数'];
    charts_color = ['#ef172c'];
    $('#morris_line').html('<img src="/concept/images/loading.gif" style="width:80%">');
    var params = {'period':period, 'charts_type':charts_type};
    setTimeout(function(){data(params);}, sleep);
    setTimeout(function(){sparkline();}, sleep);
    setTimeout(function(){account();}, sleep);

    //折线图各标签切换显示数据
    $('#deliver, #person, #weight, #recycle').click(function(){
        $('#morris_line').html('<img src="/concept/images/loading.gif" style="width:80%;">');
        var params = {};
        if($(this).attr('id') == 'deliver'){
            charts_label = ['总投递次数']; charts_color = ['#ef172c'];
            charts_type = 1;
        }
        else if($(this).attr('id') == 'person'){
            charts_label = ['总投递人数']; charts_color = ['#ffc107'];
            charts_type = 2;
        }
        else if($(this).attr('id') == 'weight'){
            charts_label = ['总投递重量']; charts_color = ['#2ec551'];
            charts_type = 3;
        }
        else if($(this).attr('id') == 'recycle'){
            charts_label = ['总清运次数']; charts_color = ['#25d5f2'];
            charts_type = 4;
        }
        params = {'period':period, 'charts_type':charts_type};
        setTimeout(function(){data(params);}, sleep);
    });

    //折线周期切换显示数据与总计
    $('#day, #month').click(function(){
        $('#morris_line').html('<img src="/concept/images/loading.gif" style="width:80%;">');
        if($(this).attr('id') == 'day'){
            period = 'day';
            $(this).hide();
            $('#month').show();
        }
        else{
            period = 'month';
            $(this).hide();
            $('#day').show();
        }
        params = {'period':period, 'charts_type':charts_type};
        $('#sum_d').html('计算中……');
        $('#sum_p').html('计算中……');
        $('#sum_w').html('计算中……');
        $('#sum_r').html('计算中……');
        setTimeout(function(){data(params);}, sleep);
    });
}

</script>