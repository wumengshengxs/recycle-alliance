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
                            <div class="col-sm-12 title">广告位列表</div>
                        </div>
                        <button class="btn  btn-info col-sm-offset-8 " onclick="add_ad_space()">新增广告</button>

                    </div>
                    <div class="card-body">
                        <div class="panel-body form-group">
                            <div class="col-xs-2">广告标题 ：<input type="text" class="form-control" style="background:#fff;"
                                                            readonly="readonly" id="ad_headline"/></div>
                            <div class="col-xs-2">设备小区名称 ：<input type="text" id="community_name" class="form-control"
                                                              onchange="get_table()"/></div>
                            <div class="col-xs-2">显示状态 ：
                                <select class="form-control" name="area_type" id="show_type"
                                        onchange="get_table();">
                                    <option value="">全部</option>
                                    <option value="1">隐藏</option>
                                    <option value="2">显示</option>
                                </select>
                            </div>
                            <button type="button" onclick="village_add();" class="btn btn-primary">点我查询</button>
                            <div class="col-xs-offset-8"></div>
                        </div>
                        <!--                        <div class="panel-body"></div>-->
                        <!--datatable-data-delivery为class+控制器名称-->
                        <table class="datatable-ad-ad_list table table-striped" cellspacing="0" width="100%">

                            <thead>
                            <tr>
                                <th>广告编号</th>
                                <th>配图</th>
                                <th>广告类型</th>
                                <th>广告覆盖范围</th>
                                <th>覆盖机器数量（实时）</th>
                                <th>标题</th>
                                <th>跳转类型</th>
                                <th>排序</th>
                                <th>显示状态</th>
                                <th>自动下线时间</th>
                                <th>修改时间</th>
                                <th>修改账号</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    //新增广告
    function add_ad() {
        window.open('/ad/add_ad');
    }
    //更具条件查询
    function village_edit(id) {
        show_detail("编辑小区", "/village/village_edit?id=" + id)
    }

    function get_table() {
        var ad_headline = $('#ad_headline').val();
        var community_name = $('#community_name').val();
        var show_type = $('#show_type').val();

        var params = {
            'ad_headline': ad_headline,
            'community_name': community_name,
            'show_type': show_type,
        };
        window.dataTable(params);
    }
</script>