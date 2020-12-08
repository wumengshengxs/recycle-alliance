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
                            <div class="title">机器版本管理</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <button type="button" onclick="apk_add();" class="btn btn-primary">新增版本</button>
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                        <table class="datatable-machine-machine_apk_list table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>操作</th>
                                <th>版本名称</th>
                                <th>版本号</th>
                                <th>下载地址</th>
                                <th>发布时间</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>操作</th>
                                <th>版本名称</th>
                                <th>版本号</th>
                                <th>下载地址</th>
                                <th>发布时间</th>
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
    function apk_add() {
        show_detail("新增版本", "/machine/apk_add")
    }

    function apk_to_machine($id) {
        show_detail("筛选更新机器", "/machine/apk_to_machine?id=" + $id)
    }
</script>