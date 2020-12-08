<?php

namespace bigdata\controllers;

use bigdata\models\SrUserDeliveryHistoryChildBrush;
use bigdata\models\SrUserDeliveryHistoryParentBrush;
use gm\models\PositionVillage;
use gm\models\SrRecyclingHistoryChild;
use gm\models\SrRecyclingHistoryParent;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrTrashCan;
use gm\models\SrUser;

class HomedataController extends BController
{
    /**
     * 各品类回收量
     */
    public function actionCategory_recycled()
    {
        $today_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->where(['like', 'delivery_time', date('Y-m-d')])
            ->groupBy(['delivery_type'])
            ->asArray()->all();
        $yesterday_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->where(['like', 'delivery_time', date('Y-m-d', time() - 24 * 3600)])
            ->groupBy(['delivery_type'])
            ->asArray()->all();
        $all_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->groupBy(['delivery_type'])
            ->asArray()->all();

        $number = date("w");  //当时是周几
        $number = $number == 0 ? 7 : $number; //如遇周末,将0换成7
        $diff_day = $number - 1; //求到周一差几天
        $week_day = date("Y-m-d", time() - ($diff_day * 60 * 60 * 24));

        $week_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->where(['>', 'delivery_time', $week_day])
            ->groupBy(['delivery_type'])
            ->asArray()->all();

        $month_day = date("Y-m-01");
        $month_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->where(['>', 'delivery_time', $month_day])
            ->groupBy(['delivery_type'])
            ->asArray()->all();

        $year_day = date("Y-01-01");
        $year_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num,delivery_type')
            ->where(['>', 'delivery_time', $year_day])
            ->groupBy(['delivery_type'])
            ->asArray()->all();

        $temp_today = $temp_yesterday = $temp_all = $temp_week = $temp_month = $temp_year = [];
        foreach ($today_list as $today) {
            $temp_today[$today['delivery_type']] = intval($today['num']) == $today['num'] ? intval($today['num']) : $today['num'];
        }
        foreach ($yesterday_list as $yesterday) {
            $temp_yesterday[$yesterday['delivery_type']] = intval($yesterday['num']) == $yesterday['num'] ? intval($yesterday['num']) : $yesterday['num'];
        }
        foreach ($all_list as $all) {
            $temp_all[$all['delivery_type']] = intval($all['num']) == $all['num'] ? intval($all['num']) : $all['num'];
        }
        foreach ($week_list as $week) {
            $temp_week[$week['delivery_type']] = intval($week['num']) == $week['num'] ? intval($week['num']) : $week['num'];
        }
        foreach ($month_list as $month) {
            $temp_month[$month['delivery_type']] = intval($month['num']) == $month['num'] ? intval($month['num']) : $month['num'];
        }
        foreach ($year_list as $year) {
            $temp_year[$year['delivery_type']] = intval($year['num']) == $year['num'] ? intval($year['num']) : $year['num'];
        }

        $big = [
            [
                'id' => 1,
                'name' => '饮料瓶',
            ], [
                'id' => 5,
                'name' => '纺织物',
            ], [
                'id' => 2,
                'name' => '纸类',
            ], [
                'id' => 6,
                'name' => '金属',
            ], [
                'id' => 4,
                'name' => '塑料',
            ], [
                'id' => 3,
                'name' => '书籍',
            ]
        ];
        $data = [];
        foreach ($big as $item) {
            $temp = [
                'TYPE' => $item['name'],
                'TOTAL' => '0',
                'DAY1' => '0',
                'DAY2' => '0',
                'WEEK' => '0',
                'MONTH' => '0',
                'YEAR' => '0'
            ];
            if (array_key_exists($item['id'], $temp_all)) {
                $temp['TOTAL'] = intval($temp_all[$item['id']]);
            }
            if (array_key_exists($item['id'], $temp_yesterday)) {
                $temp['DAY1'] = $temp_yesterday[$item['id']];
            }
            if (array_key_exists($item['id'], $temp_today)) {
                $temp['DAY2'] = $temp_today[$item['id']];
            }
            if (array_key_exists($item['id'], $temp_week)) {
                $temp['WEEK'] = $temp_week[$item['id']];
            }
            if (array_key_exists($item['id'], $temp_month)) {
                $temp['MONTH'] = $temp_month[$item['id']];
            }
            if (array_key_exists($item['id'], $temp_year)) {
                $temp['YEAR'] = $temp_year[$item['id']];
            }
            if ($item['id'] == 1) {
                $temp['TOTAL'] = $temp['TOTAL'] / 50;
                $temp['DAY1'] = $temp['DAY1'] / 50;
                $temp['DAY2'] = $temp['DAY2'] / 50;
                $temp['WEEK'] = $temp['WEEK'] / 50;
                $temp['MONTH'] = $temp['MONTH'] / 50;
                $temp['YEAR'] = $temp['YEAR'] / 50;
            }
            $data[] = $temp;
        }
        die(json_encode($data));
    }


    /**
     * 实时投递
     */

    public function actionReal_time_delivery()
    {

        //sr_user_delivery_history_child   sr_user   position_village  sr
        //选择是否刷量的表
        $activeQuery_list = SrUserDeliveryHistoryChildBrush::find()
            ->select('user_id,delivery_type,delivery_count,delivery_income,parent_id')
            ->limit(20)
            ->orderBy(['id' => SORT_DESC])
            ->asArray()->all();

        empty($activeQuery_list) || $user_ids = array_unique(array_filter(array_column($activeQuery_list, 'user_id')));
        $user_temp = [];
        $sum_temp = [];
        if (!empty($user_ids)) {
            $users = SrUser::find()
                ->select('id,phone_num')
                ->where(['in', 'id', $user_ids])
                ->asArray()->all();
            foreach ($users as $u) {
                $user_temp[$u['id']] = substr_replace($u['phone_num'], '****', 3, 4);
            }
            $sum = SrUserDeliveryHistoryChildBrush::find()
                ->select('SUM(delivery_count) as num,SUM(delivery_income) as amnt,user_id,delivery_type')
                ->where(['in', 'user_id', $user_ids])
                ->groupBy('user_id,delivery_type')
                ->asArray()->all();
            foreach ($sum as $s) {
                $sum_temp[$s['user_id'] . '/' . $s['delivery_type']] = $s;
            }

        }

        empty($activeQuery_list) || $parent_id = array_unique(array_filter(array_column($activeQuery_list, 'parent_id')));
        $parent = [];
        if (!empty($parent_id)) {
            $parent = SrUserDeliveryHistoryParentBrush::find()
                ->select('id,machine_id')
                ->where(['in', 'id', $parent_id])
                ->asArray()->all();
        }
        $area_mach = [];
        empty($parent) || $machine_ids = array_unique(array_filter(array_column($parent, 'machine_id')));
        if (!empty($machine_ids)) {
            $machine_list = SrRecyclingMachine::find()
                ->select('id, community_name')
                ->where(['in', 'id', $machine_ids])
                ->asArray()->all();
            $machine_temp = [];
            foreach ($machine_list as $mach) {
                $machine_temp[$mach['id']] = $mach['community_name'];
            }
            foreach ($parent as $p) {
                if (array_key_exists($p['machine_id'], $machine_temp)) {
                    $area_mach[$p['id']] = $machine_temp[$p['machine_id']];
                }
            }
        }

        empty($activeQuery_list) || $delivery_type = array_unique(array_filter(array_column($activeQuery_list, 'delivery_type')));
        $category_temp = [];
        if (!empty($delivery_type)) {

            $category = SrRubbishCategory::find()
                ->select('id,category_name')
                ->where(['in', 'id', $delivery_type])
                ->asArray()->all();
            foreach ($category as $c) {
                $category_temp[$c['id']] = $c['category_name'];
            }

        }

        //取出用户名 ，所在小区，分类信息，分类数量，本次价格
        $data = [];
        foreach ($activeQuery_list as $a) {
            $temp = [
                'USER' => '未知',
                'AREA' => '未知',
                'CATEGORY' => '未知',
                'NUMBER' => $a['delivery_count'],
                'AMNT' => $a['delivery_income'],
                'ALL_COUNT' => '0',
                'ALL_AMNT' => '0'
            ];
            if (array_key_exists($a['user_id'], $user_temp)) {
                $temp['USER'] = $user_temp[$a['user_id']];
            }
            if (array_key_exists($a['parent_id'], $area_mach)) {
                $temp['AREA'] = $area_mach[$a['parent_id']];
            }
            if (array_key_exists($a['delivery_type'], $category_temp)) {
                $temp['CATEGORY'] = $category_temp[$a['delivery_type']];
            }
            if (array_key_exists($a['user_id'] . '/' . $a['delivery_type'], $sum_temp)) {
                $temp['ALL_COUNT'] = $sum_temp[$a['user_id'] . '/' . $a['delivery_type']]['num'];
                $temp['ALL_AMNT'] = $sum_temp[$a['user_id'] . '/' . $a['delivery_type']]['amnt'];
            }
            $data[] = $temp;
        }
        die(json_encode($data));
    }

    /**
     * 总订单量
     */
    public function actionAll_order()
    {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }

    /**
     * 今日订单量
     */
    public function actionToday_order()
    {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->where(['>', 'delivery_time', date('Y-m-d')])
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }

    /**
     * 历史总回收量
     */
    public function actionAll_num()
    {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num'])
        ]];
        die(json_encode($data));
    }


    /**
     * 历史发放回收金
     */
    public function actionAll_amnt()
    {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_income`) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : $all['num']
        ]];
        die(json_encode($data));
    }


    /**
     * 7日投递次数排行榜
     */
    public function actionSeven_count_list()
    {
        $list = SrUserDeliveryHistoryChildBrush::find()
            ->select(["DATE_FORMAT(`delivery_time`,'%m-%d') as DAY", "SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as NUM"])
            ->where(['>', 'delivery_time', date('Y-m-d', strtotime('-6 day'))])
            ->groupBy('DAY')
            ->asArray()->all();
        die(json_encode($list));
    }


    /**
     * 已铺设回收机数量
     */
    public function actionMache_num()
    {

        $all = SrRecyclingMachine::find()
            ->select('count(1) as num')
            ->where(['del_flag' => 0])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : $all['num'] * 5
        ]];
        die(json_encode($data));


    }


    /**
     * 覆盖街镇
     */
    public function actionStreet_num()
    {
        $all = PositionVillage::find()
            ->groupBy('street_name')
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }


    /**
     * 覆盖小区数量
     */
    public function actionVillage_num()
    {
        $all = PositionVillage::find()
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }


    /**
     * 覆盖户数
     */
    public function actionHourse_num()
    {
        $all = PositionVillage::find()
            ->count();
        $data = [[
            'SHOW' => $all * 498 * 3.5
        ]];
        die(json_encode($data));
    }


    /**
     * 今日清运
     */
    public function actionToday_recycled()
    {
        $all = SrRecyclingHistoryChild::find()
            ->where(['>', 'create_date', date('Y-m-d')])
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }


    /**
     * 历史清运
     */
    public function actionAll_recycled()
    {
        $all = SrRecyclingHistoryChild::find()
            ->count();
        $data = [[
            'SHOW' => $all
        ]];
        die(json_encode($data));
    }

    /**
     * 回收机满仓监控
     */
    public function actionMache_can_full()
    {
        /**
         * 1 查询垃圾桶表中80%和满桶的数据 取出他们的回收机id ，满桶的名称
         */
        $all_trash = SrTrashCan::find()
            ->where(['>', 'can_full', '0'])
            ->asArray()->all();
        $machine_ids = [];
        empty($all_trash) || $machine_ids = array_unique(array_filter(array_column($all_trash, 'machine_id')));
        /**
         * 2 通过回收机ID集合查询回收机所在小区，街道信息
         */
        $recycling_machine = SrRecyclingMachine::find()
            ->where(['in', 'id', $machine_ids])
            ->asArray()->all();
        $machine_temp = [];

        foreach ($recycling_machine as $m) {
            $machine_temp[$m['id']] = $m; //array的索引就根据数据的id来设定

        }

        $data = [];
        foreach ($all_trash as $a) {
            $b = [];
            if (array_key_exists($a['machine_id'], $machine_temp)) {

                $b['CAN'] = $a['can_name'];
                $b['STREET'] = $machine_temp[$a['machine_id']]['street_name'];
                $b['VOLLAGE'] = $machine_temp[$a['machine_id']]['community_name'];
                $b['STATUS'] = $a['can_full'] == 1 ? '80%' : '满箱';
                $data[] = $b;

            }


        }
        die(json_encode($data));
    }


    /**
     * 回收机满仓监控
     */
    public function actionMache_can() {
        $all_trash = SrTrashCan::find()
            ->where('can_name IS NOT NULL')
            ->orderBy(['quantity' => SORT_DESC])
            ->limit(20)
            ->asArray()->all();
        $machine_ids = [];
        empty($all_trash) || $machine_ids = array_unique(array_filter(array_column($all_trash, 'machine_id')));
        $recycling_machine = SrRecyclingMachine::find()
            ->where(['in', 'id', $machine_ids])
            ->asArray()->all();
        $machine_temp = [];

        foreach ($recycling_machine as $m) {
            $machine_temp[$m['id']] = $m; //array的索引就根据数据的id来设定

        }

        $data = [];
        foreach ($all_trash as $a) {
            $b = [
                'ID' => '',
                'STREET' => '',
                'VOLLAGE' => '',
                'CAN' => '',
                'NUM' => '',
                'AMNT' => '',
                'STATUS' => '',
                'RONG' => ''
            ];
            if (array_key_exists($a['machine_id'], $machine_temp)) {
                $b['ID'] = $machine_temp[$a['machine_id']]['device_id'];
                $b['CAN'] = $a['can_name'];
                $b['NUM'] = $a['category'] == 1 ? $a['count'] : $a['weight'];
                $b['AMNT'] = $b['NUM'] * $a['sale_price'];
                $b['RONG'] = $a['quantity'];
                $b['STREET'] = $machine_temp[$a['machine_id']]['street_name'];
                $b['VOLLAGE'] = $machine_temp[$a['machine_id']]['community_name'];
                $b['STATUS'] = $a['can_full'] == 0 ? '未满箱' : ($a['can_full'] == 1 ? '80%' : '满箱');
            }
            $data[] = $b;
        }
        die(json_encode($data));
    }


    /**
     * 回收机满仓分类箱体数量列表
     */
    public function actionMache_can_category_list() {
        $all = SrTrashCan::find()
            ->select('count(1) as NUM,p.category_name as TYPE')
            ->alias('c')
            ->leftJoin(SrRubbishCategory::tableName() . ' p',
                'p.id=c.category')
            ->where('c.can_name IS NOT NULL AND can_full > 0')
            ->groupBy('c.category')
            ->asArray()->all();
        empty($all) && $all = [[]];
        die(json_encode($all));
    }


    /**
     * 今日类别清运重量列表
     */
    public function actionCategory_recycled_list()
    {
        $all = SrRecyclingHistoryChild::find()
            ->select('SUM(IF(`category` = 1,`recycling_amount`/50,`recycling_amount`)) as num,p.category_name')
            ->alias('c')
            ->leftJoin(SrRubbishCategory::tableName() . ' p',
                'p.id=c.category')
            ->where(['>', 'recycling_time', date('Y-m-d')])
            ->groupBy('category')
            ->asArray()->all();
        $data = [];
        foreach ($all as $item) {
            $b = [];
            $b['NUM'] = round($item['num'], 2);
            $b['CATEGORY'] = $item['category_name'];
            $data[] = $b;
        }
        die(json_encode($data));
    }


    /**
     * 实时清运重量列表
     */
    public function actionRecycled_list() {

        $all = SrRecyclingHistoryChild::find()
            ->select('m.street_name as STREET,m.community_name as VOLLAGE,g.category_name as CAN,c.recycling_amount as NUM,')
            ->alias('c')
            ->leftJoin(SrRecyclingHistoryParent::tableName() . ' p',
                'p.id=c.parent_id')
            ->leftJoin(SrRecyclingMachine::tableName() . ' m',
                'm.id=p.machine_id')
            ->leftJoin(SrRubbishCategory::tableName() . ' g',
                'c.category=g.id')
            ->limit(20)
            ->orderBy(['c.id' => SORT_DESC])
            ->asArray()->all();

        empty($all) && $all = [[]];

        die(json_encode($all));
    }

    /**
     * 各数据统计
     */
    public function actionStatistic()
    {
        $all_user = SrUser::find()
            ->select('count(1) as num')
            ->asArray()->one();
        $all_hsitory = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as number,SUM(`delivery_income`) as amnt')
            ->where(["<>", "delivery_type", 1])
            ->asArray()->one();
        $ping_hsitory = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as number,SUM(`delivery_income`) as amnt')
            ->where(["delivery_type" => 1])
            ->asArray()->one();
        empty($ping_hsitory['number']) && $ping_hsitory['number'] = 0;
        empty($ping_hsitory['amnt']) && $ping_hsitory['amnt'] = 0;

        $all_hsitory['number'] += empty($ping_hsitory['number']) ? 0 : $ping_hsitory['number'] / 50;
        $all_hsitory['amnt'] += $ping_hsitory['amnt'];
        $all_machin = SrRecyclingMachine::find()
            ->select('count(1) as num')
            ->asArray()->one();
        $all_village = PositionVillage::find()
            ->select('count(1) as num')
            ->asArray()->one();

        $data = [[
            'USER_NUMBER' => intval(empty($all_user['num']) ? '0' : $all_user['num'] . ''),
            'HISTORY_CATEGORY_NUMBER' => intval(empty($all_hsitory['number']) ? '0' : $all_hsitory['number'] . ''),
            'RECOVERY_MONEY' => intval(empty($all_hsitory['amnt']) ? '0.00' : $all_hsitory['amnt'] . ''),
            'RUNNING_MATCH' => intval(empty($all_machin['num']) ? '0' : $all_machin['num'] . ''),
            'RUNNING_CITY' => 1,
            'RUNNING_VILLAGE' => intval(empty($all_village['num']) ? '0' : $all_village['num'] . '')
        ]];

        die(json_encode($data));
    }

    /**
     * 区域统计排行
     */
    public function actionArea_num_list()
    {
        $data = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`c`.`delivery_type` = 1,`c`.`delivery_count`/50,`c`.`delivery_count`)) as AREA_NUM,m.community_name as AREA_NAME')
            ->alias('c')
            ->leftJoin(
                SrUserDeliveryHistoryParentBrush::tableName() . ' p',
                'p.id=c.parent_id'
            )
            ->leftJoin(
                SrRecyclingMachine::tableName() . ' m',
                'm.id=p.machine_id'
            )
            ->where("m.community_name <> '' AND m.community_name <> '0'")
            ->groupBy('m.community_name')
            ->orderBy(['AREA_NUM' => SORT_DESC])
            ->asArray()->all();
        foreach ($data as &$item) {
            $item['AREA_NUM'] = round($item['AREA_NUM'], 2);
        }
        die(json_encode($data));
    }
}