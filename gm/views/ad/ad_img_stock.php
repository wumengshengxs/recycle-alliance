<!DOCTYPE html>
<html>

<head>
    <!--    <title>投递明细</title>-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/lib/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/themes/flat-blue.css">
</head>
<body>
<div class="container-fluid">
    <div class="side-body">

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="switch" style="">
                                        <div class="form-horizontal">
                                            <div class="form-group " id="container">
                                                <label for="inputName" class="col-sm-4 col-md-5 col-lg-5 control-label">
                                                    <div class="center-block">
                                                        <button id="selectfiles" href="javascript:void(0);"
                                                                class='btn  btn-info col-sm-2 col-md-2 col-lg-2'>本地选择
                                                        </button>
                                                        <div id="ossfile" class="col-sm-2 col-md-2 col-lg-2 "></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="form-group ">
                                                <table class="datatable-ad-ad_img_stock table table-striped"
                                                       cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
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
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                            <input class="_csrf-gm" type="hidden"
                                                   value="<?= Yii::$app->request->csrfToken ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn  btn-info col-lg-4 " onclick="ad_confirm()">确认</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>

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
<style>/*代码在controller里面*/
    .product {
        width: 160px;
    }

    .product-top {
        box-sizing: border-box;
        width: 100%;
    }

    .product-top img {
        width: 100%;
    }

    .choose-image {
        border:5px solid #9BDF70;
    }
</style>

<script>
    function chooseImage(that) {
        $(".choose-image").removeClass("choose-image");
        $(that).addClass("choose-image");
    }

    function ad_confirm() {
        var lujing=$(".choose-image").attr("src");
        if (typeof lujing === 'undefined') {
            alert('请点击选择图片');
            return
        }
        window.parent.choose_image(lujing);
        // $(".mini-dialog-container").css("display","none");
        // window.close();
    }
    var img_name = '';
    function add_to_stock() {
        var data = {
            img_name: img_name,//广告位缩略图
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url: '/ad/add_picture_library',
            type: 'get',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.res) {
                    window.dataTable({});
                } else {
                    alert(res.msg);
                }
            }
        });


    }

    function ad_save() {
        var ad_name = $('#ad_name').val();//广告位名称
        var ad_version = $('#ad_version').val();//版本号
        //var ad_resolution_ratio = $('#ad_resolution_ratio').val();//广告图片建议分辨率

        if (ad_name == "") {
            alert('请输入广告位名称')
            return
        }
        if (ad_version == "") {
            alert('请输入版本号')
            return
        }
        if (img_urls == "") {
            alert('请上传缩略图')
            return
        }
        if (ad_resolution_ratio == "") {
            alert('请输入分辨率')
            return
        }
        /*var r = confirm("是否确认？")
        if (r != true) {
            window.location.reload();
            return
        }*/
        var data = {
            //code: '<?//=$code?>//',
            ad_name: ad_name,
            ad_version: ad_version,
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


    }

    //阿里云上传
    accessid = 'LTAI5OVoMFrYKUTh';
    accesskey = 'Z5QI8SoEsV1OWKtNErji8Min4qGlb5';
    accessid = "LTAI4G3axJ37rNexBWoBJWXy";
    accesskey = "UBDOhoJEevUUycSQ6bTrXZQisiQyrL";
    host = 'https://xiaosongshu-images.oss-cn-shanghai.aliyuncs.com/';

    g_dirname = ''
    g_object_name = ''
    g_object_name_type = 'random_name'
    now = timestamp = Date.parse(new Date()) / 1000;

    var policyText = {
        "expiration": "2025-01-01T12:00:00.000Z", //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
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
            },

            FilesAdded: function (up, files) {
                plupload.each(files, function (file) {
                    // document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                    document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + ' <b></b>'
                        + '<div class="progress"><div class="progress-bar" style="width: 0%;background-color: rgb(92, 184, 92);"></div></div>'
                        + '</div>';
                });
                //直接上传
                set_upload_param(uploader, '', false);
            },

            BeforeUpload: function (up, file) {
                // console.log(444)
                check_object_radio();
                get_dirname();
                set_upload_param(up, file.name, true);
            },

            UploadProgress: function (up, file) {
                // console.log(555)
                var d = document.getElementById(file.id);
                d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var prog = d.getElementsByTagName('div')[0];
                var progBar = prog.getElementsByTagName('div')[0]
                progBar.style.width = 2 * file.percent + 'px';
                progBar.setAttribute('aria-valuenow', file.percent);
            },

            FileUploaded: function (up, file, info) {
                if (info.status == 200) {
                    img_name = get_uploaded_object_name(file.name)
                    add_to_stock()
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