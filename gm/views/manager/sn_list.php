<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">激活码管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">激活码管理</li>
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
                <h5><b>激活码管理</b></h5>
                <div class="row">
                    <h3 class="col-12 col-lg-8" style="margin-top:10px;"><?=$agent['company_name']?></h3>
<!--                    --><?php //if(!$agent['admin']){?>
                    <div class="col-12 col-lg-4 text-right">
                        <a href="javascript:void(0)" onclick="sn_add(<?=$agent['id']?>)" class="btn btn-primary">新增激活码</a>
                    </div>
<!--                    --><?php //}?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" style="min-width:1554px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                        <tr>
                            <th>激活码</th>
                            <th>创建时间</th>
                            <th>激活时间</th>
                            <th>激活状态</th>
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
        var url = '/manager/ajax_sn_list';
        var params = {'agent_id':<?=$agent['id']?>};
        window.dataTable(params, url);
    })

    function sn_add(id){
        Dialog({
            showTitle: false,
            width: 500,
            iframeContent: {
                src: '/manager/sn_add/?id=' + id,
                height: 224
            },
            bodyScroll:false,
            showButton: false
        });
    }

    function close_ifr(){
        Dialog.close();
    }
</script>