<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">用户投递列表</h2>
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
                <h5><b>用户投递列表</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="投递日期" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" id="phone" placeholder="请输入手机号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['phone']) ? $_GET['phone'] : ''?>">
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="machine_id"  class="form-control" lay-verify="required" lay-search="" >
                            <option value="">请选择机器</option>
                            <?php foreach ($srRecyclingMachine as $v) { ?>
                                <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['machine_id']) ? '' : $_GET['machine_id'])) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <input type="text" id="device_id" placeholder="请输入设备号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['device_id']) ? $_GET['device_id'] : ''?>">
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3  form-group  col-sm-12 ">
                        <button type="button" id="add"  class="btn btn-primary" onclick="rank()">用户投递排名查询</button>
                        <button type="button" id="add"  class="btn btn-primary" onclick="add()">添加投递记录</button>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>投递id</th>
                            <th>用户id</th>
                            <th>用户昵称</th>
                            <th>用户手机</th>
                            <th>终端设备</th>
                            <th>投递时间</th>
                            <th>操作</th>
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
        var url = '/data/ajax-delivery';
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
        var device_id = $('#device_id').val();
        var phone = $('#phone').val();
        var params = {};
        if(date_time != ''){
            params.delivery_time = date_time;
        }
        if(machine_id != ''){
            params.machine_id = machine_id;
        }
        if(device_id != ''){
            params.device_id = device_id;
        }

        if(phone != ''){
            params.phone = phone;
        }

        var url = '/data/ajax-delivery';
        window.dataTable(params, url);
    });


    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function delivery_detail(id) {
        Dialog({
            title: '投递明细',
            width: 800,
            iframeContent: {
                src: '/data/delivery-detail?id='+id,
                height: 600
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function add(){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/data/delivery-add',
                height: 600
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function check_log(machine_id) {
        Dialog({
            title: '选择要查看日志的时间',
            width: 730,
            iframeContent: {
                src: '/data/logs?machine_id=' + machine_id,
                height: 100
            },
            bodyScroll:false,
            showButton:false
        });
        // show_detail("选择要查看日志的时间", "/machine/logs?machine_id=" + machine_id)
    }

    //查看排名
    function rank()
    {
        var phone_num = $('#phone').val();
        if(phone_num == ''){
            Dialog.error("错误", '请输入手机号');
            return;
        }
        Dialog({
            title: '用户投递排名查询',
            width: 900,
            iframeContent: {
                src: '/data/user-A?phone_num='+phone_num,
                height: 600
            },
            bodyScroll:false,
            showButton:false
        });
    }



</script>