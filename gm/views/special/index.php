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
                            <div class="title">异常箱体订单管理</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body form-group">
                            <div class="col-xs-2">
                                小区名称：<input type="text" id="name" class="form-control" onchange="get_table()" />
                            </div>
                            <div class="col-xs-2">回收员姓名：
                                <select class="form-control" id="recycler_id" onchange="get_table()">
                                    <option value="0">全部</option>
                                    <?php foreach ($recycles as $key => $value) { ?>
                                    <option value="<?=$key?>"><?=$value?></option>
                                    <?php } ?>
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
<!--                        </div>-->
<!--                        <div class="panel-body form-group">-->
                            <div class="col-xs-2">申报订单状态：
                                <select class="form-control" id="order_status" onchange="get_table()">
                                    <option value="0">全部</option>
                                    <option value="1">待处理</option>
                                    <option value="2">已处理</option>
                                </select>
                            </div>
                            <div class="col-xs-2">排序条件：
                                <select class="form-control" id="order_by" onchange="get_table()">
                                    <option value="id">申报时间</option>
                                    <option value="now_time_out">超时时间</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                        <table class="datatable-special-can_list table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>操作</th>
                                <th>审核超时时间</th>
                                <th>申报编号</th>
                                <th>申报订单状态</th>
                                <th>恶意投递订单数量</th>
                                <th>误操作订单数量</th>
                                <th>申报时间</th>
<!--                                <th>回收机编号</th>-->
                                <th>小区名称</th>
                                <th>箱体名称</th>
                                <th>申报回收员</th>
                                <th>申报原因</th>
                                <th>照片数量</th>
                                <th>详细说明</th>
                                <th>预估重量（kg）</th>
                                <th>距上次开箱订单数</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>操作</th>
                                <th>审核超时时间</th>
                                <th>申报编号</th>
                                <th>申报订单状态</th>
                                <th>恶意投递订单数量</th>
                                <th>误操作订单数量</th>
                                <th>申报时间</th>
<!--                                <th>回收机编号</th>-->
                                <th>小区名称</th>
                                <th>箱体名称</th>
                                <th>申报回收员</th>
                                <th>申报原因</th>
                                <th>照片数量</th>
                                <th>详细说明</th>
                                <th>预估重量（kg）</th>
                                <th>距上次开箱订单数</th>

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
    function order_detail(id) {
        // location.href =
        window.open('/special/can_detail?id=' + id)
        // show_detail("上门订单详情", "/book/detail?order_id=" + order_id)
    }

    function show_image(id) {
        show_detail("图片查看", "/special/show_image?id=" + id)
    }

    function get_table(){
        var name = $('#name').val();
        var user_name = $('#user_name').val();
        var recycler_id = $('#recycler_id').val();
        var order_status = $('#order_status').val();
        var timeout_status = $('#timeout_status').val();
        var order_by = $('#order_by').val();

        var params = {
            'name': name,
            'user_name': user_name,
            'recycler_id': recycler_id,
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