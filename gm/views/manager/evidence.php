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
<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-header">
        <h5><b>到账回执</b></h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="table-responsive col-12">
                <table id="dataTable" class="table table-striped table-bordered second">
                    <thead id="dataTitle">
                    <tr>
                        <th>联营方名称</th>
                        <th>联营方编号</th>
                        <th>汇款银行</th>
                        <th>汇款金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$agent['company_name']?></td>
                        <td><?=get_shot_guid($agent['uuid'])?></td>
                        <td><?=$srAgentTradeHistory['bank_name']?></td>
                        <td><?=$srAgentTradeHistory['amount']?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row row-input">
            <label>到账日期</label>
            <input type="text" id="" value="<?=date('Y-m-d')?>" readonly class="form-control col-12" />
        </div>
        <div class="row row-input">
            <label>银行流水号(选填)</label>
            <input type="text" id="" readonly="readonly" class="form-control col-12" />
        </div>

        <div class="row row-input">
            <a href="javascript:void(0)" onclick="show_img()">
                <img id="image" style="height:200px" src="<?=$srAgentTradeHistory['receipt_url']?>" />
            </a>
        </div>

        <div class="row row-input">
            <label>到账金额</label>
            <input type="text" id="amount" readonly="readonly" value="<?=$srAgentTradeHistory['amount']?>" class="form-control col-12" />
        </div>

        <div class="row row-button">
            <div id="close" class="col-12 text-center butt"><b>关闭</b></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    function show_img(){
        var src = $('#image').attr('src');
        window.parent.open(src);
    }
    $('#close').click(function(){
        window.parent.close_iframe();
    });
</script>
</body>
</html>