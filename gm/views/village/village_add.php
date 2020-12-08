<!DOCTYPE html>
<html>

<head>
    <title>新增小区</title>
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
<!--                                            <div class="hidden" style="display: none;">-->
<!--                                                <input type="text" name="csrf" value="f04aef7" id="csrf" class="form-control">-->
<!--                                            </div>-->
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    小区名称:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="name" value="" id="name" class="form-control" placeholder="小区名称">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCode" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    所在区域:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <select class="form-control" name="area_id" id="area_id" onchange="changeArea();">
                                                        <option value="0">全部</option>
                                                        <?php foreach ($area_list as $v){?>
                                                            <option value="<?=$v['id']?>"><?=$v['name']?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="street_select">
                                                <label for="inputCode" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    镇/街道:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <select class="form-control" name="code" id="code">
                                                        <option value="0">全部</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputTown" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    详细地址:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="town" value="" id="town" class="form-control" placeholder="详细地址">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputLongitude" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    经度:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="longitude" value="" id="longitude" class="form-control" placeholder="经度">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputLatitude" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    纬度:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="latitude" value="" id="latitude" class="form-control" placeholder="纬度">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <button onclick="village_add();" class="btn btn-lg btn-success">添加</button>
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
<script>
    let street_list = <?=json_encode($street_list)?>;
    function changeArea() {
        var area_id = $('#area_id').val()
        // var code = $('#code').html()
        $('#code').val(0)
        var html = '<label for="inputCode" class="col-sm-2 col-md-4 col-lg-3 control-label">\n' +
            '镇/街道:' +
            '</label>' +
            '<div class="col-sm-10 col-md-8 col-lg-9 ">\n' +
            '<select class="form-control" name="code" id="code"><option value="0">全部</option>'
        if (typeof (street_list[area_id]) != 'undefined') {
            for (var i = 0; i < street_list[area_id].length; i++) {
                html += '<option value="' + street_list[area_id][i].code + '">' + street_list[area_id][i].name + '</option>'
            }
        }
        html += '</select></div>'
        $('#street_select').html(html)
    }
    function village_add() {
        var name = $('#name').val()
        var code = $('#code').val()
        var town = $('#town').val()
        var longitude = $('#longitude').val()
        var latitude = $('#latitude').val()
        if (name == '') {
            alert('请输入小区名称')
            return
        }
        if (code == '') {
            alert('请选择镇/街道')
            return
        }
        if (town == '') {
            alert('请输入详细地址')
            return
        }
        if (isNaN(longitude) || longitude <= 0) {
            alert('请输入正确经度')
            return
        }
        if (isNaN(latitude) || latitude <= 0) {
            alert('请输入正确纬度')
            return
        }
        var data = {
            name: name,
            code: code,
            town: town,
            longitude: longitude,
            latitude: latitude,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url:'/villageoption/add',
            type:'post',
            data:data,
            dataType:'json',
            success:function(res){
                window.parent.dataTable({});
                alert(res.msg)
            }
        });

    }
</script>