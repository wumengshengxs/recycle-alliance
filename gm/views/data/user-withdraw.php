<link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
<script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
<style>
    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: 0;
        margin-left: 0;
    }
    .flag-x{
        padding: 0 30px 0 30px;
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body" style="padding: 10px 20px 10px 20px;">
                <div class="row flag-x" >
                    <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0;">&nbsp;&nbsp;&nbsp; 累计收益: <?= $static['cumulative_income']?></div>
                    <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0;">当前可用环保金: <?= $static['current_env_amount']?></div>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:770px;min-width:756px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>提现金额</th>
                            <th>提现状态</th>
                            <th>申请提现时间</th>
                            <th>提现到账时间</th>
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
<!-- dataTables -->
<script src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
<script src="/concept/vendor/datatables/js/dataTables.js"></script>


<script>
    $(function(){
        var id = '<?= !empty($id) ? $id : '' ?>'

        var url = '/data/ajax-user-withdraw?id='+id;
        var params = {};
        window.dataTable(params, url);
    });




</script>