<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">回收员<?=$recyclers['nick_name']?></h2>

            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item " aria-current="page"><a href="/external/recycler-staff-index" class="breadcrumb-link">回收员管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">充值记录</li>
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
                <h5><b>充值记录</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <select id="status" class="form-control " style="height: 42px">
                            <option value="">类型</option>
                            <option value="1">收入</option>
                            <option value="2">支出</option>
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
                            <th>类型</th>
                            <th>时间</th>
                            <th>金额</th>
                            <th>账户金额</th>
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
    var id = '<?= !empty($_GET['id']) ? $_GET['id'] : '' ?>'
    $(function(){

        var url = '/external/ajax-recycler-staff-history?id='+id;
        var params = {};
        window.dataTable(params, url);
    });


    $('#search').click(function(){

        var status = $('#status').val();
        var params = {};
        params.id = '<?= !empty($_GET['id']) ? $_GET['id'] : '' ?>';
        if(status != ''){
            params.status = status;
        }

        var url = '/external/ajax-recycler-staff-history';
        window.dataTable(params, url);
    });


</script>