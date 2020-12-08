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

    <title>添加广告位</title>
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
            font-size: 12px;
            font-family: PingFangSC-Bold, PingFang SC;
            font-weight: bold;
            color: rgba(239, 24, 44, 1);
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
        <div>添加广告位</div>
        <div class="card-body">
            <!--<div class="form-group">
                <label for="inputName" class="col-sm-1 col-md-2 col-lg-2 control-label">
                    广告位code:
                </label>
                <label class="col-sm-10 col-md-5 col-lg-5 "> <?/*= $code */?></label>
            </div>-->
            <div class="form-group">
                <label for="inputName" class="col-sm-1 col-md-2 col-lg-2 control-label">
                    <span style="color: red">*</span>广告位类型:
                </label>
                <label class="col-sm-10 col-md-5 col-lg-5 hiddenClass">
                    <!--自适应下拉选宽度-->
                    <select class="form-control" id="ad_type" name="ad_type">

                        <?php foreach ($type_list as $k => $v) { ?>
                            <option value="<?= $v['id'] ?>"> <?= $v['name'] ?> </option>
                        <?php } ?>
                    </select>
                </label>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-1 col-md-2 col-lg-2 control-label">
                    <span style="color: red">*</span> 广告位名称:
                </label>
                <label class="col-sm-6 col-md-5 col-lg-5 ">
                    <input type="text" name="ad_name" value="" id="ad_name"
                           class="form-control"
                           onfocus="if(this.placeholder == '请填写广告位名称，如：云贵三日游广告') this.placeholder = ''"
                           onblur="if(this.placeholder =='') this.placeholder = '请填写广告名称，如：云贵三日游广告'"
                           placeholder="请填写广告位名称，如：云贵三日游广告">
                </label>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-1 col-md-2 col-lg-2 control-label" style="vertical-align: top">
                    <span style="color: red">*</span> 广告位缩略图:
                </label>
                <label class="col-sm-10 col-md-5 col-lg-5  " id="container" style="vertical-align: top" >
                    <div class="flex-start" onclick="return false">
                      <!--  -->
                        <div id="selectfiles" class=""  style="height: auto;margin-right: 15px;">
                            <a
                                    href="javascript:void(0);">
                                <!--avatarPreview-->
                                <img  id="avatarPreview" src="../img/sample.png"
                                     title="点击更换图片"
                                     style=" width: 120px; height: 100px">
                            </a>
                        </div>
                        <div  style="min-height:162px;">
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

            <div class="form-group" id="">
                <label for="inputName" class="col-sm-1 col-md-2 col-lg-2 control-label">
                    <span style="color: red">*</span>所属联营方
                </label>
                <label class="col-sm-10 col-md-5 col-lg-5 hiddenClass">
                    <!--自适应下拉选宽度-->
                    <select class="form-control" id="agent_id" name="agent_id">

                        <?php foreach ($agent_list as $k => $v) { ?>
                            <option value="<?= $v['id'] ?>"> <?= $v['username'] ?> </option>
                        <?php } ?>
                    </select>
                </label>
            </div>

            <div class="row row-button">
                <div id="canceld" class="col-6 text-center butt" onclick="cancel()">取消</div>
                <div id="ad_save" class="col-6 text-center butt" onclick="ad_save()">确定</div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/lib/js/Chart.min.js"></script>
<script type="text/javascript" src="/lib/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="/lib/js/select2.full.min.js"></script>
<script type="text/javascript" src="/lib/js/ace/ace.js"></script>
<script type="text/javascript" src="/lib/js/ace/mode-html.js"></script>
<script type="text/javascript" src="/lib/js/ace/theme-github.js"></script>
<script type="text/javascript" src="/lib/js/jquery-ui.min.js"></script>
<!-- Javascript -->
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/index.js"></script>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>

<script type="text/javascript" src="/oss/lib/crypto1/crypto/crypto.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/hmac/hmac.js"></script>
<script type="text/javascript" src="/oss/lib/crypto1/sha1/sha1.js"></script>
<script type="text/javascript" src="/oss/lib/base64.js"></script>
<script type="text/javascript" src="/oss/lib/plupload-2.1.2/js/plupload.full.min.js"></script>
<script>
    function cancel() {
        window.parent.ifr_close();
    }

    var img_urls = '';
    function ad_save() {
        var ad_name = $('#ad_name').val();//广告位名称
        var ad_type = $('#ad_type').val();//产品类型
        var agent_id = $('#agent_id').val();//所属联营方id
        // var ad_resolution_ratio = $('#ad_resolution_ratio').val();//广告图片建议分辨率

        if (ad_type == "") {
            alert('请输入产品类型')
            return
        }
        if (ad_name == "") {
            alert('请输入广告位名称')
            return
        }
        if (agent_id == "") {
            alert('请选择所属联营方')
            return
        }
        if (img_urls == "") {
            alert('请上传缩略图')
            return
        }

        var data = {
            //code: '<?=$code?>',
            code: ad_type,
            agent_id: agent_id,
            ad_name: ad_name,
            ad_version: 0,
            ad_resolution_ratio: 0,
            img_urls: img_urls,//广告位缩略图
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url: '/ad/space_create',
            type: 'get',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.res) {
                    alert(res.msg);
                    setTimeout(function () {
                        //关闭当前页面
                        window.opener = null;
                        window.open('', '_self');
                        window.close();
                        //刷新页面
                        window.location.reload();
                    }, 500);
                } else {
                    alert(res.msg);
                }
            }
        });
        window.parent.ifr_close();
        window.location.reload();

    }

    function get_table() {
        var village_name = $('#village_name').val();
        var village_code = $('#village_code').val();
        var area_type = $('#area_type').val();

        var params = {
            'village_name': village_name,
            'area_type': area_type,
            'village_code': village_code
        };
        window.dataTable(params);
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
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
            }
        }
    });

    uploader.init();


</script>