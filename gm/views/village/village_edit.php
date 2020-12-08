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

    <title>小区编辑</title>
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
            margin-top: 26px;
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
            margin-top: 45px;
        }
    </style>
</head>

<body>
    <div class="card" style="margin-bottom:0px">
        <div class="card-header">
            <div><b>添加小区</b></div>
        </div>
        <div class="card-body">
            <div class="row ">
                <h5 class=""><b>小区ID</b></h5>
                <input type="text" id="pid" value="<?= $village['p_id'] ?>" class="form-control col-12" disabled  readonly/>
            </div>
            <div class="">
                <h5 class="area-title"><b>省市区</b></h5>
                <div class="row">
                    <div class="col-lg-4 col-sm-12 area-select">
                        <select id="province" class="form-control">
                            <!-- <option value="">选择省</option> -->
                            <?php foreach ($province as $v) { ?>
                                <option value="<?= $v['id'] ?>" <?php if ($village['province_name'] == $v['fullname']) { ?> selected<?php } ?>><?= $v['fullname'] ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-12 area-select">
                        <select id="city" class="form-control">
                            <!-- <option value="">选择市</option> -->
                            <option value=""><?= $village['city'] ?> </option>

                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-12 area-select">
                        <select id="district" class="form-control">
                            <!-- <option value="">选择区</option> -->
                            <option value=""><?= $village['county_name'] ?> </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <h5><b>街镇</b></h5>
                <select id="street" class="form-control">
                    <!-- <option value="">选择街道</option> -->
                    <option value=""><?= $village['street_name'] ?> </option>
                </select>
            </div>
            <div class="row">
                <h5 class="area-title"><b>详细地址</b></h5>
                <input type="text" id="location" value="<?= $village['town_name'] ?>" class="form-control col-12" />
            </div>
            <div class="row row-button">
                <div id="cancel" class="col-6 text-center butt"><b>取消</b></div>
                <div id="save" class="col-6 text-center butt" onclick="save()"><b>确定</b></div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript" src="/js/region.js"></script>
<script>
    jQuery(document).ready(function($) {
        console.log('<?= $village['province_name']?>' )
        var pid = $('#province').val()
        if (pid != '') {
            var city = 'city';
            var city_name = '<?= $village['city'] ?>';
            address(pid, city, city_name)
        }

        var cid = $('#city').val();
        if (cid != '') {
            var district = 'district';
            var county_name = '<?= $village['county_name'] ?>';
            address(cid, district, county_name)
        }

        var did = $('#district').val();
        if (did != '') {
            var street = 'street';
            var street_name = '<?= $village['street_name'] ?>';
            address(did, street, street_name)
        }
    })



    $('#cancel').click(function() {
        window.parent.ifr_close();
    })

    function address(pid, type, name) { //封装地址请求三级联动
        $.ajax({
            url: '/manager/region_tx',
            type: 'get',
            data: {
                'pid': pid,
                'type': type
            },
            dataType: 'json',
            async: false,
            success: function(res) {
                var html = '';
                for (var i in res) {
                    var native = '';
                    if (name == res[i].fullname) {
                        native = 'selected';
                    }
                    html += '<option value="' + res[i].id + '"' + native + '>' + res[i].fullname + '</option>';
                }
                $('#' + type).html(html);

            }
        });
    }

    function save() {
        var data = {
            id:'<?= $village['p_id']?>',
            province_name: $('#province').find('option:selected').text().trim(),
            city: $('#city').find('option:selected').text().trim(),
            county_name: $('#district').find('option:selected').text().trim(),
            street_name: $('#street').find('option:selected').text().trim(),
            town_name: $('#location').val().trim(),
            '_csrf-gm': '<?= Yii::$app->request->csrfToken ?>'
        };
        console.log(data)
        // return
       //校验数据
        if (data.province_name == '') {
            Dialog.error("错误", '请选择省');
            return;
        }
        if (data.city == '') {
            Dialog.error("错误", '请选择市');
            return;
        }
        if (data.county_name == '') {
            Dialog.error("错误", '请选择区');
            return;
        }
        if (data.street_name == '') {
            Dialog.error("错误", '请选择街/镇');
            return;
        }
        if (data.town_name == '') {
            Dialog.error("错误", '请输入详细地址');
            return;
        }
      
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确定保存</b></h5>",
            ok: {
                callback: function() {
                    $('.mini-dialog-container').remove();
                    $.ajax({
                        url: '/village/village_save',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function(res) {
                            $('.mini-dialog-container').remove();
                            if (res.status==200) {
                                Dialog.success("提示", '操作成功');
                                window.parent.ifr_close();
                            } else {
                                Dialog.error("错误", res.msg);
                            }
                        },
                        beforeSend: function(){
                            Dialog.waiting("处理中，请等待...");
                        }
                    });
                }
            }
        });
    }
</script>