<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">用户提现列表</h2>
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
                <h5><b>用户提现列表</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <select id="status" class="form-control " style="height: 42px">
                            <option value="">提现状态</option>
                            <option value="1">提现成功</option>
                            <option value="2">提现失败</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <input type="text" id="phone_num" placeholder="请输入手机号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['phone_num']) ? $_GET['phone_num'] : ''?>">
                        <div class="input-group-append">
                            <button type="button" id="search" onclick="get_table(1)" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="请选择导入报表的日期范围" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-4 form-group  col-sm-12 ">
                        <button type="button" class="btn btn-primary" onclick="get_excel()">导出报表</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>用户名称</th>
                            <th>用户手机号</th>
                            <th>订单号</th>
                            <th>订单金额(元)</th>
                            <th>订单状态</th>
                            <th>未成功原因</th>
                            <th>订单创建时间</th>
                            <th>订单修改时间</th>
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
        var url = '/data/ajax-user-order-withdraw';
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


    function get_table()
    {
        var status = $('#status').val();
        var phone_num = $('#phone_num').val();
        var params = {};
        if(status != ''){
            params.status = status;
        }
        if(phone_num != ''){
            params.phone_num = phone_num;
        }

        var url = '/data/ajax-user-order-withdraw';
        window.dataTable(params, url);
    }
    function get_excel()
    {
        var datetime = $('#date_time').val();
        if (!datetime) {
            Dialog.error("错误", '请选择日期,再点击此项!');
            return;
        }
        var params = 'date_time='+datetime;
        window.location.href = "/data/user-withdraw-excel?"+params;
    }

</script>