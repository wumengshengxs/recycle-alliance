<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">回收员管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">回收员管理</li>
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
                <h5><b>回收员管理</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" id="nick_name" placeholder="回收员姓名" style="height: 42px" class="form-control" value="<?=  !empty($_GET['nick_name']) ? $_GET['nick_name'] : ''?>">
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <input type="text" id="phone" placeholder="请输入手机号" class="form-control" value="<?=  !empty($_GET['phone']) ? $_GET['phone'] : ''?>">
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
<!--                    <div class="col-12 col-lg-2  form-group  col-sm-12 ">-->
<!--                        <button type="button" id="add" class="btn btn-primary" onclick="add()">添加账号</button>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>回收员编号</th>
                            <th>回收员类型</th>
                            <th>姓名</th>
                            <th>小区</th>
                            <th>手机</th>
                            <th>累计收益金额</th>
                            <th>当前余额</th>
                            <th>状态</th>
                            <th>创建时间</th>
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
        var url = '/external/ajax-recycler-staff-index';
        var params = {};
        window.dataTable(params, url);
    });


    $('#search').click(function(){
        var nick_name = $('#nick_name').val();
        var phone = $('#phone').val();
        var params = {};
        if(nick_name != ''){
            params.nick_name = nick_name;
        }
        if(phone != ''){
            params.phone_num = phone;
        }

        var url = '/external/ajax-recycler-staff-index';
        window.dataTable(params, url);
    });



    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function edit(id){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/external/recycler-staff-edit?id='+id,
                height: 780
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function RecyclerList(id){
        window.location.href = '/external/recycler-staff-history?id='+id
    }



</script>