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
                            <div class="title">用户投递明细</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--<button type="button" onclick="get_excel();" class="btn btn-primary">导出报表</button>-->
                        <div class="panel-body form-group">
                            <div class="col-xs-2">开始日期：<input type="text" class="form-control" style="background:#fff;" readonly="readonly" id="datepicker_start" /></div>
                            <div class="col-xs-2">结束日期：<input type="text" class="form-control" style="background:#fff;" readonly="readonly" id="datepicker_end" /></div>
                            <div class="col-xs-2">品类选择：
                                <select class="form-control" id="delivery_type">
                                    <option value="">全部品类</option>
                                    <?php foreach ($srRubbishCategory as $v){?>
                                        <option value="<?=$v['id']?>"><?=$v['category_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-xs-2"><button class="btn btn-lg btn-info" onclick="get_table()">查找</button></div>
                            <div class="col-xs-offset-6"></div>
                        </div>
                        <div class="panel-body"></div>
                        <!--datatable-data-delivery为class+控制器名称-->
                        <table class="datatable-statistic-category table table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>投递id</th>
                                <th>用户id</th>
                                <th>用户手机</th>
                                <th>品类</th>
                                <th>数量</th>
                                <th>环保金</th>
                                <th>投递时间</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>投递id</th>
                                <th>用户id</th>
                                <th>用户手机</th>
                                <th>品类</th>
                                <th>数量</th>
                                <th>环保金</th>
                                <th>投递时间</th>
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
    var delivery_type = $('#delivery_type').val();

    var params = {
        'delivery_start':delivery_start,
        'delivery_end':delivery_end,
        'delivery_type':delivery_type
    };
    window.dataTable(params);
}
</script>