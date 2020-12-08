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
                        <li class="breadcrumb-item active" aria-current="page">创建联营方账号</li>
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
                <h5><b>创建联营方账号</b></h5>
            </div>
            <div class="card-body">
                <div class="row form-group">
                    <label for="username" class="col-3 col-lg-1 col-form-label text-right"><b>登录名</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="username" type="text" placeholder="请输入联营人手机号" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="username_success" class="fas fa-check-circle fa-success"></i>
                        <i id="username_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-3 col-lg-1 col-form-label text-right"><b>账号类型</b></label>
                    <div class="col-9 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-2">
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="admin_type" checked="" value="0" class="custom-control-input"><span class="custom-control-label">自营</span>
                                </label>
                            </div>
                            <div class="col-12 col-lg-2">
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="admin_type" value="1" class="custom-control-input"><span class="custom-control-label">一级联营</span>
                                </label>
                            </div>
                            <!--
                            <div class="col-12 col-lg-2">
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="admin_type" value="2" class="custom-control-input"><span class="custom-control-label">二级联营</span>
                                </label>
                            </div>
                            -->
                        </div>
                    </div>
                </div>

                <div class="row form-group parent" style="display:none">
                    <label for="parent" class="col-3 col-lg-1 col-form-label text-right"><b>账号所属</b></label>
                    <div class="col-7 col-lg-10">
                        <select id="parent" class="form-control">
                            <option value="">请选择一级联营</option>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="contract" class="col-3 col-lg-1 col-form-label text-right"><b>合同编号</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="contract" type="text" placeholder="合同编号" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="contract_success" class="fas fa-check-circle fa-success"></i>
                        <i id="contract_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="machine_num" class="col-3 col-lg-1 col-form-label text-right"><b>设备数量</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="machine_num" type="text" placeholder="设备采购数量" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="machine_num_success" class="fas fa-check-circle fa-success"></i>
                        <i id="machine_num_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="company_name" class="col-3 col-lg-1 col-form-label text-right"><b>企业名称</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="company_name" type="text" placeholder="企业名称" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="company_name_success" class="fas fa-check-circle fa-success"></i>
                        <i id="company_name_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="credit_code" class="col-3 col-lg-1 col-form-label text-right"><b>信用代码</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="credit_code" type="text" placeholder="统一社会信用代码" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="credit_code_success" class="fas fa-check-circle fa-success"></i>
                        <i id="credit_code_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="contact_address" class="col-3 col-lg-1 col-form-label text-right"><b>企业地址</b></label>
                    <div class="col-9 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-2 form-group">
                                <select id="province" class="form-control">
                                    <option value="">选择省</option>
                                    <?php foreach ($province as $v){?>
                                        <option value="<?=$v['id']?>"><?=$v['fullname']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 form-group">
                                <select id="city" class="form-control">
                                    <option value="">选择市</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 form-group">
                                <select id="district" class="form-control">
                                    <option value="">选择区县</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 col-lg-1 col-form-label text-right"></div>
                    <div class="col-7 col-lg-10">
                        <input id="contact_address" type="text" placeholder="企业联系地址" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="contact_address_success" class="fas fa-check-circle fa-success"></i>
                        <i id="contact_address_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="corporation" class="col-3 col-lg-1 col-form-label text-right"><b>法人姓名</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="corporation" type="text" placeholder="法人姓名" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="corporation_success" class="fas fa-check-circle fa-success"></i>
                        <i id="corporation_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="id_card" class="col-3 col-lg-1 col-form-label text-right"><b>身份证</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="id_card" type="text" placeholder="法人身份证" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="id_card_success" class="fas fa-check-circle fa-success"></i>
                        <i id="id_card_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="mobile" class="col-3 col-lg-1 col-form-label text-right"><b>联系电话</b></label>
                    <div class="col-7 col-lg-10">
                        <input id="mobile" type="text" placeholder="联系电话" class="form-control">
                    </div>
                    <div class="col-2 col-lg-1">
                        <i id="mobile_success" class="fas fa-check-circle fa-success"></i>
                        <i id="mobile_danger" class="fas fa-minus-circle fa-danger"></i>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-3 col-lg-1 col-form-label text-right"></label>
                    <div class="col-9 col-lg-10">
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
    //二级联营绑定一级联营下拉框
    $('input[name=admin_type]').click(function(){
        $('.parent').hide();
        if($(this).val() == 2){
            $('.parent').show();
        }
    })

    //初始化表单提交错误信息
    var submit = {
        username:'请设置联营方登录名',
        contract:'请输入合同编号',
        machine_num:'请正确输入设备数量',
        company_name:'请输入企业名称',
        credit_code:'请输入统一社会信用代码',
        province:'请选择省份',
        city:'请选择市',
        contact_address:'请输入企业地址',
        corporation:'请输入法人姓名',
        id_card:'请输入法人身份证',
        mobile:'请输入联系电话'
    };

    //登录名文本框校验
    $('#username').change(function(){
        $('#username_success').css('display','none');
        $('#username_danger').css('display','none');
        if($(this).val() == ''){
            $('#username_danger').css('display','block');
            $('#username').css('border-color','#dc3545')
            Dialog.error( "错误", '请设置联营方登录名');
            submit.username = '请设置联营方登录名';
        }
        else{
            var data = {'username':$(this).val()};
            submit.username = validate(data, 'username');
        }
    });

    //合同文本框校验
    $('#contract').change(function(){
        $('#contract_success').css('display','none');
        $('#contract_danger').css('display','none');
        if($(this).val() == ''){
            $('#contract_danger').css('display','block');
            $('#contract').css('border-color','#dc3545');
            Dialog.error( "错误", '请输入合同编号');
            submit.contract = '请输入合同编号';
        }
        else{
            var data = {'contract':$(this).val()};
            submit.contract = validate(data, 'contract');
        }
    });

    //设备数量文本框校验
    $('#machine_num').change(function(){
        $('#machine_num_success').css('display','none');
        $('#machine_num_danger').css('display','none');
        if($(this).val() == ''
            || isNaN($(this).val())
            || Math.floor($(this).val()) != $(this).val()
            || $(this).val() == 0){
            $('#machine_num_danger').css('display','block');
            $('#machine_num').css('border-color','#dc3545');
            Dialog.error( "错误", '请正确输入设备数量');
            submit.machine_num = '请正确输入设备数量';
        }
        else{
            $('#machine_num').css('border-color', '#28a745');
            $('#machine_num_success').css('display','block');
            submit.machine_num = '';
        }
    });

    //企业名称文本框校验
    $('#company_name').change(function(){
        submit.company_name = jvalidate('company_name', '请输入企业名称');
    });

    //信用代码文本框校验
    $('#credit_code').change(function(){
        submit.credit_code = jvalidate('credit_code', '请输入统一社会信用代码');
    });

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
        //校验省市区县
        $('#province').val() != '' ? submit.province = '' : submit.province = '请选择省份';
        $('#city').val() != '' ? submit.city = '' : submit.city = '请选择市';

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
                    Dialog.waiting( "处理中，请等待..." );
                    //获取参数
                    let data = {
                        username:$('#username').val(),
                        admin:$('input[name=admin_type]:checked').val(),
                        contract:$('#contract').val(),
                        machine_num:$('#machine_num').val(),
                        company_name:$('#company_name').val(),
                        credit_code:$('#credit_code').val(),
                        province:$("#province").find("option:selected").text(),
                        province_code:$('#province').val(),
                        city:$("#city").find("option:selected").text(),
                        city_code:$('#city').val(),
                        district:$("#district").find("option:selected").text() == '选择区县' ? '' : $("#district").find("option:selected").text(),
                        district_code:$('#district').val() == '' ? '0' : $('#district').val(),
                        contact_address:$('#contact_address').val(),
                        corporation:$('#corporation').val(),
                        id_card:$('#id_card').val(),
                        mobile:$('#mobile').val(),
                        '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
                    }
                    $.ajax({
                        url: '/manager/ajax_add',
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