<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">小区管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">小区管理</li>
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
                <h5><b>小区管理</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 form-group">
                        <select id="province" class="form-control">
                            <option value="">选择省</option>
                            <?php foreach ($province as $v){?>
                                <option value="<?=$v['id']?>"><?=$v['fullname']?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="city" class="form-control">
                            <option value="">选择市</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="district" class="form-control">
                            <option value="">选择区县</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="street" class="form-control">
                            <option value="">选择街/镇</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <input type="text" id="village_name" placeholder="请输入小区名称~" class="form-control">
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
                            <th>小区id</th>
                            <th>小区名称</th>
                            <th>所属区域</th>
                            <th>街道</th>
                            <th>详细地址</th>
                            <th>设备数量</th>
                            <th>添加时间</th>
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
        var url = '/village/ajax_list';
        var params = {};
        window.dataTable(params, url);
    });
    $('#search').click(function(){
        var province = $('#province').find('option:selected').text();
        var city = $('#city').find('option:selected').text();
        var district = $('#district').find('option:selected').text();
        var street = $('#street').find('option:selected').text();
        var village_name = $('#village_name').val();
        var params = {};
        if(province != '' && province != '选择省'){
            params.province = province;
        }
        if(city != '' && city != '选择市'){
            params.city = city;
        }
        if(district != '' && district != '选择区县'){
            params.district = district;
        }
        if(street != '' && street != '选择街/镇'){
            params.street = street;
        }

        if(village_name != ''){
            params.village_name = village_name;
        }
        var url = '/village/ajax_list';
        window.dataTable(params, url);
    });


    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function village_edit(id) {
        Dialog({
            showTitle: false,
            width:1000,
            iframeContent: {
                src: '/village/village_edit/?id=' + id,
                height: 574
            },
            bodyScroll:false,
            showButton:false
        });
    }
    function village_del(id) {
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定删除</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/village/village_del',
                        type: 'post',
                        data: {id,'_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'},
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status==200) {
                                // Dialog.success("提示", '操作成功');
                                window.parent.ifr_close()
                                // setTimeout(function(){;},1500)
                            } else {
                                Dialog.error("错误", '请联系管理员');
                            }
                        },
                        beforeSend: function(){
                            Dialog.waiting("处理中，请等待...");
                        }
                    });
                }
            }
        });
    
    }
</script>