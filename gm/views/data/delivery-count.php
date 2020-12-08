<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">投递数据统计列表</h2>
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
                <h5><b>投递数据统计列表 (默认不展示, 请筛选后点击查找)</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="选择日期" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="street_name" class="form-control " style="height: 42px"   lay-filter="street_name" >
                            <option value="">请选择街道/镇</option>
                            <?php foreach ($street as $v) { ?>
                                <option value="<?= $v['street_name']?>"><?= $v['street_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group layui-form">
                        <select id="community_name" class="form-control " style="height: 42px" lay-filter="community_name"  lay-verify="required" lay-search="">
                            <option value="">请选择小区</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <div class="input-group-append">
                            <button type="button" id="search" onclick="get_table()" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>时间</th>
                            <th>区/县</th>
                            <th>街镇</th>
                            <th>小区</th>
                            <th>饮料瓶(重量)</th>
                            <th>纸类(公斤)</th>
                            <th>书籍</th>
                            <th>纺织</th>
                            <th>塑料</th>
                            <th>金属</th>
                            <th>玻璃</th>
                            <th>总重</th>
                            <th>饮料瓶(个)</th>
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
        var url = '/data/ajax-delivery-count';
        var params = {};
        window.dataTable(params, url);
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            var form = layui.form;
            //日期范围
            laydate.render({
                elem: '#date_time'
                ,range: true
            });

            form.on('select(street_name)', function(data){
                community();
            });
        });
    })

    function get_table()
    {
        var params = {
            date_time: $('#date_time').val(),
            street_name: $('#street_name').val(),
            community_name: $('#community_name').val()
        };
        //校验数据
        if (params.date_time == '') {
            Dialog.error("错误", '请选择时间');
            return;
        }
        if (params.street_name == '') {
            Dialog.error("错误", '请选择街道/镇');
            return;
        }
        var url = '/data/ajax-delivery-count';
        window.dataTable(params, url);
    }

    function community()
    {
        var street = $("#street_name").find("option:selected").val();
        $.ajax({
            url: '/data/get-delivery-community',
            type: 'post',
            data: {'street':street,'_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'},
            dataType: 'json',
            success: function(res) {
                if (res.status == 200){
                    var html = '<option value="">请选择小区</option>';
                    for (var i in res.data) {
                        html += '<option value="' + res.data[i].community_name + '">' + res.data[i].community_name + '</option>';
                    }

                    $('#community_name').html(html);
                    //在渲染一遍
                    layui.use(['form','laydate'], function(){
                        var form = layui.form;
                        form.render('select');
                    });
                }
            },

        });
    }


</script>
