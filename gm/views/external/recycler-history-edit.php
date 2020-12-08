<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="/concept/libs/css/style.css"> -->
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">

    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="/concept/vendor/layui/css/layui.css"  media="all">

    <?php if ($history['status'] == 1) { ?>
        <title>提交到账回执</title>
    <?php } else {?>
         <title>查看到账回执</title>
    <?php } ?>
    <style>
        .row {
            padding: 0;
            margin: 0;
        }
        .area-select {
            margin-bottom: 30px;
            /* padding:0; */
        }

        .area-title {
            margin: 20px 0 10px;
        }

        .card-header {
            padding: 1rem 1.25rem;
        }

        .butt {
            border: solid 1px #ced4da;
            cursor: pointer;
        }

        .butt:last-child {
            border-left: none;
        }

        .butt:hover {
            background-color: #05aafe;
            color: #fff;
        }

        .row-button {
            line-height: 62px;
            margin: -20px;
            margin-top: 30px;
        }

        label {
            margin-bottom: 0;
        }

        .machine {
            height: 269px;
            border: 1px solid #D2D2E3;
            padding: 10px 20px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .machine-used {
            min-height: 24px;
            margin-top: 15px;
            font-size:12px;
            font-family:PingFangSC-Bold,PingFang SC;
            font-weight:bold;
            color:rgba(239,24,44,1);
            max-height: 220px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .flex-between {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
        }
        .layui-form-select{
            width: 100%;
        }
        .flex-start{
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-start;
            align-items: flex-start;
        }
        #ossfile{
            max-width: 200px;
        }
        .hiddenClass .select2 {
            display:none;
        }
    </style>
</head>

<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-header">
        <?php if ($history['status'] == 1) { ?>
            <div>提交到账回执</div>
        <?php } else {?>
            <div>查看到账回执</div>
        <?php } ?>
    </div>
    <div class="card-body" style="padding-bottom: 0;">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>回收商名称</th>
                        <th>回收商编号</th>
                        <th>汇款银行卡号</th>
                        <th>汇款金额</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$history['nick_name']?></td>
                            <td><?=$history['external_recycler_id']?></td>
                            <td><?=$history['income_bank']?></td>
                            <td><?=$history['income_amount']?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-body" style="padding-top: 0;">
        <div class="row">
            <div class="area-title">到账日期</div>
            <input type="text" id="reach_time" value="<?= $history['reach_time']?>" class="form-control col-12"  />
        </div>
        <div class="row">
            <div class="area-title">到账金额</div>
            <input type="number" id="reach_amount" value="<?= $history['reach_amount']?>" class="form-control col-12"/>
        </div>
        <div class="row">
            <div class="area-title">银行流水编号</div>
            <input type="number" id="reach_bank" value="<?= $history['reach_bank']?>" class="form-control col-12"/>
        </div>
        <?php if ($history['status'] == 1) { ?>
            <div class="row">
                <div class="area-title"  for="inputName">  上传截图:</div>
                <label class="col-sm-10 col-md-5 col-lg-5  " id="container" style="vertical-align: top" >
                    <div   class="flex-start" onclick="return false">
                        <!--  -->
                        <div id="selectfiles" class=""  style="height: auto;margin-right: 15px;margin-top: 20px;">
                            <a
                                    href="javascript:void(0);">
                                <!--avatarPreview-->
                                <img  id="avatarPreview" src="../img/sample.png"
                                      title="点击更换图片"
                                      style=" width: 120px; height: 100px">
                            </a>
                        </div>
                        <div  style="min-height:122px;margin-top: 20px;">
                            <div class="col-sm-offset-3" id="postfiles"><a
                                        href="javascript:void(0);"
                                        class='btn btn-sm btn-info'><i
                                            class="fa fa-cloud-upload">开始上传</i></a>
                            </div>
                            <div class="form-group"
                                 style="margin-top: 5px ;padding-bottom: 0;margin-bottom: 0px">
                                <div id="ossfile" class="col-sm-offset-3"></div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="row">
                <span style="color: red">注意: 操作确认到账后回收商的余额实时增加，不可撤销。</span>
            </div>
        <?php } else {?>
            <div class="row">
                <div class="area-title" >  上传截图:</div>
                <label class="col-sm-10 col-md-5 col-lg-5  " style="vertical-align: top" >
                    <div   class="flex-start" >
                        <!--  -->
                        <div  class=""  style="height: auto;margin-right: 15px;margin-top: 20px;">
                            <a href="javascript:void(0);" onclick="look(this)" >
                                <!--avatarPreview-->
                                <img  src="<?= $history['reach_img'] ?>"
                                      style=" width: 120px; height: 100px">
                            </a>
                        </div>
                    </div>
                </label>
            </div>
        <?php } ?>


        <div class="row row-button">
            <input type="hidden" id="id" value="<?= $history['id']?>" class="form-control col-12"/>
            <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
            <?php if ($history['status'] == 1) { ?>
                <div id="save" class="col-6 text-center butt" onclick="save()">确定</div>
            <?php }?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script src="/concept/vendor/layui/layui.js" charset="utf-8"></script>

<!-- Javascript -->

<script type="text/javascript" src="/oss/lib/crypto1/crypto/crypto.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/hmac/hmac.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/sha1/sha1.js"></script>
<script type="text/javascript" src="/oss/lib/base64.js"></script>
<script type="text/javascript" src="/oss/lib/plupload-2.1.2/js/plupload.full.min.js"></script>

<script type="text/javascript">
    layui.use(['form','laydate'], function(){
        var laydate = layui.laydate;
        //日期范围
        laydate.render({
            elem: '#reach_time'
            ,type:'datetime'
        });
    });
    function cancel() {
        window.parent.ifr_close();
    }
    var img_urls = '';
    //保存
    function save() {
        var data = {
            id : $('#id').val().trim(),
            reach_time : $('#reach_time').val().trim(),
            reach_amount : $('#reach_amount').val().trim(),
            reach_bank : $('#reach_bank').val().trim(),
            reach_img : img_urls,
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        //校验数据
        if (data.reach_time == '') {
            Dialog.error("错误", '请输入到账日期');
            return;
        }
        if (data.reach_amount == '' || data.reach_amount <= 0) {
            Dialog.error("错误", '请输入到账金额');
            return;
        }
        if (data.reach_bank == '') {
            Dialog.error("错误", '请输入银行流水编号');
            return;
        }
        if (data.reach_img == '') {
            Dialog.error("错误", '请上传截图');
            return;
        }

        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定提交</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/external/recycler-history-edit',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status == 200) {
                                Dialog.success("提示", '操作成功');
                                setTimeout(function(){
                                    parent.location.reload();
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

    function look(obj){
        var url = $(obj).find('img').attr('src');
        var res = getImageSize(url);
        Dialog({
            width: res[0] * 0.6,
            imageContent: {
                src: url,
                height: res[1]  * 0.6
            },
            showButton: false,
            showTitle: false,
            maskClose: true
        });
    }

    // 获取图片真实高度
    function getImageSize(url){
        var img = new Image();
        img.src = url;
        // 如果图片被缓存，则直接返回缓存数据
        if(img.complete){
            return [img.width, img.height];
        }else{
            return [img.width, img.height];
        }
    }


    //阿里云上传
    accessid = 'LTAI5OVoMFrYKUTh';
    accesskey = 'Z5QI8SoEsV1OWKtNErji8Min4qGlb5';

    var now = new Date();
    var time = now.getTime();
    host = 'https://xiaosongshu-special.oss-cn-shanghai.aliyuncs.com/';
    g_dirname = ''
    g_object_name = ''
    g_object_name_type = 'random_name'


    var policyText = {
        "expiration": "2031-01-01T12:00:00.000Z", //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
        "conditions": [
            ["content-length-range", 0, 1048576000] // 设置上传文件的大小限制
        ]
    };

    var policyBase64 = Base64.encode(JSON.stringify(policyText))
    message = policyBase64
    var bytes = Crypto.HMAC(Crypto.SHA1, message, accesskey, {asBytes: true});
    var signature = Crypto.util.bytesToBase64(bytes);

    function check_object_radio() {
        var tt = document.getElementsByName('myradio');
        for (var i = 0; i < tt.length; i++) {
            if (tt[i].checked) {
                g_object_name_type = tt[i].value;
                break;
            }
        }
    }

    function get_dirname() {
        dir = '';
        if (dir != '' && dir.indexOf('/') != dir.length - 1) {
            dir = dir + '/'
        }
        //alert(dir)
        g_dirname = dir
    }

    function random_string(len) {
        len = len || 32;
        var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        var maxPos = chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }

    function random_date(){
        now = timestamp = Date.parse(new Date()) / 1000;

        return now;
    }

    function get_suffix(filename) {
        pos = filename.lastIndexOf('.')
        suffix = ''
        if (pos != -1) {
            suffix = filename.substring(pos)
        }
        return suffix;
    }

    function calculate_object_name(filename) {
        if (g_object_name_type == 'local_name') {
            g_object_name += "${filename}"
        } else if (g_object_name_type == 'random_name') {
            //suffix = get_suffix(filename)
            // g_object_name = g_dirname + random_date(10) + suffix  .png.png
            //ar=filename.split(".");

            g_object_name = g_dirname +random_date() +filename
        }
        return ''
    }

    function get_uploaded_object_name(filename) {
        if (g_object_name_type == 'local_name') {
            tmp_name = g_object_name
            tmp_name = tmp_name.replace("${filename}", filename);
            return tmp_name
        } else if (g_object_name_type == 'random_name') {
            return g_object_name
        }
    }

    function set_upload_param(up, filename, ret) {
        g_object_name = g_dirname;
        if (filename != '') {
            suffix = get_suffix(filename)
            calculate_object_name(filename)
        }

        new_multipart_params = {
            'key': g_object_name,
            'policy': policyBase64,
            'OSSAccessKeyId': accessid,
            'success_action_status': '200', //让服务端返回200,不然，默认会返回204
            'signature': signature,
        };

        up.setOption({
            'url': host,
            'multipart_params': new_multipart_params
        });
        up.start();
    }

    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'selectfiles',
        //multi_selection: false,
        container: document.getElementById('container'),
        flash_swf_url: 'lib/plupload-2.1.2/js/Moxie.swf',
        silverlight_xap_url: 'lib/plupload-2.1.2/js/Moxie.xap',
        url: 'http://oss.aliyuncs.com',

        init: {
            PostInit: function () {
                document.getElementById('ossfile').innerHTML = '';
                document.getElementById('postfiles').onclick = function () {
                    set_upload_param(uploader, '', false);
                    return false;
                };
            },

            FilesAdded: function (up, files) {
                plupload.each(files, function (file) {
                    document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + ' <b></b>'
                        + '<div class="progress"><div class="progress-bar" style="width: 0%;background-color: rgb(92, 184, 92);"></div></div>'
                        + '</div>';
                });
            },

            BeforeUpload: function (up, file) {
                check_object_radio();
                get_dirname();
                set_upload_param(up, file.name, true);
            },

            UploadProgress: function (up, file) {
                var d = document.getElementById(file.id);
                d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var prog = d.getElementsByTagName('div')[0];
                var progBar = prog.getElementsByTagName('div')[0]
                progBar.style.width = 2 * file.percent + 'px';
                progBar.setAttribute('aria-valuenow', file.percent);
            },

            FileUploaded: function (up, file, info) {
                if (info.status == 200) {
                    $("#avatarPreview").attr('src', host +  get_uploaded_object_name(file.name));  /*预览图片*/
                    $("#postfiles").css('display','none');
                    if (img_urls == '') {
                        img_urls = img_urls + host +  get_uploaded_object_name(file.name)
                    } else {
                        img_urls = img_urls + ';' + host +  get_uploaded_object_name(file.name)
                    }
                } else {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                }
            },

            Error: function (up, err) {
                Dialog.error("错误", err.response);
            }
        }
    });

    uploader.init();
</script>
