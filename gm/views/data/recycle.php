<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">设备清运列表</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">数据统计管理</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5><b>设备清运列表</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="清运日期" id="date_time" readonly >
                    </div>

                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="recycler_id"  class="form-control" lay-verify="required" lay-search="" >
                            <option value="">请选择回收员</option>
                            <?php foreach ($recycler as $val) { ?>
                                <option value="<?= $val['id']?>" <?php if ($val['id'] == (empty($_GET['recycler_id']) ? '' : $_GET['recycler_id'])) { ?> selected<?php } ?>><?= $val['nick_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-3 input-group mb-3 form-group  layui-form">
                        <select id="machine_id"  class="form-control" lay-verify="required" lay-search="" >
                            <option value="">请选择机器</option>
                            <?php foreach ($machine as $v) { ?>
                                <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['machine_id']) ? '' : $_GET['machine_id'])) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                            <?php } ?>
                        </select>
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>


                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>清运品类</th>
                            <th>清运重量</th>
                            <th>清运费</th>
                            <th>设备地址</th>
                            <th>回收员</th>
                            <th>电话</th>
                            <th>清运时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/region.js"></script>

<script>

    $(function(){
        var url = '/data/ajax-recycle';
        var params = {};
        window.dataTable(params, url);
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            //日期范围
            laydate.render({
                elem: '#date_time'
                ,range: true
            });
        });
    });

    $('#search').click(function(){
        var date_time = $('#date_time').val();
        var machine_id = $('#machine_id').val();
        var recycler_id = $('#recycler_id').val();
        var params = {};
        if(date_time != ''){
            params.date_time = date_time;
        }
        if(machine_id != ''){
            params.machine_id = machine_id;
        }
        if(recycler_id != ''){
            params.recycler_id = recycler_id;
        }
        var url = '/data/ajax-recycle';
        window.dataTable(params, url);
    });

</script>