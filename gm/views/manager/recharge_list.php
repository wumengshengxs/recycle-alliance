<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">环保金审核</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">环保金审核</li>
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
                <h5><b>环保金审核</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-4 text-right">
                        <select id="status" class="form-control">
                            <option value="">全部</option>
                            <option value="0">待审核</option>
                            <option value="1">待复核</option>
                            <option value="3">已审核</option>
                            <option value="2">已驳回</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4 input-group mb-3 form-group">
                        <input type="text" id="company_name" placeholder="联营方名称或联系电话" class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="search" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="min-width:1554px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>提交时间</th>
                            <th>联营方编号</th>
                            <th>联营方名称</th>
                            <th>联系方式</th>
                            <th>汇款银行</th>
                            <th>汇款金额</th>
                            <th>当前金额</th>
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
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    $(function(){
        var url = '/manager/ajax_recharge_list';
        var params = {};
        window.dataTable(params, url);
    })

    $('#search').click(function(){
        var url = '/manager/ajax_recharge_list';
        var params = {
            status:$('#status').val(),
            company_name:$('#company_name').val()
        };
        window.dataTable(params, url);
    });

    function verify(id){
        Dialog({
            showTitle: false,
            width: 900,
            iframeContent: {
                src: '/manager/verify/?id=' + id,
                height: 745
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function chk(id){
        Dialog({
            showTitle: false,
            width: 900,
            iframeContent: {
                src: '/manager/check/?id=' + id,
                height: 745
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function evidence(id) {
        Dialog({
            showTitle: false,
            width: 900,
            iframeContent: {
                src: '/manager/evidence/?id=' + id,
                height: 745
            },
            bodyScroll:false,
            showButton:false
        });
    }
    
    function close_iframe(){
        Dialog.close();
    }
</script>