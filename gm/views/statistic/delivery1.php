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
                            <div class="title">投递数据统计报表</div>
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
                        <div class="panel-body"></div>
                        <!--datatable-data-delivery为class+控制器名称-->
                        <table class="datatable-statistic-delivery1 table table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="150">时间</th>
                                <th>区/县</th>
                                <th>街镇</th>
                                <th width="200">小区</th>
                                <th>纸类(公斤)</th>
                                <th>书籍</th>
                                <th>纺织</th>
                                <th>塑料</th>
                                <th>金属</th>
                                <th>总重</th>
                                <th>占比</th>
                                <th>饮料瓶(个)</th>
                                <th>占比</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>时间</th>
                                <th>区/县</th>
                                <th>街镇</th>
                                <th>小区</th>
                                <th>纸类</th>
                                <th>书籍</th>
                                <th>纺织</th>
                                <th>塑料</th>
                                <th>金属</th>
                                <th>总重</th>
                                <th>占比</th>
                                <th>饮料瓶（个）</th>
                                <th>占比</th>
                            </tr>
                            </tfoot>
                        </table>
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
}
</script>