<style>
    .fa-success{
        margin-top:10px;
        color:#28a745;
        display:none;
    }
    .fa-danger{
        margin-top:10px;
        color:#dc3545;
        display:none;
    }
    .lh{
        line-height:35px;
    }
</style>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">账号管理</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">首页</a></li>
                        <li class="breadcrumb-item active" aria-current="page">编辑联营方信息</li>
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
                <h5><b>编辑联营方信息</b></h5>
            </div>
            <div class="card-body">
                <div class="row form-group">
                    <label for="username" class="col-3 col-lg-1 col-form-label text-right"><b>登录名</b></label>
                    <div class="col-7 col-lg-10">
                        <span class="lh"><?=$agent['username']?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-3 col-lg-1 col-form-label text-right"><b>账号类型</b></label>
                    <div class="col-9 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-2">
                                <span class="lh"><?php $agent['admin'] == 0 ? print('自营') : print('一级代理')?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="company_name" class="col-3 col-lg-1 col-form-label text-right"><b>企业名称</b></label>
                    <div class="col-7 col-lg-10">
                        <span class="lh"><?=$agent['company_name']?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="credit_code" class="col-3 col-lg-1 col-form-label text-right"><b>信用代码</b></label>
                    <div class="col-7 col-lg-10">
                        <span class="lh"><?=$agent['credit_code']?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="contact_address" class="col-3 col-lg-1 col-form-label text-right"><b>企业地址</b></label>
                    <div class="col-9 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-2 form-group">
                                <select class="form-control">
                                    <option value=""><?=$agent['province']?></option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 form-group">
                                <select class="form-control">
                                    <option value=""><?=$agent['city']?></option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 form-group">
                                <select class="form-control">
                                    <option value=""><?=$agent['district']?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 col-lg-1 col-form-label text-right"></div>
                    <div class="col-7 col-lg-10">
                        <input id="contact_address" type="text" value="<?=$agent['contact_address']?>" placeholder="企业联系地址" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="contact_address_success" class="fas fa-check-circle fa-success"></i>
                        <i id="contact_address_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="corporation" class="col-3 col-lg-1 col-form-label text-right"><b>法人姓名</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="corporation" type="text" value="<?=$agent['corporation']?>" placeholder="法人姓名" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="corporation_success" class="fas fa-check-circle fa-success"></i>
                        <i id="corporation_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="id_card" class="col-3 col-lg-1 col-form-label text-right"><b>身份证</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="id_card" type="text" value="<?=$agent['id_card']?>" placeholder="法人身份证" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="id_card_success" class="fas fa-check-circle fa-success"></i>
                        <i id="id_card_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="mobile" class="col-3 col-lg-1 col-form-label text-right"><b>联系电话</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="mobile" type="text" value="<?=$agent['mobile']?>" placeholder="联系电话" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="mobile_success" class="fas fa-check-circle fa-success"></i>
                        <i id="mobile_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-3 col-lg-1 col-form-label text-right"></label>
                    <div class="col-9 col-lg-10">
                        <input id="agent_id" type="hidden" value="<?=$agent['id']?>">
                        <input id="save" type="button" class="btn btn-primary" value="保存">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/region.js"></script>
<script>
    $(function(){

        //初始化表单提交错误信息
        var submit = {
            contact_address:'',
            corporation:'',
            id_card:'',
            mobile:''
        };


        //企业地址文本框校验
        $('#contact_address').change(function(){
            submit.contact_address = jvalidate('contact_address', '请输入企业地址');
        });

        //法人姓名文本框校验
        $('#corporation').change(function(){
            submit.corporation = jvalidate('corporation', '请输入法人姓名');
        });

        //法人身份证文本框校验
        $('#id_card').change(function(){
            submit.id_card = jvalidate('id_card', '请输入法人身份证');
        });

        //联系电话文本框校验
        $('#mobile').change(function(){
            submit.mobile = jvalidate('mobile', '请输入联系电话');
        });

        //保存
        $('#save').click(function(){

            //遍历错误信息并弹窗输出
            for(var k in submit){
                if(submit[k] != ''){
                    Dialog.error( "错误", submit[k]);
                    return;
                }
            }

            //ajax提交保存信息
            Dialog({
                showTitle: false,
                content: "<h5><b>是否确认保存</b></h5>",
                ok: {
                    callback: function () {
                        $('#save').attr('disabled', true);
                        Dialog.waiting("处理中，请等待...");
                        //获取参数
                        let data = {
                            agent_id:$('#agent_id').val(),
                            contact_address:$('#contact_address').val(),
                            corporation:$('#corporation').val(),
                            id_card:$('#id_card').val(),
                            mobile:$('#mobile').val(),
                            '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
                        }
                        $.ajax({
                            url: '/manager/ajax_edit',
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function(res){
                                Dialog.close();
                                if(res.res) {
                                    $('#save').attr('disabled', false);
                                    Dialog.success("提示", '保存成功').ok(function () {
                                        location.href = '/manager/list';
                                    });
                                }
                                else{
                                    Dialog.error("错误", '系统错误，请联系管理员');
                                }
                            }
                        });
                    }
                },
                cancel: {}
            });
        });

    });

    //企业名称、信用代码、企业地址校验逻辑
    function jvalidate(id, msg){
        $('#' + id + '_success').css('display','none');
        $('#' + id + '_danger').css('display','none');
        if($('#' + id).val() == ''){
            $('#' + id + '_danger').css('display','block');
            $('#' + id).css('border-color','#dc3545');
            Dialog.error( "错误", msg);
            return msg;
        }
        $('#' + id).css('border-color', '#28a745');
        $('#' + id + '_success').css('display','block');
        return '';
    }

    //校验联营方开户内容
    function validate(data, id){
        var msg = '';
        $.ajax({
            url:'/manager/ajax_validate',
            type:'get',
            async:false,
            data:data,
            dataType:'json',
            success:function(res){
                if(res.status){
                    $('#' + id).css('border-color', '#28a745');
                    $('#' + id + '_success').css('display','block');
                }
                else{
                    $('#' + id).css('border-color','#dc3545');
                    $('#' + id + '_danger').css('display','block');
                    Dialog.error( "错误", res.msg);
                    msg = res.msg;
                }
            }
        });
        return msg;
    }
</script>