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
                            <div class="title">区域管理</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body form-group">
                            <div class="col-xs-2">所属市：
                                <select class="form-control" id="order_by" onchange="get_table()">
                                    <option value="book_id">下单时间</option>
                                    <option value="now_time_out">超时时间</option>
                                </select>
                            </div>
                            <div class="col-xs-2">所属县/区域：
                                <select class="form-control" id="order_by" onchange="get_table()">
                                    <option value="book_id">下单时间</option>
                                    <option value="now_time_out">超时时间</option>
                                </select>
                            </div>
                            <div class="col-xs-2">所属镇/街道：
                                <select class="form-control" id="order_by" onchange="get_table()">
                                    <option value="book_id">下单时间</option>
                                    <option value="now_time_out">超时时间</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                        <table class="datatable-area-list table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>操作</th>
                                <th>所属区域</th>
                                <th>区域名称</th>
                                <th>区域编号</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>操作</th>
                                <th>所属区域</th>
                                <th>区域名称</th>
                                <th>区域编号</th>
                            </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    function order_detail(order_id) {
        // location.href =
        window.open('/book/detail?order_id=' + order_id)
        // show_detail("上门订单详情", "/book/detail?order_id=" + order_id)
    }

    function order_change(order_id) {
        show_detail("分配回收员", "/book/change?order_id=" + order_id)
    }

    function get_table(){
        var name = $('#name').val();
        var user_name = $('#user_name').val();
        var user_phone = $('#user_phone').val();
        var order_status = $('#order_status').val();
        var timeout_status = $('#timeout_status').val();
        var order_by = $('#order_by').val();
        var params = {
            'name': name,
            'user_name': user_name,
            'user_phone': user_phone,
            'order_status': order_status,
            'timeout_status': timeout_status,
            'order_by': order_by
        };
        window.dataTable(params);
    }

    function order_cancel(order_id) {
        show_detail("确认取消订单", "/book/cancel?order_id=" + order_id)
        //$.ajax({
        //    url:'/bookoption/cancel',
        //    type:'post',
        //    data:{order_id: order_id, "_csrf-gm": "<?//=Yii::$app->request->csrfToken?>//"},
        //    dataType:'json',
        //    success:function(res){
        //        // alert(res.msg)
        //        window.dataTable({});
        //    }
        //});
    }
</script>