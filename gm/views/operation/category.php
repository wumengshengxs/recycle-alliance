<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">


    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>

    <title>小松鼠运营管理系统</title>
    <style>
        .butt{
            border:solid 1px #ced4da;cursor:pointer;
        }
        .butt:hover{
            background-color:#05aafe;
            color: #fff;
        }
        .row-input{
            margin: 20px 0px 0px 0px;
        }
        .row-button{
            line-height: 62px;
            margin: -20px;
            margin-top: 45px;
        }
    </style>
</head>
<body style="background-color:#fff;">
<div class="card" style="margin-bottom:0px">
    <div class="card-body">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>类型</th>
                        <th>今日</th>
                        <th>昨日</th>
                        <th>本周</th>
                        <th>本月</th>
                        <th>年度</th>
                        <th>累计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($category as $val) { ?>
                        <tr>
                            <td><img src="<?= $val['category_img_url']?>" alt="" style="height: 24px;width:  24px;">&nbsp; <?= $val['category_name']?>
                               <?php if ($val['id'] == 1) {?>
                                    (个)
                               <?php } else { ?>
                                   (kg)
                               <?php } ?>
                            </td>
                            <td><?= $val['day']?></td>
                            <td><?= $val['to_day']?></td>
                            <td><?= $val['week']?></td>
                            <td><?= $val['month']?></td>
                            <td><?= $val['year']?></td>
                            <td><?= $val['total']?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>

</script>
</body>
</html>