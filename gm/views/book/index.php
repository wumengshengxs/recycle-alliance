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
                            <div class="title">上门回收订单管理</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body form-group">
                            <div class="col-xs-2">
                                小区名称：<input type="text" id="name" class="form-control" onchange="get_table()" />
                            </div>
                            <div class="col-xs-2">
                                客户姓名：<input type="text" id="user_name" class="form-control" onchange="get_table()" />
                            </div>
                            <div class="col-xs-2">
                                客户电话：<input type="text" id="user_phone" class="form-control" onchange="get_table()" />
                            </div>
                            <div class="col-xs-2">订单状态：
                                <select class="form-control" id="order_status" onchange="get_table()">
                                    <option value="0">全部</option>
                                    <option value="1">待分配</option>
                                    <option value="2">待上门</option>
                                    <option value="3">已取消</option>
                                    <option value="4">已完成</option>
                                </select>
                            </div>
                            <div class="col-xs-2">超时状态：
                                <select class="form-control" id="timeout_status" onchange="get_table()">
                                    <option value="0">全部</option>
                                    <option value="1">超时</option>
                                    <option value="2">未超时</option>
                                </select>
                            </div>
                            <div class="col-xs-offset-8"></div>
                        </div>
                        <div class="panel-body form-group">
                            <div class="col-xs-2">排序条件：
                                <select class="form-control" id="order_by" onchange="get_table()">
                                    <option value="book_id">下单时间</option>
                                    <option value="now_time_out">超时时间</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                        <table class="datatable-book-list table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>操作</th>
                                <th>状态</th>
                                <th>下单时间</th>
                                <th>小区名称</th>
                                <th>地址</th>
                                <th>客户姓名</th>
                                <th>预约时间</th>
                                <th>回收员</th>
                                <th>派单时间</th>
                                <th>超时</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>操作</th>
                                <th>状态</th>
                                <th>下单时间</th>
                                <th>小区名称</th>
                                <th>地址</th>
                                <th>客户姓名</th>
                                <th>预约时间</th>
                                <th>回收员</th>
                                <th>派单时间</th>
                                <th>超时</th>
                                <th>备注</th>
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
        show_detail("分配回收员", "/book/change?order_id=" + order_id)/*show_detail是封装好的*/
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
    }
</script>