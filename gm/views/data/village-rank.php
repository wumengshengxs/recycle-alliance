<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">每月小区排名列表</h2>
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
                <h5><b>每月小区排名列表(默认不展示, 请筛选后点击查找)</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="排名月份" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="street_name" class="form-control " style="height: 42px">
                            <option value="">请选择街道/镇</option>
                            <?php foreach ($street as $v) { ?>
                                <option value="<?= $v['street_name']?>"><?= $v['street_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <div class="input-group-append">
                            <button type="button" id="search" onclick="get_table()" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>排行</th>
                            <th>小区名称</th>
                            <th>所属街道/镇</th>
                            <th>总金额</th>
                            <th>总重量</th>
                            <th>总投递次数</th>
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
        var url = '/data/ajax-village-rank';
        var params = {};
        window.dataTable(params, url);
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            //日期范围
            var year  = new Date().getFullYear();
            var month = new Date().getMonth();
            if (month == 0){
                year = year -1;
                month = 12;
            }
            if (month < 10){
                month = '0'+month
            }
            laydate.render({
                elem: '#date_time'
                ,type: 'month'
                ,max: -1
                ,value: year+"-"+month
                ,btns: ['clear', 'confirm']
            });
        });
    })


    function get_table()
    {
        var params = {
            date_time: $('#date_time').val(),
            street_name: $('#street_name').val()
        };
        //校验数据
        if (params.date_time == '') {
            Dialog.error("错误", '请选择时间');
            return;
        }
        if (params.street_name == '') {
            Dialog.error("错误", '请选择街道/镇');
            return;
        }
        var url = '/data/ajax-village-rank';
        window.dataTable(params, url);
    }


</script>
