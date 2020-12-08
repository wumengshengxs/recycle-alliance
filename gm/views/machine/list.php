<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">设备管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">设备管理</li>
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
                <h5><b>设备管理</b></h5>
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
                    <?php if(!$agent['admin']){?>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="active" class="form-control">
                            <option value="">激活状态 </option>
                            <option value="0">未激活</option>
                            <option value="1">已激活</option>
                        </select>
                    </div>
                    <?php }?>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="status" class="form-control">
                            <option value="">设备状态</option>
                            <option value="0">正常</option>
                            <option value="1">维修中</option>
                            <option value="2">停止运营</option>
                        </select>
                    </div>
                    <?php if($agent['admin']){?>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="village_id" class="form-control" style="height: 42px">
                            <option value="">选择小区</option>
                            <?php foreach ($village as $v) { ?>
                                <option value="<?= $v['p_id']?>" <?php if ($v['p_id'] == (empty($_GET['village_id']) ? '' : $_GET['village_id'])) { ?> selected<?php } ?>><?= $v['village_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 form-group">
                        <select id="recycler_id" class="form-control" style="height: 42px">
                            <option value="">选择清运人员</option>
                            <?php foreach ($recycler as $v) { ?>
                                <option value="<?= $v['id']?>" <?php if ($v['id'] == (empty($_GET['recycler_id']) ? '' : $_GET['recycler_id'])) { ?> selected<?php } ?>><?= $v['nick_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php }?>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <input type="text" id="company_name" placeholder="请输入点位名称" class="form-control">
                    </div>
                    <div class="col-12 col-lg-3 input-group mb-3 form-group">
                        <input type="text" id="company_agent" placeholder="搜索联营方名称或联系电话…" class="form-control">
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
                            <th>设备ID</th>
                            <th>激活状态</th>
                            <th>所在省市</th>
                            <th>点位名称</th>
                            <th>点位地址</th>
                            <th>当前状态</th>
                            <?php if(!$agent['admin']){?>
                                <th>联营方名称</th>
                                <th>联系方式</th>
                            <?php } else {?>
                                <th>清运人员</th>
                                <th>维修人员</th>
                            <?php }?>

                            <th>当前设备版本号</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <button class="btn btn-primary btn-all">全选</button>
                    <button class="btn btn-primary btn-batch">批量修改价格</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/region.js"></script>
<script>
    $(function(){
        var url = '/machine/ajax_list';
        var params = {};
        window.dataTable(params, url);
    });
    $('#search').click(function(){
        var province = $('#province').find('option:selected').text();
        var city = $('#city').find('option:selected').text();
        var district = $('#district').find('option:selected').text();
        var active = $('#active').val();
        var status = $('#status').val();
        var company_name = $('#company_name').val();
        var company_agent = $('#company_agent').val();
        var village_id = $('#village_id').val();
        var recycler_id = $('#recycler_id').val();
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
        if(active != ''){
            params.active = active;
        }
        if(status != ''){
            params.status = status;
        }
        if(company_name != ''){
            params.company_name = company_name;
        }
        if(company_agent != ''){
            params.company_agent = company_agent;
        }
        if(village_id != ''){
            params.village_id = village_id;
        }
        if(recycler_id != ''){
            params.recycler_id = recycler_id;
        }
        var url = '/machine/ajax_list';
        window.dataTable(params, url);
    });

    var btn_all = false;
    $('.btn-all').click(function(){
        btn_all == true ? btn_all = false : btn_all = true;
        $('.custom-control-input').each(function(){
            $(this).prop('checked', btn_all);
        });
    });
    $('.btn-batch').click(function(){
        var id = [];
        $('.custom-control-input').each(function(){
            if($(this).prop('checked')){
                id.push($(this).val());
            }
        });
        if(id.length > 0){
            option(2, id);
        }
    })


    function option(obj, id){
        var opt;
        !isNaN(obj) ? opt = obj : opt = $(obj).val();
        var params = {};
        if(opt == 1){
            params.title = '修改设备信息';
            params.width = 900;
            params.url = '/machine/info/?id=' + id;
            params.height = 855;
        }
        if(opt == 2){
            params.title = '回收机价格设置';
            params.width = 900;
            params.url = '/machine/price/?id=' + id;
            params.height = 352;
        }
        if(opt == 5){
            params.title = '设备实时状态';
            params.width = 900;
            params.url = '/machine/status/?id=' + id;
            params.height = 900;
        }
        $(obj).find('option').eq(0).prop('selected',true);
        if(opt > 0) {
            Dialog({
                title: params.title,
                width: params.width,
                iframeContent: {
                    src: params.url,
                    height: params.height
                },
                bodyScroll: true,
                showButton: false
            });
        }
    }

    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

</script>