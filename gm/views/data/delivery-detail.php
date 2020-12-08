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
        <title>投递明细</title>
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
                        <th>明细ID</th>
                        <th>品类</th>
                        <th>数量</th>
                        <th>环保金</th>
                        <th>当前审核状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($detail as $val){?>
                        <tr>
                            <td><?= $val['id']?></td>
                            <td><?= $val['can_name']?></td>
                            <td><?= $val['delivery_count']?></td>
                            <td><?= $val['delivery_income']?></td>
                            <td><?= $val['status']?></td>
                            <?php if ($val['declarable_status'] == 0){?>
                            <td><a href="javascript:(0)" onclick="edit_delivery(<?= $val['id'] ?>)">修改</a></td>
                            <?php }else{?>
                                <td>已审核</td>
                            <?php }?>
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


    function edit_delivery(id)
    {
        Dialog({
            title: '修改投递信息',
            width: 500,
            iframeContent: {
                src: '/data/delivery-detail-edit?id='+id,
                height: 360
            },
            bodyScroll:false,
            showButton:false
        });

    }


</script>