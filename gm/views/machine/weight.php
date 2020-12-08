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
                            <div class="title">机器重量列表</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                            <table class="datatable-machine-weight table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>操作</th>
                                    <th>设备编号</th>
                                    <th>设备地址</th>
                                    <th>重量</th>
                                    <th>饮料瓶</th>
                                    <th>合计</th>
                                    <th>使用开始时间</th>
                                    <th>使用结束时间</th>
                                    <th>负责人</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>操作</th>
                                    <th>设备编号</th>
                                    <th>设备地址</th>
                                    <th>重量</th>
                                    <th>饮料瓶</th>
                                    <th>合计</th>
                                    <th>使用开始时间</th>
                                    <th>使用结束时间</th>
                                    <th>负责人</th>
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

    function check_log(device_id) {
        show_detail("选择要查看日志的时间", "/machine/logs?device_id=" + device_id)
        // window.open('/machine/log?device_id=' + device_id)
    }
</script>