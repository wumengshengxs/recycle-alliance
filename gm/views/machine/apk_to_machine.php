<!DOCTYPE html>
<html>

<head>
    <title>筛选更新机器</title>
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
                                            <div class="form-group">
                                                <div class="col-sm-10 col-md-8 col-lg-9">
                                                    请选择厂家:
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <div class="radio float-left" style="margin-right: 20px;">
                                                        <label>
                                                            <a type="button" onclick="all_select('cang');">全部</a>
                                                        </label>
                                                    </div>
                                                    <?php
                                                    $cang_info = [
                                                        'hgj' => '黄狗机',
                                                        'wdf' => '万德福'
                                                    ];
                                                    foreach ($code as $c) { ?>
                                                        <div class="radio float-left" style="margin-right: 20px;">
                                                            <label>
                                                                <input type="checkbox" name="cang"
                                                                       onclick="changeCang();" value="<?= $c ?>">
                                                                <?= (empty($cang_info[$c]) ? $c : $cang_info[$c]) ?>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9">
                                                    请选择厂家机器版本号:
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9" id="version">
                                                    <div class="radio float-left" style="margin-right: 20px;">
                                                        <label>
                                                            <a type="button" onclick="all_select('version');">全部</a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9">
                                                    请选择镇/街道:
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9 " id="area">
                                                    <div class="radio float-left" style="margin-right: 20px;">
                                                        <label>
                                                            <a type="button" onclick="all_select('area');">全部</a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9">
                                                    请选择小区:
                                                </div>
                                                <div class="col-sm-10 col-md-8 col-lg-9 " id="community">
                                                    <div class="radio float-left" style="margin-right: 20px;">
                                                        <label>
                                                            <a type="button" onclick="all_select('community');">全部</a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <button onclick="confirm();" class="btn btn-lg btn-success">确认
                                                    </button>
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
    var big_info = <?=(json_encode($info))?>;

    function all_select(select) {
        // choose_cang = new Array();
        $("input[name='" + select + "']").each(function () {
            this.checked = true;
            // choose_cang.push(this.value)
            // alert(this.value)
        })
        if (select == 'cang') {
            changeCang()
        }
        if (select == 'version') {
            changeVersion()
        }
        if (select == 'area') {
            changeArea()
        }
    }

    function changeCang() {
        var choose_cang = new Array();
        $("input[name='cang']").each(function () {
            if (this.checked == true) {
                choose_cang.push(this.value)
            }
        })
        var version_html = '<div class="radio float-left" style="margin-right: 20px;">\n' +
            '                                                        <label>\n' +
            '                                                            <a type="button" onclick="all_select(\'version\');">全部</a>\n' +
            '                                                        </label>\n' +
            '                                                    </div>';
        var show_version = new Array();
        for (j = 0, len = big_info.length; j < len; j++) {
            if (choose_cang.indexOf(big_info[j].divece_code) > -1 && show_version.indexOf(big_info[j].divece_code + '-' + big_info[j].divece_version) < 0) {
                show_version.push(big_info[j].divece_code + '-' + big_info[j].divece_version);
                version_html += '<div class="radio float-left" style="margin-right: 20px;">\n' +
                    '                                                        <label>\n' +
                    '                                                            <input type="checkbox" name="version" onclick="changeVersion();" value="' + big_info[j].divece_code + '-' + big_info[j].divece_version + '">\n' +
                    '                                                            ' + big_info[j].divece_code + '-' + big_info[j].divece_version + '\n' +
                    '                                                        </label>\n' +
                    '                                                    </div>';
            }
        }
        $('#version').html(version_html)
        changeVersion()
    }

    function changeVersion() {
        var choose_version = new Array();
        $("input[name='version']").each(function () {
            if (this.checked == true) {
                choose_version.push(this.value)
            }
        })
        var version_html = '<div class="radio float-left" style="margin-right: 20px;">\n' +
            '                                                        <label>\n' +
            '                                                            <a type="button" onclick="all_select(\'area\');">全部</a>\n' +
            '                                                        </label>\n' +
            '                                                    </div>';
        var show_area = new Array();
        for (j = 0, len = big_info.length; j < len; j++) {
            if (choose_version.indexOf(big_info[j].divece_code + '-' + big_info[j].divece_version) > -1 && show_area.indexOf(big_info[j].street_name) < 0) {
                show_area.push(big_info[j].street_name);
                version_html += '<div class="radio float-left" style="margin-right: 20px;">\n' +
                    '                                                        <label>\n' +
                    '                                                            <input type="checkbox" name="area" onclick="changeArea();"  value="' + big_info[j].street_name + '">\n' +
                    '                                                            ' + big_info[j].street_name + '\n' +
                    '                                                        </label>\n' +
                    '                                                    </div>';
            }
        }
        $('#area').html(version_html)
        changeArea()
    }

    function changeArea() {
        var choose_area = new Array();
        $("input[name='area']").each(function () {
            if (this.checked == true) {
                choose_area.push(this.value)
            }
        })
        var choose_version = new Array();
        $("input[name='version']").each(function () {
            if (this.checked == true) {
                choose_version.push(this.value)
            }
        })
        var version_html = '<div class="radio float-left" style="margin-right: 20px;">\n' +
            '                                                        <label>\n' +
            '                                                            <a type="button" onclick="all_select(\'community\');">全部</a>\n' +
            '                                                        </label>\n' +
            '                                                    </div>';
        var show_community = new Array();
        for (j = 0, len = big_info.length; j < len; j++) {
            if (choose_version.indexOf(big_info[j].divece_code + '-' + big_info[j].divece_version) > -1 && choose_area.indexOf(big_info[j].street_name) > -1 && show_community.indexOf(big_info[j].community_name) < 0) {
                show_community.push(big_info[j].community_name);
                version_html += '<div class="radio float-left" style="margin-right: 20px;">\n' +
                    '                                                        <label>\n' +
                    '                                                            <input type="checkbox" name="community" value="' + big_info[j].community_name + '">\n' +
                    '                                                            ' + big_info[j].community_name + '\n' +
                    '                                                        </label>\n' +
                    '                                                    </div>';
            }
        }
        $('#community').html(version_html)
    }

    function confirm() {
        var choose_area = new Array();
        var choose_version = new Array();
        var choose_community = new Array();
        $("input[name='area']").each(function () {
            if (this.checked == true) {
                choose_area.push(this.value)
            }
        })
        if (choose_area.length <= 0) {
            alert('请先选择镇/街道');
            return;
        }
        $("input[name='version']").each(function () {
            if (this.checked == true) {
                choose_version.push(this.value)
            }
        })
        if (choose_version.length <= 0) {
            alert('请先选择机器版本号');
            return;
        }
        $("input[name='community']").each(function () {
            if (this.checked == true) {
                choose_community.push(this.value)
            }
        })
        if (choose_version.length <= 0) {
            alert('请先选择小区');
            return;
        }
        var data = {
            id: '<?=$id?>',
            version: choose_version,
            area: choose_area,
            community: choose_community,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url: '/machineoption/apk_to_machine',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                window.parent.location.reload();
                alert(res.msg)
            }
        });
    }
</script>