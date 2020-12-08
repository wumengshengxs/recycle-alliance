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
                            <div class="title">两次箱体开门产生订单审核</div>
                        </div>
                    </div>
                    <div class="card-body padding-top">
                        <div class="row  no-margin-bottom">
                            <div class="col-md-12 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <table class="table table-striped" cellspacing="0" width="100%">
                                            <tbody>
                                            <tr>
                                                <th><strong>箱体信息</strong></th>
                                                <th>小区：<?=$community['name']?></th>
                                                <th>地址：<?=$community['location']?></th>
                                                <th>机器编号：<?=$community['device_id']?></th>
                                                <th>箱体名称：<?=$community['can_name']?></th>
                                            </tr>
                                            <tr>
                                                <th><strong>上次清理信息</strong></th>
                                                <th>开箱时间：<?=$last['open_time']?></th>
                                                <th>回收员姓名：<?=$last['recycle_name']?></th>
                                                <th>回收员手机：<?=$last['recycle_phone']?></th>
                                            </tr>
                                            <tr>
                                                <th><strong>本次清理信息</strong></th>
                                                <th>开箱时间：<?=$current['open_time']?></th>
                                                <th>回收员姓名：<?=$current['recycle_name']?></th>
                                                <th>回收员手机：<?=$current['recycle_phone']?></th>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body padding-top">
                        <div class="row  no-margin-bottom">
                            <div class="col-md-12 col-sm-12">
                                <div class="thumbnail no-margin-bottom">
                                    <div class="caption">
                                        <h5 id="thumbnail-label">总投递 <?=count($delivery_list)?>条 | 恶意投递 <?=$ey?>条 | 订单无异常 <?=$wyc?>条 | 误操作 <?=$wcz?>条<?php if ($current['status'] == 0) { ?><button style="margin-left: 100px;" type="button" onclick="no_special()" class="btn btn-success">批量订单无异常</button><?php } ?></h5>
                                    </div>
                                    <div class="caption">
                                        <table class="table table-striped" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <?php if ($current['status'] == 0) { ?>
                                                <th><button type="button" onclick="swapCheck()" class="btn btn-success">全选</button></th>
                                                <th>操作</th>
                                                <?php } ?>
                                                <th>核实结果</th>
                                                <th>审核时间</th>
                                                <th>核实方式</th>
                                                <th>核实图片</th>
                                                <th>审核备注</th>
                                                <th>用户手机</th>
                                                <th>投递时间</th>
                                                <th>环保金</th>
                                                <th>重量/数量</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($delivery_list as $de) { ?>
                                                <tr>
                                                    <?php if ($current['status'] == 0) { ?>
                                                    <td>
                                                        <?php if ($de['declarable_status'] == 0) { ?>
                                                        <div class="ckbox">
                                                            <input name="choose" type="checkbox" value="<?=$de['id']?>">
                                                        </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($de['declarable_status'] == 0) { ?>
                                                            <button type="button" onclick="verfy(<?=$de['id']?>, <?=$id?>)" class="btn btn-success">添加审核</button>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td><?=$de['status_info']?></td>
                                                    <td><?=($de['declarable_status'] == 0 ? '无' : $de['update_date'])?></td>
                                                    <td><?=$de['check_type']?></td>
                                                    <td><?=(empty($de['check_imgs']) ? 0 : '<a href="javascript:(0)" onclick="show_image(\''.$de['id'].'\')"><span style="color: green">' . count(explode(';', $de['check_imgs'])) . '</span></a>')?></td>
                                                    <td><div style="width:200px;"><?=$de['check_info']?></div></td>
                                                    <td><?=$de['phone']?></td>
                                                    <td><?=$de['create_date']?></td>
                                                    <td><?=$de['delivery_income']?></td>
                                                    <td><?=$de['delivery_count']?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

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

<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script type="text/javascript" src="/lib/js/jquery.min.js"></script>
<script>
    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[type='checkbox']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[type='checkbox']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }


    function verfy(id, special_id) {
        show_detail("提交审核结果", "/special/can_verfy?id=" + id + "&special_id=" + special_id)
    }

    function show_image(id) {
        show_detail("图片查看", "/special/user_show_image?id=" + id)
    }

    function no_special() {
        var select = "";
        $("input[name='choose']:checked").each(function (index, item) {
            if ($("input[name='choose']:checked").length-1==index) {
                select += $(this).val();
            } else {
                select += $(this).val() + ",";
            }
        });
        if (select == "") {
            alert("请选择需要无异常的投递记录")
            return
        }
        var data = {
            select: select,
            "_csrf-gm": "<?=Yii::$app->request->csrfToken?>"
        }
        $.ajax({
            url:'/specialoption/delivery_no_special',
            type:'post',
            data:data,
            dataType:'json',
            success:function(res){
                window.parent.location.reload();
                alert(res.msg)
            }
        });
    }

    function daeSFormat( s ) {
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