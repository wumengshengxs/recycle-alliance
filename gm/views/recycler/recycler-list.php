<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">


    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>

    <title>回收员管理</title>
    <style>
        .row {
            padding: 0;
            margin: 0;
        }

        .recycler-li {
            line-height: 46px;
            font-size: 14px;
            font-family: PingFangSC-Regular, PingFang SC;
            font-weight: 400;
            text-indent: 16px;
            cursor: pointer;
        }

        .recycler-li-active {
            background: #05AAFE;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="row ">
        <div class="col-lg-2">
            <div class="card" style="margin-bottom:0px">
                <div class="card-header">
                    <h5><b>选择清运人员</b></h5>
                    <div class="row form-group">
                        <input type="text" id="name"  class="form-control col-12" placeholder="请输入清运人员名称" value="<?= !empty($_GET['recycler']) ? $_GET['recycler'] : ''?>" />
                    </div>
                </div>
                <div class="card-body">
                    <h5></h5>
                    <ul class="list-unstyled recycler-ul">
                        <li class="recycler-li recycler-li-active" onclick="recycler(this,'')">全部</li>
                        <?php foreach ($recycler as $v) { ?>
                            <li data-id="<?= $v['id'] ?>" class="recycler-li" onclick="recycler(this,<?= $v['id'] ?>)"><?= $v['nick_name'] ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-10 col-sm-12">
            <div class="card" style="margin-bottom:0px">
                <div class="card-header">
                    <h5><b>清运人员管理</b></h5>
                    <div class="row">
                        <div class="col-12 col-lg-3 col-sm-12 form-group">
                            <select id="villageName" class="form-control" style="height: 42px">
                                <option value="">请选择</option>
                                <?php foreach ($village as $v) { ?>
                                    <option value="<?= $v['p_id']?>" <?php if ($v['p_id'] == (empty($_GET['village_id']) ? '' : $_GET['village_id'])) { ?> selected<?php } ?>><?= $v['village_name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3 input-group mb-3 form-group  col-sm-12 ">
                            <input type="text" id="deviceId" placeholder="请输入设备编号" class="form-control" value="<?=  !empty($_GET['device_id']) ? $_GET['device_id'] : ''?>">
                            <div class="input-group-append">
                                <button type="button" id="search" class="btn btn-primary">查找</button>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3  form-group  col-sm-12 ">
                            <button type="button" id="add" class="btn btn-primary" onclick="add()">添加清运人员</button>
                        </div>
                    </div>
                </div>
                <div class="card-body"">
                    <div class=" table-responsive">
                    <table id="dataTable" style="max-width:1250px;min-width:1200px;" class="table table-striped table-bordered second">
                        <thead id="dataTitle">
                            <tr>
                                <th>回收机编号</th>
                                <th>回收机所在小区</th>
                                <th>回收机详细地址</th>
                                <th>清运人员姓名</th>
                                <th>清运权限 </th>
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

</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript" src="/js/region.js"></script>
<script>
    jQuery(document).ready(function($) {
        var url = '/recycler/ajax_recycler_list';
        var params = {};
        window.dataTable(params, url);
    });

    $('#name').change(function(){
        var url = '/recycler/recycler-list';
        var recycler=$(this).val().trim()
        window.location.href=url+'?recycler='+recycler
    });

    function recycler(is, id) {
        $(is).addClass('recycler-li-active')
        $('.recycler-li').not(is).removeClass('recycler-li-active')
        var url = '/recycler/ajax_recycler_list';
        var params = {
            'recycler_id': id
        };
        window.dataTable(params, url);
    }

    $('#search').click(function(){
        var url = '/recycler/ajax_recycler_list';
            var recycler_id=$('.recycler-li-active').attr('data-id')
        var village_id = $('#villageName').val().trim();
        var device_id = $('#deviceId').val().trim();
        var params = {
            recycler_id,
            village_id,
            device_id
        };
        window.dataTable(params, url);
    })
    
    function ifr_close(){
        Dialog.close();
        $('#dataTable').DataTable().draw(false);
    }

    function add(){
        Dialog({
            showTitle: false,
            width: 800,
            iframeContent: {
                src: '/recycler/recycler_add',
                height: 856
            },
            bodyScroll:false,
            showButton:false
        });
    }
    
</script>