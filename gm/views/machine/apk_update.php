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
                            <div class="title">版本更新查看</div>
                        </div>
                    </div>
                    <div class="card-body">
<!--                        <button type="button" onclick="apk_add();" class="btn btn-primary">新增版本</button>-->
                        <div class="panel-body" style="width: 100%;overflow-x: scroll;">
                            <table class="datatable-machine-apk_to_update table table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>设备号</th>
                                    <th>版本描述</th>
                                    <th>版本号</th>
                                    <th>更新时间</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>设备号</th>
                                    <th>版本描述</th>
                                    <th>版本号</th>
                                    <th>更新时间</th>
                                </tr>
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
    /*
        datatable自动接受加载数据
    */
    window.dataTable_param = {
        'machine_id':<?=$machine_id?>
    }

</script>