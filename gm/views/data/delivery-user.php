<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">用户投递明细列表</h2>
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
                <h5><b>用户投递明细列表 (默认不展示, 请筛选日期后点击查找)</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="投递日期" id="delivery_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group  layui-form">
                        <select id="machine_id"  class="form-control" lay-verify="required" lay-search="" >
                            <option value="">请选择机器</option>
                            <?php foreach ($machine as $v) { ?>
                                <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['machine_id']) ? '' : $_GET['machine_id'])) { ?> selected<?php } ?>><?= $v['community_name']?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 col-lg-3 input-group mb-3 form-group  layui-form">
                        <select id="category_id"  class="form-control" lay-verify="required" lay-search="" >
                            <option value="">请选择品类</option>
                            <?php foreach ($category as $val) { ?>
                                <option value="<?= $val['id']?>" <?php if ($val['id'] == (empty($_GET['category_id']) ? '' : $_GET['category_id'])) { ?> selected<?php } ?>><?= $val['category_name']?></option>
                            <?php } ?>
                        </select>
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 form-group  col-sm-12 ">
                        <button type="button" class="btn btn-primary" onclick="get_num()">投递总人数</button>
                        <button type="button" class="btn btn-primary" onclick="get_money()">投递总环保金</button>
                        <button type="button" class="btn btn-primary" onclick="get_excel()">导出报表</button>
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
                            <th>用户手机</th>
                            <th>小区名称</th>
                            <th>品类</th>
                            <th>数量</th>
                            <th>环保金</th>
                            <th>投递时间</th>
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
        });
        var url = '/data/ajax-delivery-user';
        var params = {};
        window.dataTable(params, url);
    });

    $('#search').click(function(){
        var delivery_time = $('#delivery_time').val();
        var machine_id = $('#machine_id').val();
        var category_id = $('#category_id').val();
        var params = {};
        if(delivery_time == ''){
            Dialog.error("错误", '请选择投递日期');
            return;
        }
        if(machine_id == ''){
            Dialog.error("错误", '请选择机器地址');
            return;
        }
        if(delivery_time != ''){
            params.delivery_time = delivery_time;
        }
        if(category_id != ''){
            params.category_id = category_id;
        }
        if(machine_id != ''){
            params.machine_id = machine_id;
        }
        var url = '/data/ajax-delivery-user';
        window.dataTable(params, url);
    });

    function get_num(){
        var delivery_time = $('#delivery_time').val();
        var machine_id = $('#machine_id').val();
        if (!machine_id || !delivery_time) {
            Dialog.error("错误", '请选择日期和小区,再选择此项!');
            return;
        }
        var machine = $("#machine_id").find("option:selected").html();

        var data = {
            machine_id: machine_id,
            delivery_time: delivery_time,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        };

        $.ajax({
            url: '/data/delivery-user-num',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                console.log(res)
                Dialog.success("提示",delivery_time+'时间段在 '+machine+' 小区投递人数总计为 ' + (res.data) + '人');
            }
        })
    }

    function get_money(){
        var delivery_time = $('#delivery_time').val();
        var machine_id = $('#machine_id').val();
        if (!machine_id || !delivery_time) {
            Dialog.error("错误", '请选择日期和小区,再选择此项!');
            return;
        }
        var machine = $("#machine_id").find("option:selected").html();

        var data = {
            machine_id: machine_id,
            delivery_time: delivery_time,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        };
        $.ajax({
            url: '/data/delivery-user-money',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.status == 100) {
                    Dialog.error("错误", delivery_time+'时间段在 '+machine+'暂无环保金记录产生');
                    return
                }
                Dialog.success("提示",delivery_time+'时间段在 '+machine+' 小区投递环保金总计为 ' + (res.data) + '元');
            }
        })
    }

    function get_excel(){
        var delivery_time = $('#delivery_time').val();
        var machine_id = $('#machine_id').val();
        var delivery_type = $('#category_id').val();
        if (!machine_id || !delivery_time) {
            Dialog.error("错误", '请选择日期和小区,再选择此项!');
            return;
        }
        params = delivery_time && '&delivery_time=' + delivery_time;
        params += machine_id && '&machine_id=' + machine_id;
        params += delivery_type && '&delivery_type=' + delivery_type;


        window.location.href = '/data/delivery-user-excel/?' + params;
    }

</script>