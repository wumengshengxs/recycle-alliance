<?php
namespace gm\controllers;

use gm\models\SrRecycler;
use gm\models\SrRecyclingMachine;
use gm\models\SrTrashCan;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserDeliveryHistoryParent;
use gm\models\SrUserUsageStatistics;
use gm\models\SrUserVillageRankHistory;
use gm\models\SrUserVillageRankMonth;
use gm\models\SrUserVillageRankWeek;
use Yii;
use yii\filters\AccessControl;

//******************************//
//*  数据控制器编辑或新增操作  *//
//******************************//
class DataoptionController extends GController {
    /**
     * AccessControl
     * 访问权限控制
     */
    public function behaviors(){
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
            ],
        ];
    }

    /**
     * 投递品类补量操作
     * sr_user_delivery_history_child 查询 + 更新
     * sr_user_delivery_history_parent 查询 + 更新
     * sr_user_income_history 新增
     * sr_user_usage_statistics 更新
     * sr_trash_can 查询
     */
    public function actionBoost(){
        //获取投递明细id与变更后的重量数据
        $id = Yii::$app->request->post('id');
        $delivery_count = Yii::$app->request->post('delivery_count');

        //根据明细id查询明细记录
        $srUserDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->where(['id' => $id])->one();

        //根据parent_id查询投递记录
        $srUserDeliveryHistoryParent = SrUserDeliveryHistoryParent::find()
            ->where(['id' => $srUserDeliveryHistoryChild['parent_id']])->one();

        //查找所属箱体的品类单价
        $srTrashCan = SrTrashCan::find()
            ->select('recycle_price')
            ->where(['category' => $srUserDeliveryHistoryChild['delivery_type'], 'machine_id' => $srUserDeliveryHistoryParent['machine_id']])
            ->asArray()->one();

        //查找所属用户的账户
        $srUserUsageStatistics = SrUserUsageStatistics::find()
            ->where(['user_id' => $srUserDeliveryHistoryChild['user_id']])->one();

        //计算补量后的数量差值
        $delivery_count_diff = $delivery_count - $srUserDeliveryHistoryChild['delivery_count'];

        //计算补量后的环保金差值
        $income_diff = round($delivery_count_diff * $srTrashCan['recycle_price'], 2);

        //更新sr_user_delivery_history_child数据
        $srUserDeliveryHistoryChild->setAttributes([
            'delivery_count' => $delivery_count,
            'delivery_income' => $srUserDeliveryHistoryChild['delivery_income'] + $income_diff
        ]);

        //更新sr_user_delivery_history_parent数据
        $srUserDeliveryHistoryParent->setAttributes([
            'income_amount' => $srUserDeliveryHistoryParent['income_amount'] + $income_diff
        ]);

        //更新sr_user_usage_statistics数据
        $srUserUsageStatistics->setAttributes([
            'cumulative_income' => $srUserUsageStatistics['cumulative_income'] + $income_diff,
            'current_env_amount' => $srUserUsageStatistics['current_env_amount'] + $income_diff,
        ]);
        $transaction = yii::$app->db->beginTransaction();
        $bool = $srUserDeliveryHistoryChild->save() && $srUserDeliveryHistoryParent->save() && $srUserUsageStatistics->save();
        if (!$bool) {
            $transaction->rollBack();
            die(json_encode(['res' => false]));
        }
        if ($srUserDeliveryHistoryChild->has_rank == 0) {
            $transaction->commit();
            die(json_encode(['res' => true]));
        }

        //查询该用户的当月小区的投递信息
        $machine_id = $srUserDeliveryHistoryChild->machine_id;
        if (empty($machine_id)) {
            $machine_id = $srUserDeliveryHistoryParent->machine_id;
        }
        if (empty($machine_id)) {
            $transaction->rollBack();
            die(json_encode(['res' => false]));
        }
        $machine = SrRecyclingMachine::find()
            ->where(['id' => $machine_id])
            ->asArray()->one();
        if (empty($machine) || empty($machine['position_village_id'])) {
            $transaction->rollBack();
            die(json_encode(['res' => false]));
        }
        $time = strtotime($srUserDeliveryHistoryChild->delivery_time);
        $day = date('Y-m-d', $time);
        $month = date('Y-m', $time);
        $week = date('Y-W', $time);

        //获取是否有记录
        $rank_history = SrUserVillageRankHistory::find()
            ->where(['close_date' => $day, 'user_id' => $srUserDeliveryHistoryChild->user_id, 'village_id' => $machine['position_village_id']])
            ->one();
        $rank_month = SrUserVillageRankMonth::find()
            ->where(['month' => $month, 'user_id' => $srUserDeliveryHistoryChild->user_id, 'village_id' => $machine['position_village_id']])
            ->one();
        $rank_week = SrUserVillageRankWeek::find()
            ->where(['week' => $week, 'user_id' => $srUserDeliveryHistoryChild->user_id, 'village_id' => $machine['position_village_id']])
            ->one();
        $income = bcdiv($income_diff, 2, 2);
        if (!empty($rank_history)) {
            $rank_history->setAttributes([
                'day_delivery_count' => $rank_history->day_delivery_count + 1,
                'day_delivery_income' => bcadd($rank_history->day_delivery_income, $income, 2)
            ]);
        } else {
            $rank_history = new SrUserVillageRankHistory();
            $rank_history->setAttributes([
                'user_id' => $srUserDeliveryHistoryChild->user_id,
                'village_id' => $machine['position_village_id'],
                'day_delivery_count' => 1,
                'day_delivery_income' => $income,
                'close_date' => $day
            ]);
        }
        if (!empty($rank_month)) {
            $rank_month->setAttributes([
                'delivery_count' => $rank_month->delivery_count + 1,
                'delivery_income' => bcadd($rank_month->delivery_income, $income, 2)
            ]);
        } else {
            $rank_month = new SrUserVillageRankMonth();
            $rank_month->setAttributes([
                'user_id' => $srUserDeliveryHistoryChild->user_id,
                'village_id' => $machine['position_village_id'],
                'delivery_count' => 1,
                'delivery_income' => $income,
                'month' => $month,
            ]);
        }
        if (!empty($rank_week)) {
            $rank_week->setAttributes([
                'delivery_count' => $rank_week->delivery_count + 1,
                'delivery_income' => bcadd($rank_week->delivery_income, $income, 2)
            ]);
        } else {
            $rank_week = new SrUserVillageRankWeek();
            $rank_week->setAttributes([
                'user_id' => $srUserDeliveryHistoryChild->user_id,
                'village_id' => $machine['position_village_id'],
                'delivery_count' => 1,
                'delivery_income' => $income,
                'week' => $week,
            ]);
        }
        if ($rank_history->save() && $rank_month->save() && $rank_week->save()) {
            $transaction->commit();
            $position_street_name = $machine["street_name"];
            //3塞入排行榜  存入6号数据库
            $village = 'village_'.$machine['position_village_id'];
            $u_id = 'uid_'.$srUserDeliveryHistoryChild->user_id;
            $redis6 = yii::$app->redis;
            $redis6->select(6);
            //3.1查询此用户是否存在排行
            $user_id = $srUserDeliveryHistoryChild->user_id;
            $p_id = $machine['position_village_id'];
            $redis6->zincrby($village,$income,$user_id);
            //$redis6->zincrby($village.'_count',1,$user_id);
            //3.2查询此小区的用户是否存在排行  zincrby已存在就累计，不存在就新增
            $redis6->zincrby($u_id,$income,$p_id);
            $town_name = 'town_'.$position_street_name;
            $redis6->zincrby($town_name,$income,$p_id);
            $redis6->zincrby('all_village',$income,$p_id);
            die(json_encode(['res' => true]));
        }
        $transaction->rollBack();
        die(json_encode(['res' => false]));
    }

    /**
     * 新增回收员
     */
    public function actionRecycler_add(){
        $nick_name = $this->post('nick_name');
        $phone_num = $this->post('phone_num');
        $datepicker_start = $this->post('datepicker_start');
        $datepicker_end = $this->post('datepicker_end');
        //如果出现空值则表示信息不完整
        if(empty($nick_name) || empty($phone_num) || empty($datepicker_start) || empty($datepicker_end)){
            die(json_encode(['res' => false, 'msg' => '提交信息不完整']));
        }
        if(!is_mobile_num($phone_num)){
            die(json_encode(['res' => false, 'msg' => '手机号码不正确']));
        }
        if($datepicker_start >= $datepicker_end){
            die(json_encode(['res' => false, 'msg' => '结束时间必须大于开始时间']));
        }

        //写入数据
        $srRecycler = new SrRecycler();
        $srRecycler->setAttributes([
            'nick_name' => $nick_name,
            'phone_num' => $phone_num,
            'recycler_status' => 1,
            'create_date' => date('Y-m-d H:i:s'),
            'update_date' => date('Y-m-d H:i:s'),
            'del_flag' => '0',
            'password' => 'e4aed93ec8e0084edaef0cb945aa5acb885792dea7c115f6de9a96c77df0ca617761738c76e965f18aafc30eccbe0dacc9ec1788a7a1bbc0d6ef59b98047c099',
            'cooperation_start_time' => $datepicker_start,
            'cooperation_end_time' => $datepicker_end,
            'balance' => 500,
        ]);

        //保存数据
        $res = $srRecycler->save();
        die(json_encode(['res' => $res]));
    }

}