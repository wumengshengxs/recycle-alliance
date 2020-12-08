<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">用户环保金明细列表</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">数据统计管理</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5><b>用户环保金明细列表</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="注册日期" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <input type="text" id="phone_num" placeholder="请输入手机号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['phone_num']) ? $_GET['phone_num'] : ''?>">
                        <div class="input-group-append">
                            <button type="button" id="search" onclick="get_table(1)" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 form-group  col-sm-12 ">
                        <input type="hidden" id="order" value="1">
                        <button type="button" id="add"  class="btn btn-primary" onclick="get_excel()">导出报表</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>用户ID</th>
                            <th>用户手机号</th>
                            <th>昵称</th>
                            <th>环保金累计金额<a onclick="get_table(1)"><b> ↑</b></a>  <a  onclick="get_table(2)"><b>↓</b></a ></th>
                            <th>当前可提现金额<a onclick="get_table(3)"><b> ↑</b></a>  <a  onclick="get_table(4)"><b>↓</b></a ></th>
                            <th>当前待审核金额</th>
                            <th>注册时间</th>
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

<script src="/js/region.js"></script>

<script>

    $(function(){
        var url = '/data/ajax-user-statistics';
        var params = {};
        window.dataTable(params, url);
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            //日期范围
            laydate.render({
                elem: '#date_time'
                ,range: true
            });
        });
    });


    function get_table(order)
    {
        var date_time = $('#date_time').val();
        var phone_num = $('#phone_num').val();
        var params = {};
        params.order = order;
        $('#order').val(order);
        if(date_time != ''){
            params.date_time = date_time;
        }
        if(phone_num != ''){
            params.phone_num = phone_num;
        }

        var url = '/data/ajax-user-statistics';
        window.dataTable(params, url);
    }

    function get_excel()
    {
        var datetime = $('#date_time').val();
        var phone_num = $('#phone_num').val();
        var order = $('#order').val();

        var params = 'date_time='+datetime+'&phone_num='
            +phone_num+'&order='+order;

        window.location.href = "/data/user-statistics-excel?"+params;
    }



</script>