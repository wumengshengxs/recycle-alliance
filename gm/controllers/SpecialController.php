<?php
namespace gm\controllers;

use gm\models\SrRecyclingSpecialOrderChild;
use gm\models\SrUserDeliverySpecialChild;
use gm\models\PositionVillage;
use gm\models\SrRecycler;
use gm\models\SrRecyclingHistoryChild;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrUser;
use gm\models\SrUserBookOrder;
use gm\models\SrUserBookOrderChild;
use gm\models\SrUserBookRecycler;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SysArea;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SpecialController extends GController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionCan_list()
    {
        //获取所有回收员信息
        $recycls = SrRecycler::find()
            ->select('id, nick_name')
            ->asArray()->all();
        $recycls = array_column($recycls, 'nick_name', 'id');
        return $this->render('index', ['recycles' => $recycls]);
    }

    /**
     * 抓取异常列表数据
     */
    public function actionAjax_can_list()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //排序
        $order_by = yii::$app->request->get('order_by');
        empty($order_by) && $order_by = 'id';
        $order_by = [$order_by => SORT_DESC];

        //小区名称匹配
        $name = yii::$app->request->get('name');
        $like_where = "";
        if (!empty($name)) {
            $list = SrRecyclingMachine::find()
                ->select('id')
                ->where(['like', 'community_name', $name])
                ->asArray()->all();
            $ids = [];
            empty($list) || $ids = array_column($list, 'id');
            empty($ids) || $like_where = "(`machine_id` in (" . implode(',', $ids) . "))";
        }

        //回收员筛选
        $recycler_id = yii::$app->request->get('recycler_id');
        empty($recycler_id) || $like_where .= (empty($like_where) ? " " : " AND ") . "(`recycler_id` = {$recycler_id})";

        //超时状态
        $timeout_status = yii::$app->request->get('timeout_status');
        empty($timeout_status) || $like_where .= (empty($like_where) ? " " : " AND ") . "(TIMESTAMPDIFF(HOUR, `create_date`, if(`status` = 0, now(), `update_date`)) " . ($timeout_status == 1 ? ">" : "<=") . " 48)";

        //订单状态
        $order_status = yii::$app->request->get('order_status');
        empty($order_status) || $like_where .= (empty($like_where) ? " " : " AND ") . "(`status` " . ($order_status == 1 ? "= 0" : "IN (1,2)") . ")";

        //获取异常列表
        $special_list = SrRecyclingSpecialOrderChild::find()
            ->select("TIMESTAMPDIFF(MINUTE, `create_date`, if(`status` = 0, now(), `update_date`)) as 'now_time_out', id, status, create_date, machine_id, cun_name, recycler_id, special_type, special_imgs, special_info, weight_or_count, recycling_child_id")
            ->where($like_where)
            ->andWhere('status <> 3')
            ->orderBy($order_by)
            ->offset($start)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrRecyclingSpecialOrderChild::find()
            ->where($like_where)
            ->andWhere('status <> 3')
            ->count();

        $child_ids = [];
        empty($special_list) || $child_ids = array_column($special_list, 'recycling_child_id');
        $delivery_count = $e_special_count = $w_special_count = [];
        if (!empty($child_ids)) {
            $delivery_list = SrUserDeliveryHistoryChild::find()
                ->select('count(1) as num, recycle_child_id')
                ->where(['in', 'recycle_child_id', $child_ids])
                ->groupBy('recycle_child_id')
                ->asArray()->all();
            empty($delivery_list) || $delivery_count = array_column($delivery_list, 'num', 'recycle_child_id');
            $delivery_special = SrUserDeliverySpecialChild::find()
//                ->select('count(1) as num, recycling_child_id')
                ->where(['in', 'recycling_child_id', $child_ids])
//                ->groupBy('recycling_child_id')
                ->asArray()->all();
            foreach ($delivery_special as $d) {
                if ($d['check_result'] == "恶意投递") {
                    empty($e_special_count[$d['recycling_child_id']]) && $e_special_count[$d['recycling_child_id']] = 0;
                    $e_special_count[$d['recycling_child_id']] ++;
                }
                if ($d['check_result'] == "误操作") {
                    empty($w_special_count[$d['recycling_child_id']]) && $w_special_count[$d['recycling_child_id']] = 0;
                    $w_special_count[$d['recycling_child_id']] ++;
                }
            }
            empty($delivery_special) || $special_count = array_column($delivery_special, 'num', 'recycling_child_id');
        }

        //获取机器信息
        $machine_ids = [];
        $machine_temp = [];
        empty($special_list) || $machine_ids = array_column($special_list, 'machine_id');
        if (!empty($machine_ids)) {
            $machine_list = SrRecyclingMachine::find()
                ->select('id, device_id, community_name')
                ->where(['in', 'id', $machine_ids])
                ->asArray()->all();
            foreach ($machine_list as $m) {
                $machine_temp[$m['id']] = $m;
            }
        }

        //获取所有回收员信息
        $recycls = SrRecycler::find()
            ->select('id, nick_name')
            ->asArray()->all();
        $recycls = array_column($recycls, 'nick_name', 'id');

        $status = ['待处理', '无异常', '有异常'];
        $data = [];
        foreach ($special_list as $special) {
            $temp = [];
            $temp['option'] = '<button type="button" onclick="order_detail(\''.$special['id'].'\')" class="btn ' . ($special['status'] == 0 ? "btn-success" : "btn-default") . '">' . ($special['status'] == 0 ? "审核" : "详情") . '</button>';
            $time = $special['now_time_out'] - 48*60;
            $str_time = time_to_dhs(($time > 0 ? $time : $time*-1));
            $temp['now_time_out'] = '已处理';
            if ($time > 0) {
                $temp['now_time_out'] = '<span style="color:red;">超时' . $str_time . '</span>';
            } else {
                $temp['now_time_out'] = $special['status'] == 0 ? $str_time . '后超时' : '已处理';
            }

//            $temp['now_time_out'] = $special['now_time_out'];
            $temp['id'] = $special['id'];
            $temp['status_info'] = '未知';
            $temp['special_deliver_num'] = 0;
            $temp['wu_deliver_num'] = 0;
            if (array_key_exists($special['status'], $status)) {
                $temp['status_info'] = $status[$special['status']];
            }
            $temp['create_date'] = $special['create_date'];
//            $temp['device_id'] = '';
            $temp['community_name'] = '';
            if (array_key_exists($special['machine_id'], $machine_temp)) {
//                $temp['device_id'] = $machine_temp[$special['machine_id']]['device_id'];
                $temp['community_name'] = $machine_temp[$special['machine_id']]['community_name'];
            }
            $temp['cun_name'] = $special['cun_name'];
            $temp['recycler_name'] = '';
            if (array_key_exists($special['recycler_id'], $recycls)) {
                $temp['recycler_name'] = $recycls[$special['recycler_id']];
            }
            //special_type, special_imgs, special_info, weight_or_count
            $temp['special_type'] = $special['special_type'];
            $temp['special_imgs_num'] = empty($special['special_imgs']) ? 0 : '<a href="javascript:(0)" onclick="show_image(\''.$special['id'].'\')">' . count(explode(';', $special['special_imgs'])) . '</a>';
            $temp['special_info'] = $special['special_info'];
            $temp['weight_or_count'] = $special['weight_or_count'];
            $temp['deliver_num'] = 0;
            if (array_key_exists($special['recycling_child_id'], $delivery_count)) {
                $temp['deliver_num'] = $delivery_count[$special['recycling_child_id']];
            }
            if (array_key_exists($special['recycling_child_id'], $e_special_count)) {
                $temp['special_deliver_num'] = $e_special_count[$special['recycling_child_id']];
            }
            if (array_key_exists($special['recycling_child_id'], $w_special_count)) {
                $temp['wu_deliver_num'] = $w_special_count[$special['recycling_child_id']];
            }
            if ($special['status'] == 0) {
                $temp['special_deliver_num'] = "未处理";
            }
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));
    }

    public function actionCan_detail() {
        $id = Yii::$app->request->get('id');
        $special = SrRecyclingSpecialOrderChild::find()
            ->select('*')
            ->where(['id' => $id])
            ->asArray()->one();
        $recycling = SrRecyclingHistoryChild::find()
            ->select('*')
            ->where(['id' => $special['recycling_child_id']])
            ->asArray()->one();
        $mache_info = SrRecyclingMachine::find()
            ->select('id, community_name, location, device_id')
            ->where(['id' => $special['machine_id']])
            ->asArray()->one();
        $delivery_list = SrUserDeliveryHistoryChild::find()
            ->where(['recycle_child_id' => $special['recycling_child_id']])
            ->asArray()->all();
        $last_recycling = SrRecyclingHistoryChild::find()
            ->where(['<', 'id', $special['recycling_child_id']])
            ->andWhere([
                'machine_id' => $recycling['machine_id'],
                'category' => $recycling['category'],
                'can_num' => $recycling['can_num']
            ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->asArray()->one();
        $last_recycle = [];
        if (!empty($last_recycling)) {
            $last_recycle = SrRecycler::find()
                ->select('*')
                ->where(['id' => $last_recycling['recycler_id']])
                ->asArray()->one();
        }
        $current_recycle = [];
        if (!empty($recycling)) {
            $current_recycle = SrRecycler::find()
                ->select('*')
                ->where(['id' => $recycling['recycler_id']])
                ->asArray()->one();
        }
        $user_special_list = SrUserDeliverySpecialChild::find()
            ->where(['recycling_child_id' => $special['recycling_child_id']])
            ->asArray()->all();
        $user_special_temp = [];
        foreach ($user_special_list as $item) {
            $user_special_temp[$item['user_child_id']] = $item;
        }
        $user_ids = [];
        empty($delivery_list) || $user_ids = array_unique(array_column($delivery_list, 'user_id'));
        $user_temp = [];
        if (!empty($user_ids)) {
            $user_list = SrUser::find()
                ->select('id, phone_num')
                ->where(['in', 'id', $user_ids])
                ->asArray()->all();
            empty($user_list) || $user_temp = array_column($user_list, 'phone_num', 'id');
        }

        $eyi_count = $wcz_count = $wyc_count = $wsh = 0;
        //0:待审核，1:自动无异常，2:手动无异常，3:异常
        $status_info = ['待审核', '自动无异常', '手动无异常', '异常'];
        foreach ($delivery_list as &$delivery) {
            $delivery['status_info'] = empty($status_info[$delivery['declarable_status']]) ? '' : $status_info[$delivery['declarable_status']];
            $delivery['check_type'] = '无';
            $delivery['check_info'] = '无';
            if (array_key_exists($delivery['id'], $user_special_temp)) {
                $delivery['status_info'] = $user_special_temp[$delivery['id']]['check_result'];
                if ($delivery['status_info'] == "恶意投递") {
                    $eyi_count ++;
                }
                if ($delivery['status_info'] == "误操作") {
                    $wcz_count ++;
                }
                $delivery['check_type'] = $user_special_temp[$delivery['id']]['check_type'];
                $delivery['check_info'] = $user_special_temp[$delivery['id']]['check_info'];
                $delivery['check_imgs'] = $user_special_temp[$delivery['id']]['check_imgs'];
            }
            $delivery['phone'] = '未知';
            if (array_key_exists($delivery['user_id'], $user_temp)) {
                $delivery['phone'] = $user_temp[$delivery['user_id']];
            }
            if (in_array($delivery['declarable_status'], [1,2])) {
                $wyc_count ++;
            }
            if ($delivery['declarable_status'] == 0) {
                $wsh ++;
            }
        }
        if ($wsh == 0 && $special['status'] == 0) {
            $u_status = (($eyi_count + $wcz_count > 0) ? 2 : 1);
            $count = SrRecyclingSpecialOrderChild::updateAll(['status' => $u_status, 'update_date' => date('Y-m-d H:i:s')], ['id' => $id]);
            $count > 0 && $special['status'] = $u_status;
        }
        $recycling['can_name'] = $this->show_can_name($recycling['can_name']);
        $data = [
            'community' => [
                'name' => empty($mache_info['community_name']) ? '未知' : $mache_info['community_name'],
                'location' => empty($mache_info['location']) ? '未知' : $mache_info['location'],
                'device_id' => empty($mache_info['device_id']) ? '未知' : $mache_info['device_id'],
                'can_name' => $recycling['can_name']
            ],
            'last' => [
                'open_time' => empty($last_recycling['recycling_time']) ? '未知' : $last_recycling['recycling_time'],
                'recycle_name' => empty($last_recycle['nick_name']) ? '未知' : $last_recycle['nick_name'],
                'recycle_phone' => empty($last_recycle['phone_num']) ? '未知' : $last_recycle['phone_num']
            ],
            'delivery_list' => $delivery_list,
            'ey' => $eyi_count,
            'wcz' => $wcz_count,
            'wyc' => $wyc_count,
            'current' => [
                'open_time' => $recycling['recycling_time'],
                'recycle_name' => empty($current_recycle['nick_name']) ? '未知' : $current_recycle['nick_name'],
                'recycle_phone' => empty($current_recycle['phone_num']) ? '未知' : $current_recycle['phone_num'],
                'status' => $special['status']
            ],
            'id' => $id
        ];
        return $this->render('can_detail', $data);
    }

    public function actionCan_verfy()
    {
        $id = Yii::$app->request->get('id');
        $special_id = Yii::$app->request->get('special_id');
        return $this->renderAjax('can_verfy', ['id' => $id, 'special_id' => $special_id]);
    }

    public function actionShow_image()
    {
        $id = Yii::$app->request->get('id');
        $img = SrRecyclingSpecialOrderChild::find()
            ->select('special_imgs')
            ->where(['id' => $id])
            ->asArray()->one();
        $data['imgs'] = explode(';', $img['special_imgs']);
        return $this->renderAjax('show_image', $data);
    }

    public function actionUser_show_image()
    {
        $id = Yii::$app->request->get('id');
        $img = SrUserDeliverySpecialChild::find()
            ->select('check_imgs')
            ->where(['user_child_id' => $id])
            ->asArray()->one();
        $data['imgs'] = explode(';', $img['check_imgs']);
        return $this->renderAjax('show_image', $data);
    }

    public function actionVillage_add()
    {
        $area_list = SysArea::find()
            ->select('name, code')
            ->where(['type' => 3])
            ->asArray()->all();
        return $this->renderAjax('village_add', ['area_list' => $area_list]);
    }

    public function actionVillage_edit()
    {
        $id = Yii::$app->request->get('id');
        $village = PositionVillage::find()
            ->select('*')
            ->where(['p_id' => $id])
            ->asArray()->one();
        $area_list = SysArea::find()
            ->select('name, code')
            ->where(['type' => 3])
            ->asArray()->all();
        return $this->renderAjax('village_edit', [
            'area_list' => $area_list,
            'village' => $village
        ]);
    }

    public function actionAjax_detail()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

//        $recycl_id = yii::$app->request->get('recycler');
        $order_id = yii::$app->request->get('order_id');
        $order_by = ['id' => SORT_DESC];
        var_dump($order_id);
        $book_list = SrUserBookRecycler::find()
            ->where(['book_order_id' => $order_id])
            ->orderBy($order_by)
            ->offset($start)
            ->limit($length)->asArray()->all();
        //0待分配 1待上门  2取消 3完成
        $data = [];
        foreach ($book_list as $book) {
            $temp = [];
            $temp['create_time'] = $book['create_time'];
            $temp['type'] = $book['type'] ? "手动" : "自动";
            $temp['admin_name'] = $book['admin_name'];
            $temp['recycler_name'] = $book['recycler_name'];
            array_push($data, array_values($temp));
        }

        //获取记录总数
        $total = SrUserBookRecycler::find()
            ->where(['book_order_id' => $order_id])
            ->count();

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));
    }

    public function actionAbc() {
        die(json_encode([
            [
                "COL1" => "1",
                "COL2" => 333,
                "COL3" => "上海"
            ],[
                "COL1" => "1",
                "COL2" => 333,
                "COL3" =>"上海"
            ]]));
    }
}