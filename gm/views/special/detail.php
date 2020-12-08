<div class="container-fluid">
    <div class="side-body">
        <div class="page-title">
            <span class="title"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">

                        <div class="card-title">
                            <div class="title">预约订单详情</div>
                        </div>
                    </div>
                    <div class="card-body padding-top">
                        <div class="row  no-margin-bottom">
                            <div class="col-md-6 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h3 id="thumbnail-label">订单信息</h3>
                                    </div>
                                    <div class="caption">
                                        <p>
                                        订单编号:<?=$order['order_id']?>
                                        </p>
                                        <p>
                                        下单状态:<?=(empty($order_status[$order['status']]) ? '未知' : $order_status[$order['status']])?>
                                        </p>
                                        <?php if ($order['status'] == 2) {
                                            $cancel = ["用户取消","后台取消"]; ?>
                                        <p>
                                        取消类型:<?=(empty($cancel[$order['cancellation_type']]) ? '未知' : $cancel[$order['cancellation_type']])?>
                                        </p>
                                        <p>
                                        取消原因:<?=(empty($order['cancel_info']) ? '无' : $order['cancel_info'])?>
                                        </p>
                                        <?php } ?>
                                        <p>
                                        下单时间:<?=$order['create_time']?>
                                        </p>
                                        <?php if ($order['time_out_status'] == 0) { ?>
                                        <p>
                                        是否超时:否
                                        </p>
                                        <p>
                                        超时时间:<span id="time"></span>
                                        </p>
                                        <?php } else { ?>
                                        <p>
                                            是否超时:<span style="color: red;">已超时</span>
                                        </p>
                                        <p>
                                            超时时间:<span id="time" style="color: red;">已超时<?=time_to_dhs($order['now_time_out'])?></span>
                                        </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h3 id="thumbnail-label">用户信息</h3>
                                    </div>
                                    <div class="caption">
                                        <p>
                                            客户姓名:<span style="color: #22A7F0;"><?=$order['user_name']?></span>
                                        </p>
                                        <p>
                                            小区名称:<?=$order['community_name']?>
                                        </p>
                                        <p>
                                            具体位置:<?=$order['user_door_number']?>
                                        </p>
                                        <p>
                                            联系电话:<span style="color: #22A7F0;"><?=$order['user_phone']?></span>
                                        </p>
                                        <p>
                                            预约时间:<?=$order['book_time']?>
                                        </p>
                                        <p>
                                            订单备注:<?=empty($order['mark']) ? "无" : $order['mark'];?>
                                        </p>
                                        <p> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row  no-margin-bottom">
                            <div class="col-md-6 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h3 id="thumbnail-label">回收员信息</h3>
                                    </div>
                                    <div class="caption">
                                        <p>
                                            上门回收员:<span style="color: #22A7F0;"><?=$recycler['nick_name']?></span>
                                        </p>
<!--                                        <p>-->
<!--                                            负责小区:-->
<!--                                        </p>-->
                                        <p>
                                            回收员联系方式:<span style="color: #22A7F0;"><?=$recycler['phone_num']?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h3 id="thumbnail-label">派单信息<?php if ($order['status'] < 2) { ?><button type="button" onclick="order_change('<?=$order['order_id']?>')" class="btn btn-success">派单</button><?php } ?></h3>
                                    </div>
                                    <table class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>派单时间</th>
                                            <th>派单类型</th>
                                            <th>派单员</th>
                                            <th>回收员</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($book_recycle as $br) { ?>
                                            <tr>
                                            <td><?=$br['create_time']?></td>
                                            <td><?=$br['type']?></td>
                                            <td><?=$br['admin_name']?></td>
                                            <td><?=$br['recycler_name']?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <?php if ($order['status'] == 3) { ?>
                        <div class="row  no-margin-bottom">
                            <div class="col-md-6 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h3 id="thumbnail-label">订单详情</h3>
                                    </div>
                                    <table class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>分类</th>
                                            <th>重量/数量</th>
                                            <th>单价</th>
                                            <th>金额</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($list as $l) { ?>
                                            <tr>
                                                <td><?=$l['cat_name']?></td>
                                                <td><?=$l['delivery_count']?></td>
                                                <td><?=(round($l['delivery_income']/$l['delivery_count'], 2))?></td>
                                                <td><?=$l['delivery_income']?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="caption">
                                        订单金额总计：<?=(empty($list) ? 0 : array_sum(array_column($list, 'delivery_income')))?> 元
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.min.js"></script>
<script>
    <?php if ($order['status'] < 2) { ?>
    refresh( <?=$order['book_limit_time']?> )
    <?php } ?>
    function refresh( end ) {
        var timestamp = (new Date()).getTime()
        var s = ((new Date(end)).getTime() - timestamp/1000).toFixed(0)
        var ss = s > 0 ? s : s*-1
        var show = dateSFormat(ss)
        var show_info = ''
        if (s > 0) {
            show_info = show + "后超时"
        } else {
            show_info = "已超时" + show
        }
        $('#time').html(show_info)
        setTimeout ('refresh( <?=strtotime($order['book_limit_time'])?> )',1000)
    }
    function dateSFormat( s ) {
        var day = Math.floor( s/ (24*3600) ); // Math.floor()向下取整
        var hour = Math.floor( (s - day*24*3600) / 3600);
        var minute = Math.floor( (s - day*24*3600 - hour*3600) /60 );
        var second = s - day*24*3600 - hour*3600 - minute*60;
        var show = '';
        if (day > 0) {
            show += day + '天';
        }
        if (hour > 0) {
            show += hour + "时";
        }
        if (minute > 0) {
            show += minute + "分";
        }
        show += second + "秒";
        return show;
    }

    function order_change(order_id) {
        show_detail("分配回收员", "/book/change?order_id=" + order_id)
    }
</script>