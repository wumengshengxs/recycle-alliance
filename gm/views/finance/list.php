<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">账务管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">账务管理</li>
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
                <h5><b>账务管理</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-4 input-group mb-3 form-group">
                        <input type="text" id="company_name" placeholder="请输入公司名称" class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="search" onclick="search()" class="btn btn-primary">查找</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="min-width:1554px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>联营方编号</th>
                            <th>公司名称</th>
                            <th>环保金余额</th>
                            <th>累计发放环保金</th>
                            <th>最新充值时间</th>
                            <th>最新充值金额</th>
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

<script>
    $(function(){
        var url = '/finance/ajax_list';
        var params = {};
        window.dataTable(params, url);
    })

    function search(){
        var company_name = $('#company_name').val();
        var params = {};
        if(company_name != ''){
            params.company_name = company_name;
        }
        var url = '/finance/ajax_list';
        window.dataTable(params, url);
    }
</script>