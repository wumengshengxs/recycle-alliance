<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">注册投递人次列表</h2>
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
                <h5><b>注册投递人次列表 (默认不展示, 请筛选日期后点击查找)</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="投递日期" id="delivery_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="用户注册日期" id="user_time" readonly >
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
                            <th>街道名称</th>
                            <th>小区名称</th>
                            <th>投递人次（去重）</th>
                            <th>总投递数（开箱次数）</th>
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
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            //日期范围,
            laydate.render({
                elem: '#delivery_time'
                ,range: true
            });
            laydate.render({
                elem: '#user_time'
                ,range: true
            });
        });
        var url = '/data/ajax-people';
        var params = {};
        window.dataTable(params, url);
    });

    $('#search').click(function(){
        var delivery_time = $('#delivery_time').val();
        var user_time = $('#user_time').val();
        var machine_id = $('#machine_id').val();
        var params = {};
        if(delivery_time == ''){
            Dialog.error("错误", '请选择投递日期');
            return;
        }
        if(delivery_time != ''){
            params.delivery_time = delivery_time;
        }
        if(user_time != ''){
            params.user_time = user_time;
        }
        if(machine_id != ''){
            params.machine_id = machine_id;
        }
        var url = '/data/ajax-people';
        window.dataTable(params, url);
    });

</script>