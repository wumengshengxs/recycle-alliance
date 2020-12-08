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

    <title>查看机器日志</title>
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


    </style>
</head>

<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-body">
        <?php foreach ($logs as $val) { ?>
            <a href="<?= $val['url']?>"  class="btn btn-lg btn-info" target="_blank"><?= $val['title']?></a>
        <?php } ?>
    </div>
</div>
</body>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>


