<div class="container-fluid">
    <div class="side-body">
        <div class="page-title">
            <span class="title"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">

                        <div class="card-title">
                            <div class="title">刷机工具</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="col-xs-2">刷机日期：<input type="text" class="form-control" style="background:#fff;" readonly="readonly" id="datepicker" value="<?=date('Y-m-d')?>" /></div>
                            <div class="col-xs-2">刷机倍率：<input type="text" id="valve" value="3" class="form-control" /></div>
                            <input id="csrf_gm" type="hidden" value="<?=Yii::$app->request->csrfToken?>" />
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-2"><button type="button" class="btn btn-lg btn-success" onclick="brush()">开始刷机</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function brush(){
    var date = $('#datepicker').val();
    var valve = $('#valve').val();
    if(!date){
        alert('请选择日期');
    }

    var url = '/tool/brush/?date=' + date + '&valve=' + valve;
    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success:function(res){
            alert(res);
        }
    });
}
</script>