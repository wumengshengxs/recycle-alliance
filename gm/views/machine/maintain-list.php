

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">设备维修管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">设备维修管理</li>
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
                <h5><b>设备维修管理</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="machine_id" class="form-control" lay-verify="required" lay-search="">
                            <option value="">请选择机器</option>
                            <?php foreach ($machine as $v) { ?>
                                <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['machine_id']) ? '' : $_GET['machine_id'])) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="maintain_id" class="form-control"  lay-verify="required" lay-search="">
                            <option value="">请选择维修员</option>
                            <?php foreach ($maintain as $val) { ?>
                                <option value="<?= $val['id']?>" <?php if ($val['id'] == (empty($_GET['maintain_id']) ? '' : $_GET['maintain_id'])) { ?> selected<?php } ?>><?= $val['nick_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <select id="type" class="form-control" style="height: 42px">
                            <option value="">故障类型</option>
                            <option value="1">日常检修</option>
                            <option value="2">机器故障</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                         <input type="text" class="layui-input layui-inline"  placeholder="添加日期" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <select id="status" class="form-control " style="height: 42px">
                            <option value="">维修状态</option>
                            <option value="1">进行中</option>
                            <option value="2">已完成</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2  form-group  col-sm-12 ">
                        <button type="button" id="add" class="btn btn-primary" onclick="add()">新增维修</button>
<!--                        <a  href="/machine/add-email" class="btn btn-primary">发送邮件</a>-->
                        <button type="button"  class="btn btn-primary" onclick="get_excel()">导出报表</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>机器ID</th>
                            <th>回收机所在小区</th>
                            <th>所属维修员</th>
                            <th>完成维修员</th>
                            <th>故障类型</th>
                            <th>故障</th>
                            <th>维修状态</th>
                            <th>创建时间</th>
                            <th>完成时间</th>
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
        var url = '/machine/ajax-maintain-list';
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
        var maintain_id = $('#maintain_id').val();
        var machine_id = $('#machine_id').val();
        var date_time = $('#date_time').val();
        var status = $('#status').val();
        var type = $('#type').val();
        var params = {};
        if(maintain_id != ''){
            params.maintain_id = maintain_id;
        }
        if(machine_id != ''){
            params.machine_id = machine_id;
        }
        if(status != ''){
            params.status = status;
        }
        if(type != ''){
            params.type = type;
        }
        if(date_time != ''){
            params.date_time = date_time;
        }
        var url = '/machine/ajax-maintain-list';
        window.dataTable(params, url);
    });

    function get_excel(){
        var maintain_id = $('#maintain_id').val();
        var machine_id = $('#machine_id').val();
        var status = $('#status').val();
        var type = $('#type').val();
        var date_time = $('#date_time').val();

        var params = 'maintain_id='+maintain_id+'&machine_id='+machine_id+'&status='
            +status+'&type='+type+'&date_time='+date_time;
        window.location.href = "/machine/get-maintain-excel?"+params;
    }


    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function add(){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/machine/maintain-add',
                height: 650
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function edit(id){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/machine/maintain-edit?id='+id,
                height: 850
            },
            bodyScroll:false,
            showButton:false
        });
    }



</script>