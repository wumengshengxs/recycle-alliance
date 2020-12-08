<!DOCTYPE html>
<html>

<head>
    <title>机器信息编辑</title>
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
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    小区名称:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="name"
                                                           value="<?= $data['community_name'] ?>"
                                                           id="community_name" class="form-control" placeholder="姓名">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    详细地址:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <input type="text" name="name"
                                                           value="<?= $data['location'] ?>"
                                                           id="location" class="form-control" placeholder="姓名">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName" class="col-sm-2 col-md-4 col-lg-3 control-label">
                                                    变更回收员:
                                                </label>
                                                <div class="col-sm-10 col-md-8 col-lg-9 ">
                                                    <select id="name">
                                                        <option selected disabled><?= $nick_name['nick_name'] ?></option>
                                                        <?php foreach ($name_list as $item) { ?>
                                                            <option id="name"
                                                                    value="<?= $item['nick_name'] ?>"><?= $item['nick_name'] ?></option>
                                                        <?php } ?>
                                                    </select></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10 col-md-offset-4 col-md-8 col-lg-9 col-lg-offset-3">
                                                    <button onclick="info_edit();" class="btn btn-lg btn-success">修改
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
    function info_edit() {
        //获取用户输入的信息
        var community_name = $('#community_name').val()
        var location = $('#location').val()
        var name = $('#name').val()
        // var name = $('#name').find("option:selected").val()

        if (community_name == '') {
            alert('请输入小区名称!')
        }
        if (location == '') {
            alert('请输入详细地址!')
        }
        if (!name) {
            alert('请选择要切换的回收员!')
            return
        }

        var data = {
            device_id: '<?=$device_id?>',
            machine_id: '<?=$machine_id?>',
            r_id: '<?=$r_id['recycler_id']?>',
            community_name: community_name,
            location: location,
            name: name,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        };

        $.ajax({
            url: '/machine/update_machine',
            type: 'post',
            data: data,

            dataType: 'json',
            success: function (res) {
                if (res.res) {
                    alert(res.msg);
                    setTimeout(function () {
                        parent.location.reload();
                    }, 500);
                } else {
                    alert(res.msg);
                }
            }
        });
    }
</script>