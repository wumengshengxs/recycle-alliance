<!DOCTYPE html>
<html>

<head>
    <title>新增版本</title>
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

    <link rel="stylesheet" type="text/css" href="/oss/style.css"/>
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
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    版本说明:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="name" value="" id="name" class="form-control" placeholder="版本说明">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    版本号:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="version" value="" id="version" class="form-control" placeholder="版本号">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    <div id="container" class="center-block">
                                                        <a id="selectfiles" href="javascript:void(0);" class='btn'>选择文件</a>
                                                        <a id="postfiles" href="javascript:void(0);" class='btn'>开始上传</a>
                                                    </div>
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <div id="ossfile"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <button onclick="recycl_change();" class="btn btn-lg btn-success">提交审核结果</button>
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

<script>
    var img_urls = ''
    function recycl_change() {
        var name = $('#name').val()
        var version = $('#version').val()
        var data = {
            name: name,
            version: version,
            link: img_urls,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url:'/machineoption/apk_add',
            type:'post',
            data:data,
            dataType:'json',
            success:function(res){
                window.parent.location.reload();
                alert(res.msg)
            }
        });

    }


    //阿里云上传
    accessid= 'LTAI5OVoMFrYKUTh';
    accesskey= 'Z5QI8SoEsV1OWKtNErji8Min4qGlb5';
    host = 'https://xiaosongshu-special.oss-cn-shanghai.aliyuncs.com/';

    g_dirname = ''
    g_object_name = ''
    g_object_name_type = 'local_name'
    now = timestamp = Date.parse(new Date()) / 1000;

    var policyText = {
        "expiration": "2021-01-01T12:00:00.000Z", //设置该Policy的失效时间，超过这个失效时间之后，就没有办法通过这个policy上传文件了
        "conditions": [
            ["content-length-range", 0, 1048576000] // 设置上传文件的大小限制
        ]
    };

    var policyBase64 = Base64.encode(JSON.stringify(policyText))
    message = policyBase64
    var bytes = Crypto.HMAC(Crypto.SHA1, message, accesskey, { asBytes: true }) ;
    var signature = Crypto.util.bytesToBase64(bytes);

    function check_object_radio() {
        var tt = document.getElementsByName('myradio');
        for (var i = 0; i < tt.length ; i++ )
        {
            if(tt[i].checked)
            {
                g_object_name_type = tt[i].value;
                break;
            }
        }
    }

    function get_dirname()
    {
        dir = '';
        if (dir != '' && dir.indexOf('/') != dir.length - 1)
        {
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

    function get_suffix(filename) {
        pos = filename.lastIndexOf('.')
        suffix = ''
        if (pos != -1) {
            suffix = filename.substring(pos)
        }
        return suffix;
    }

    function calculate_object_name(filename)
    {
        if (g_object_name_type == 'local_name')
        {
            g_object_name += "${filename}"
        }
        else if (g_object_name_type == 'random_name')
        {
            suffix = get_suffix(filename)
            g_object_name = g_dirname + random_string(10) + suffix
        }
        return ''
    }

    function get_uploaded_object_name(filename)
    {
        if (g_object_name_type == 'local_name')
        {
            tmp_name = g_object_name
            tmp_name = tmp_name.replace("${filename}", filename);
            return tmp_name
        }
        else if(g_object_name_type == 'random_name')
        {
            return g_object_name
        }
    }

    function set_upload_param(up, filename, ret)
    {
        g_object_name = g_dirname;
        if (filename != '') {
            suffix = get_suffix(filename)
            calculate_object_name(filename)
        }
        new_multipart_params = {
            'key' : g_object_name,
            'policy': policyBase64,
            'OSSAccessKeyId': accessid,
            'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
            'signature': signature,
        };

        up.setOption({
            'url': host,
            'multipart_params': new_multipart_params
        });

        up.start();
    }

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'selectfiles',
        //multi_selection: false,
        container: document.getElementById('container'),
        flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
        silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
        url : 'http://oss.aliyuncs.com',

        init: {
            PostInit: function() {
                console.log(123)
                document.getElementById('ossfile').innerHTML = '';
                document.getElementById('postfiles').onclick = function() {
                    set_upload_param(uploader, '', false);
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
                        +'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                        +'</div>';
                });
            },

            BeforeUpload: function(up, file) {
                check_object_radio();
                get_dirname();
                set_upload_param(up, file.name, true);
            },

            UploadProgress: function(up, file) {
                var d = document.getElementById(file.id);
                d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var prog = d.getElementsByTagName('div')[0];
                var progBar = prog.getElementsByTagName('div')[0]
                progBar.style.width= 2*file.percent+'px';
                progBar.setAttribute('aria-valuenow', file.percent);
            },

            FileUploaded: function(up, file, info) {
                if (info.status == 200)
                {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '上传成功';
                    if (img_urls == '') {
                        img_urls = img_urls + host + get_uploaded_object_name(file.name)
                    } else {
                        img_urls = img_urls + ';' + host + get_uploaded_object_name(file.name)
                    }
                }
                else
                {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
                }
            },

            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
            }
        }
    });

    uploader.init();

</script>