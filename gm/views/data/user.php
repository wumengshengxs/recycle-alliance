<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">注册用户列表</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">数据统计管理</li>
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
                <h5><b>注册用户列表</b></h5>
                <div class="row">
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" class="layui-input layui-inline"  placeholder="注册日期" id="date_time" readonly >
                    </div>
                    <div class="col-12 col-lg-2 col-sm-12 form-group">
                        <input type="text" id="phone" placeholder="请输入手机号" style="height: 42px" class="form-control" value="<?=  !empty($_GET['phone']) ? $_GET['phone'] : ''?>">
                    </div>

                    <div class="col-12 col-lg-2 input-group mb-3 form-group ">
                        <select id="status" class="form-control " style="height: 42px">
                            <option value="">账户状态</option>
                            <option value="2">已拉黑</option>
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
                            <th>用户id</th>
                            <th>昵称</th>
                            <th>手机号</th>
                            <th>账户状态</th>
                            <th>open_id</th>
                            <th>注册日期</th>
                            <th>更新时间</th>
                            <th>备注</th>
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
        var url = '/data/ajax-user';
        var params = {};
        window.dataTable(params, url);
        layui.use(['form','laydate'], function(){
            var laydate = layui.laydate;
            //日期范围
            laydate.render({
                elem: '#date_time'
                ,range: true
            });
        });
    });
    $('#search').click(function(){
        var date_time = $('#date_time').val();
        var phone = $('#phone').val();
        var status = $('#status').val();
        var params = {};
        if(date_time != ''){
            params.date_time = date_time;
        }
        if(phone != ''){
            params.phone = phone;
        }
        if(status != ''){
            params.status = status;
        }

        var url = '/data/ajax-user';
        window.dataTable(params, url);
    });

    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    //封号/解封
    function seal(id,status)
    {
        var data = {
            id: id,
            status: status,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        var title = '是否确认对该账户进行封号处理吗？';
        if (status == 2){
             title = '是否确解封该账户？';
        }
        Dialog({
            showTitle: false,
            content: "<h5><b>"+title+"</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/data/user-edit-status',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", res.msg);
                                setTimeout(function(){
                                    parent.location.reload();
                                    // window.parent.ifr_close();
                                },1500);
                            } else {
                                Dialog.error("错误", res.msg);
                            }
                        },
                        beforeSend: function() {
                            Dialog.waiting("处理中，请等待...");
                        }
                    });
                }
            }
        });
    }

    //备注
    function remark(id){
        Dialog({
            showTitle: false,
            width: 500,
            iframeContent: {
                src: '/data/user-remark?id='+id,
                height: 320
            },
            bodyScroll:false,
            showButton:false
        });
    }
    //查看排名
    function rank(phone_num)
    {
        Dialog({
            title: '用户投递排名查询',
            width: 900,
            iframeContent: {
                src: '/data/user-rank?phone_num='+phone_num,
                height: 600
            },
            bodyScroll:false,
            showButton:false
        });
    }

    //提现详情
    function withdraw(id)
    {
        Dialog({
            title: '用户提现详情',
            width: 830,
            iframeContent: {
                src: '/data/user-withdraw?id='+id,
                height: 600
            },
            bodyScroll:false,
            showButton:false
        });
    }




</script>