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
                            <div class="title">用户投递明细</div>
                        </div>
                    </div>
                    <div class="card-body">
<!--                        <button type="button" onclick="get_excel();" class="btn btn-primary">导出报表</button>-->
                        <div class="panel-body form-group">
                            <div class="col-xs-2">开始日期：<input type="text" class="form-control" style="background:#fff;"
                                                              readonly="readonly" id="datepicker"/></div>
                            <div class="col-xs-2">结束日期：<input type="text" class="form-control" style="background:#fff;"
                                                              readonly="readonly" id="datepicker_end"/></div>
                            <div class="col-xs-2">设备查找：
                                <select class="form-control" id="machine_id" onchange="get_table()">
                                    <option value="">全部设备</option>
                                    <?php foreach ($srRecyclingMachine as $v) { ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['community_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-2">品类选择：
                                <select class="form-control" id="delivery_type" onchange="get_table()">
                                    <option value="">全部品类</option>
                                    <?php foreach ($srRubbishCategory as $v) { ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['category_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-2">
                                <button class="btn btn-lg btn-info" onclick="get_num()">投递总人数</button>
                            </div>
                            <div class="col-xs-2">
                                <button class="btn btn-lg btn-info" onclick="get_money()">投递总环保金</button>
                            </div>
                        </div>
                        <div class="panel-body"></div>
                        <!--datatable-data-delivery为class+控制器名称-->
                        <table class="datatable-statistic-delivery table table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>投递id</th>
                                <th>用户id</th>
                                <th>用户手机</th>
                                <th>品类</th>
                                <th>数量</th>
                                <th>环保金</th>
                                <th>投递时间</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>投递id</th>
                                <th>用户id</th>
                                <th>用户手机</th>
                                <th>品类</th>
                                <th>数量</th>
                                <th>环保金</th>
                                <th>投递时间</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function get_table() {
        var delivery_time = $('#datepicker').val();
        var datepicker_end = $('#datepicker_end').val()
        var machine_id = $('#machine_id').val();
        var delivery_type = $('#delivery_type').val();

        if (!delivery_time) {
            alert('请选择开始日期');
            return;
        }
        if (!datepicker_end) {
            alert('请选择结束日期');
            return;
        }
        if (!machine_id) {
            alert('请选择或者输入你要查看的小区');
            return;
        }

        var params = {
            'delivery_time': delivery_time,
            'datepicker_end': datepicker_end,
            'machine_id': machine_id,
            'delivery_type': delivery_type
        };
        window.dataTable(params);
    }

    function get_num() {
        let delivery_time = $('#datepicker').val();
        let datepicker_end = $('#datepicker_end').val();
        var machine_id = $('#machine_id').val();
        if (!machine_id || !delivery_time) {
            alert('请选择日期和小区,再选择此项!');
            return
        }
        var data = {
            machine_id: machine_id,
            delivery_time: delivery_time,
            datepicker_end: datepicker_end,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        };
        $.ajax({
            url: '/statistic/get_num',
            type: 'get',
            data: data,
            dataType: 'json',
            success: function (res) {
                // alert('投递总人数为'+JSON.stringify(res));
                alert('该日投递人数总计为' + (res) + '人次');
            }
        })
    }

    function get_money() {
        let delivery_time = $('#datepicker').val();
        let datepicker_end = $('#datepicker_end').val();
        var machine_id = $('#machine_id').val();
        if (!machine_id || !delivery_time) {
            alert('请选择日期和小区,再选择此项!');
            return
        }
        var data = {
            machine_id: machine_id,
            delivery_time: delivery_time,
            datepicker_end: datepicker_end,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        };
        $.ajax({
            url: '/statistic/get_money',
            type: 'get',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res == "") {
                    alert('该日暂无环保金');
                    return
                }
                alert('该日环保金总计为' + (res) + '元');
                // alert(JSON.stringify(res));
            }
        })
    }

    // function get_excel() {
    //     var delivery_time = $('#datepicker').val();
    //     let datepicker_end = $('#datepicker_end').val();
    //     var phone_num = $('#phone_num').val();
    //     var machine_id = $('#machine_id').val();
    //     var params = 's=1';
    //
    //     params += delivery_time && '&delivery_time=' + delivery_time;
    //     params += datepicker_end && '&datepicker_end=' + datepicker_end;
    //     params += phone_num && '&phone_num=' + phone_num;
    //     params += machine_id && '&machine_id=' + machine_id;
    //
    //     var url = '/dataexcel/delivery/?' + params;
    //
    //     window.location.href = url;
    // }
</script>