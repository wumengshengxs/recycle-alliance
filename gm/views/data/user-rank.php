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
    <title>用户投递排名查询</title>
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
    <div class="card-body" style="padding: 10px 20px 10px 20px;">
        <div class="row" >
            <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0"><b>&nbsp; &nbsp;昵称: </b><?= $nick_name?></div>
        </div>
        <div class="row">
            <div class="area-title" style="font-size: 16px;margin: 6px 0 2px 0"><b>手机号: </b><?= $phone_num?></div>
        </div>
    </div>

    <div class="card-body" style="padding-bottom: 0;">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>小区名称</th>
                        <th>月份</th>
                        <th>当月累计次数</th>
                        <th>当月投递重量</th>
                        <th>当月收获环保金金额</th>
                        <th>当月小区排名</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($rankMonth as $val) {?>
                        <tr>
                            <td><?= $val['village_name']?></td>
                            <td><?= $val['month']?></td>
                            <td><?= $val['delivery_count']?></td>
                            <td><?= $val['delivery_weight']?></td>
                            <td><?= $val['delivery_income'] ?></td>
                            <td><?= $val['rank']?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
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

    function ifr_close(){
        Dialog.close();
    }



</script>