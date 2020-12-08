<link rel="stylesheet" type="text/css" href="/lib/layui/css/layui.css" media="all">
<style>
    .product {
        width: 240px;
        /* height: 238px; */
        margin: 10px 10px;
        border: 1px solid #828282;
        position: relative;
    }

    .product-top {
        box-sizing: border-box;
        height: 193px;
        width: 100%;
        padding: 5px;
        background-color: #cccccc;
    }

    .product-top img {
        width: 100%;
        height: 100%;
    }

    .product-bottom {
        box-sizing: border-box;
        width: 100%;
        height: 80px;
        padding: 10px;
        font-size: 14px;
        /*border-top: 1px solid #828282;*/
    }
    .product-button{
        position: absolute;
        bottom: 0;
        left: 0;
    }
    .del-button{
        position: absolute;
        bottom: 0;
        right: 0;
    }
    #dataTable_length{
        display:none;
    }
    .btn{
        padding:3px 6px;

    }
</style>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">广告位管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">广告位管理</li>
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
                <h5><b>广告位管理</b></h5>
                <div class="row">
                    <button class="btn  btn-info col-sm-offset-8 " onclick="add_ad_space()">新增广告位</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="max-width:1550px;min-width:1550px;" class="table table-striped table-bordered second">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    /*页面加载事件*/
    jQuery(document).ready(function($) {
        var url = '/ad/ajax_ad_space';
        var params = {};
        window.dataTable(params, url);
    });

    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function add_ad_space(){
        Dialog({
            showTitle: false,
            width: 950,
            iframeContent: {
                src: '/ad/add_ad_space',
                height: 539
            },
            bodyScroll:false,
            showButton:false
        });

    }

    function add_space(id){
        Dialog({
            showTitle: false,
            width: 950,
            iframeContent: {
                src: '/ad/set_ad?id='+id,
                height: 539
            },
            bodyScroll:false,
            showButton:false
        });
    }

    function ad_detail(id,name){
        window.open('/ad/ad_detail?id=' + id +'&name='+name);
    }

    function del_space(id)
    {
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定删除该广告位? </b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/ad/del_space',
                        type: 'post',
                        data: {id:id, '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'},
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.res) {
                                alert('删除成功');
                                window.location.reload();
                            } else {
                                Dialog.error("错误", res.msg);
                            }
                        },
                        beforeSend: function(){
                            Dialog.waiting("处理中，请等待...");
                        }
                    });
                }
            }
        });

    }




</script>
