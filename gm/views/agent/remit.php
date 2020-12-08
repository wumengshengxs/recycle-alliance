<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">汇款通知</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">汇款通知</li>
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
                <h5><b>汇款通知记录</b></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="min-width:500px" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>填写时间</th>
                            <th>汇款银行</th>
                            <th>金额</th>
                            <th>审核状态</th>
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
window.onload = function() {
    var url = '/data/ajax_remit';
    var params = {};
    window.dataTable(params, url);
}
</script>