<div class="container-fluid">
    <div class="side-body">
        <div class="page-title">
            <span class="title"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">每日投递品类统计图表</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--<button type="button" onclick="get_excel();" class="btn btn-primary">导出报表</button>-->
                        <div class="panel-body form-group">
                            <div class="col-xs-2">开始日期：<input type="text" class="form-control" style="background:#fff;" readonly="readonly" id="datepicker_start" /></div>
                            <div class="col-xs-2">结束日期：<input type="text" class="form-control" style="background:#fff;" readonly="readonly" id="datepicker_end" /></div>
                            <div class="col-xs-2">街镇选择：
                                <select class="form-control" id="street">
                                    <option value="">请选择街镇</option>
                                    <?php foreach ($street as $v){?>
                                        <option value="<?=$v['street_name']?>"><?=$v['street_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-xs-2"><button class="btn btn-lg btn-info" onclick="get_table()">查找</button></div>
                            <div class="col-xs-offset-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">每日投递数据清单</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body"></div>
                        <!--datatable-data-delivery为class+控制器名称-->
                        <table class="datatable-statistic-category_chart table table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>街镇</th>
                                <th width="100">日期</th>
                                <th>回收物重量</th>
                                <th>饮料瓶个数</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>街镇</th>
                                <th>日期</th>
                                <th>回收物重量</th>
                                <th>饮料瓶个数</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">饮料瓶投递趋势</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body"></div>
                        <div class="card-body no-padding">
                            <canvas id="bar-chart" class="chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function get_table(){
    var delivery_start = $('#datepicker_start').val();
    var delivery_end = $('#datepicker_end').val();
    var street = $('#street').val();

    if(!street || !delivery_start || !delivery_end){
        alert('请选择日期区间和所属街镇');
        return;
    }

    var params = {
        'delivery_start':delivery_start,
        'delivery_end':delivery_end,
        'street':street
    };
    window.dataTable(params);


    window.bar_chart();
    /*
    $.ajax({
        url:'',
        type:'post',
        data:{},
        dataType:'json',
        success:function(){
            window.bar_chart();
        }
    });
     */
}
</script>