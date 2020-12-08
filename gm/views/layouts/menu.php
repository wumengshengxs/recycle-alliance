<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">菜单</li>
                    <!--开始 —— 首页-->
                    <li class="nav-item">
                        <?php $active = ''; $aria_expanded = 'false'?>
                        <?php $this->context->id.'/'.$this->context->action->id == 'site/index' && ($active = 'active') && ($aria_expanded = 'true') ?>
                        <a class="nav-link <?=$active?>"  href="/" aria-expanded="<?=$aria_expanded?>"><i class="fa fa-fw fa-user-circle"></i>首页</a>
                    </li>
                    <!--结束 —— 首页-->

                    <!--开始 —— 联营方管理-->
                    <?php if(empty(yii::$app->user->identity->attributes['admin'])){?>
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'manager' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-5" aria-controls="submenu-5"><i class="mdi mdi-account-multiple"></i>联营方管理</a>
                        <div id="submenu-5" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'manager/list' ? print($active) : ''?>" href="/manager/list">账号管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'manager/recharge_list' ? print($active) : ''?>" href="/manager/recharge_list">环保金审核</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'manager/finance' ? print($active) : ''?>" href="/manager/finance">财务管理</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php }?>
                    <!--结束 —— 联营方管理-->

                    <!--开始 —— 设备管理-->
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'machine' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-7" aria-controls="submenu-7"><i class="mdi mdi-account-multiple"></i>设备管理</a>
                        <div id="submenu-7" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'machine/list' ? print($active) : ''?>" href="/machine/list">设备管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'machine/maintain-list' ? print($active) : ''?>" href="/machine/maintain-list">设备维修管理</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!--结束 —— 设备管理-->

                    <!--开始 —— 运营管理-->
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'operation' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-1" aria-controls="submenu-5"><i class="fa fa-fw fa-bars"></i>运营管理</a>
                        <div id="submenu-1" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'operation/delivery' ? print($active) : ''?>" href="/operation/delivery">投递记录</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'operation/recycler' ? print($active) : ''?>" href="/operation/recycler">清运记录</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'operation/category' ? print($active) : ''?>" href="/operation/category">品类统计</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!--结束 —— 清运报表-->

                    <!--开始 —— 账务管理-->
                    <li class="nav-item">
                        <?php $active = ''; $aria_expanded = 'false'?>
                        <?php $this->context->id.'/'.$this->context->action->id == 'finance/list' && ($active = 'active') && ($aria_expanded = 'true') ?>
                        <a class="nav-link <?=$active?>"  href="/finance/list" aria-expanded="<?=$aria_expanded?>"><i class="fa fa-fw fa-yen-sign"></i>账务管理</a>
                    </li>
                    <!--结束 —— 财务管理-->

                    <!--开始 —— 数据统计管理-->
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'data' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-12" aria-controls="submenu-12"><i class="mdi mdi-account-multiple"></i>数据统计管理(测试中)</a>
                        <div id="submenu-12" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/delivery' ? print($active) : ''?>" href="/data/delivery">用户投递列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/user' ? print($active) : ''?>" href="/data/user">注册用户列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/recycle' ? print($active) : ''?>" href="/data/recycle">设备清运列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/people' ? print($active) : ''?>" href="/data/people">注册投递人次列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/delivery-user' ? print($active) : ''?>" href="/data/delivery-user">用户投递明细列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/village-rank' ? print($active) : ''?>" href="/data/village-rank">每月小区排名列表</a>
                                </li>
                                <?php if (Yii::$app->user->identity->admin == 0) {?>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/user-statistics' ? print($active) : ''?>" href="/data/user-statistics">环保金统计列表</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/user-order-withdraw' ? print($active) : ''?>" href="/data/user-order-withdraw">用户提现列表</a>
                                </li>
                                <?php }?>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'data/delivery-count' ? print($active) : ''?>" href="/data/delivery-count">投递数据统计列表</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <!--end —— 数据统计管理-->

                    <!--开始 —— 广告管理-->
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'ad' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-2" aria-controls="submenu-2"><i class="mdi mdi-account-multiple"></i>广告管理</a>
                        <div id="submenu-2" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'ad/ad_space' ? print($active) : ''?>" href="/ad/ad_space">广告位设置</a>
                                </li>
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link --><?php //$uri == 'ad/ad_space' ? print($active) : ''?><!--" href="/ad/ad_space">广告设置</a>-->
<!--                                </li>-->
                            </ul>
                        </div>
                    </li>
                    <!--结束 —— 广告管理-->

                    <!--<a class="nav-link collapsed" href=" " data-toggle="collapse" aria-expanded="<?/*=$aria_expanded*/?>" data-target="#submenu-7" aria-controls="submenu-7">
                    <i class="mdi mdi-account-multiple"></i>设备管理</ a>
                    <div id="submenu-7" class="submenu collapse <?/*=$show*/?>" style="">-->
                    <!--开始 —— 回收商-->

                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php $this->context->id == 'external' && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-11" aria-controls="submenu-11"><i class="mdi mdi-account-multiple"></i>回收商管理</a>
                        <div id="submenu-11" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'external/external-recycler-index' ? print($active) : ''?>" href="/external/external-recycler-index">回收商管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'external/recycler-history' ? print($active) : ''?>" href="/external/recycler-history">充值管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'external/recycler-staff-index' ? print($active) : ''?>" href="/external/recycler-staff-index">回收员管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'external/recycler-order-amount' ? print($active) : ''?>" href="/external/recycler-order-amount">收货量统计</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'external/recycler-order-pay' ? print($active) : ''?>" href="/external/recycler-order-pay">收货金额统计</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!--end —— 回收商-->

                    <!--开始 —— 设置-->
                    <li class="nav-item">
                        <?php $show = ''; $active = 'active'; $aria_expanded = 'false'?>
                        <?php ($this->context->id == 'village' || $this->context->id == 'recycler' || $this->context->id == 'maintain' || $this->context->id == 'log') && ($show = 'show') && ($aria_expanded = 'true') ?>
                        <?php $uri = $this->context->id.'/'.$this->context->action->id ?>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="<?=$aria_expanded?>" data-target="#submenu-6" aria-controls="submenu-6"><i class="mdi mdi-account-multiple"></i>系统设置</a>
                        <div id="submenu-6" class="submenu collapse <?=$show?>" style="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'village/list' ? print($active) : ''?>" href="/village/list">小区管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'recycler/recycler-list' ? print($active) : ''?>" href="/recycler/recycler-list">清运管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'maintain/list' ? print($active) : ''?>" href="/maintain/list">维修员管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php $uri == 'log/index' ? print($active) : ''?>" href="/log/index">操作日志</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!--结束 —— 设置-->
                </ul>
            </div>
        </nav>
    </div>
</div>
