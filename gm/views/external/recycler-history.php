<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">充值管理</h2>

            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">充值管理</li>
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
                <h5><b>充值管理</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group" >
                        <input type="text" id="nick_name" placeholder="回收商姓名" style="height: 42px" class="form-control" value="<?=  !empty($_GET['nick_name']) ? $_GET['nick_name'] : ''?>">
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group" >
                        <input type="text" id="phone_num" placeholder="回收商手机号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['phone_num']) ? $_GET['phone_num'] : ''?>">
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <select id="end_status" class="form-control " style="height: 42px">
                            <option value="">超时状态</option>
                            <option value="1">已超时</option>
                            <option value="2">未超时</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <select id="status" class="form-control " style="height: 42px">
                            <option value="">审核状态</option>
                            <option value="1">待审核</option>
                            <option value="2">审核通过</option>
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
                            <th>回收商编号</th>
                            <th>回收商姓名</th>
                            <th>手机</th>
                            <th>汇款金额</th>
                            <th>汇款银行卡号</th>
                            <th>汇款截图</th>
                            <th>超时状态</th>
                            <th>超时详情</th>
                            <th>审核状态</th>
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
        var url = '/external/ajax-recycler-history';
        var params = {};
        window.dataTable(params, url);
    });

    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    $('#search').click(function(){

        var nick_name = $('#nick_name').val();
        var phone_num = $('#phone_num').val();
        var end_status = $('#end_status').val();
        var status = $('#status').val();
        var params = {};
        if(nick_name != ''){
            params.nick_name = nick_name;
        }
        if(phone_num != ''){
            params.phone_num = phone_num;
        }
        params.end_status = end_status;
        params.status = status;
        var url = '/external/ajax-recycler-history';
        window.dataTable(params, url);
    });

    function look(id)
    {
        window.location.href = '/external/recycler-history-imgs?id='+id;
    }

    function edit(id){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/external/recycler-history-edit?id='+id,
                height: 720
            },
            bodyScroll:false,
            showButton:false
        });
    }





</script>