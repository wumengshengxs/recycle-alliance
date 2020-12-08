<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">账号管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">账号管理</li>
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
                <h5><b>账号管理</b></h5>
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
                        <select id="type" class="form-control">
                            <option value="">选择联营类型</option>
                            <option value="0">自营</option>
                            <option value="1">联营</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group">
                        <input type="text" id="company_name" placeholder="请输入公司名称" class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 form-group">
                        <a href="/manager/add" class="btn btn-primary">添加账号</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="min-width:1554px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>联营方编号</th>
                            <th>创建时间</th>
                            <th>账号类型</th>
                            <th>公司名称</th>
                            <th>企业地址</th>
                            <th>法人姓名</th>
                            <th>法人身份证</th>
                            <th>联系方式</th>
                            <th>密码</th>
                            <th>设备数量</th>
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
        var url = '/manager/ajax_list';
        var params = {};
        window.dataTable(params, url);
    })
    $('#search').click(function(){
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var type = $('#type').val();
        var company_name = $('#company_name').val();
        var params = {};
        if(province != ''){
            params.province = province;
        }
        if(city != ''){
            params.city = city;
        }
        if(district != ''){
            params.district = district;
        }
        if(type != ''){
            params.type = type;
        }
        if(company_name != ''){
            params.company_name = company_name;
        }
        var url = '/manager/ajax_list';
        window.dataTable(params, url);
    })
</script>