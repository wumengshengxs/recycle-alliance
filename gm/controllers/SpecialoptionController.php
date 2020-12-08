<?php
namespace gm\controllers;

use gm\models\SrUserDeliverySpecialChild;
use gm\models\SrUserWithdrawOrder;
use gm\models\SrRecycler;
use gm\models\SrUserBookOrder;
use gm\models\SrUserBookRecycler;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserIncomeHistory;
use gm\models\SrUserUsageStatistics;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SpecialoptionController extends GController
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

    public function actionDelivery_no_special()
    {
        $select = Yii::$app->request->post('select');
        if (empty($select)) {
            die(json_encode(['res' => false, 'msg' => '请选择需要无异常的投递记录']));
        }
        $row = SrUserDeliveryHistoryChild::updateAll(['declarable_status' => 2], "id IN ({$select}) AND declarable_status = 0");
        if ($row > 0) {
            die(json_encode(['res' => true, 'msg' => '操作成功']));
        }
        die(json_encode(['res' => false, 'msg' => '操作失败']));
    }

    public function actionCan_verfy()
    {
        $result = Yii::$app->request->post('result');
        $type = Yii::$app->request->post('type');
        $mark = Yii::$app->request->post('mark');
        $imgs = Yii::$app->request->post('imgs');
        empty($imgs) && $imgs = '';
        $id = Yii::$app->request->post('id');
        $special_id = Yii::$app->request->post('special_id');

        if (empty($id) || empty($result) || empty($type)) {
            die(json_encode(['res' => false, 'msg' => '参数错误']));
        }
        $delivery = SrUserDeliveryHistoryChild::find()
            ->where(['id' => $id, 'declarable_status' => 0])
            ->one();
        if (empty($delivery)) {
            die(json_encode(['res' => false, 'msg' => 'id错误']));
        }

        $special = new SrUserDeliverySpecialChild();
        $insert_data = [
            'user_child_id' => $id,
            'recycling_child_id' => $delivery->recycle_child_id,
            'recycling_special_child_id' => $special_id,
            'check_type' => $type,
            'check_result' => $result,
            'check_imgs' => $imgs,
            'check_info' => $mark,
            'special_num' => $delivery->delivery_count,
            'solved_num' => $delivery->delivery_count,
            'solved_amnt' => $delivery->delivery_income,
            'user_id' => $delivery->user_id
        ];

        if ($result == "误操作") {//不对用户惩罚
            $insert_data['solved_amnt'] = 0.00;
            $special->setAttributes($insert_data);
            $delivery->setAttribute('declarable_status', 3);
            $transaction = yii::$app->db->beginTransaction();
            if ($special->save() && $delivery->save()) {
                $transaction->commit();
                die(json_encode(['res' => true, 'msg' => '审核成功']));
            }
            $transaction->rollBack();
            die(json_encode(['res' => true, 'msg' => '审核失败']));
        }


        $time = date('Y-m-d H:i:s');
        $reduce = new SrUserIncomeHistory();
        $insert_reduce = [
            'user_id' => $delivery->user_id,
            'income_type' => 1,
            'income_source' => '异常投递扣减',
            'income_name' => '环保金提现',
            'income_time' => $time,
            'income_amount' => $delivery->delivery_income * -1,
            'create_date' => $time,
            'update_date' => $time,
            'income_unit' => '元',
            'income_direction' => 2,
            'source_id' => 0,
            'source_code' => 'special'
        ];

        $special->setAttributes($insert_data);
        $delivery->setAttribute('declarable_status', 3);

        $account = SrUserUsageStatistics::find()
            ->where(['user_id' => $delivery->user_id])
            ->one();
        if (empty($account)) {
            die(json_encode(['res' => true, 'msg' => '账户异常']));
        }

        $withdraw = SrUserWithdrawOrder::find()
            ->where(['user_id' => $delivery->user_id, 'order_status' => 0])
            ->one();
        if (empty($withdraw)) {//当前无正在进行中提现
            $account->setAttribute('current_env_amount', $account->current_env_amount - $delivery->delivery_income);
            $transaction = yii::$app->db->beginTransaction();
            $bool = $special->save();
            $insert_reduce['source_id'] = $special->id;
            $reduce->setAttributes($insert_reduce);
            if ($bool && $delivery->save() && $reduce->save() && $account->save()) {
                $transaction->commit();
                die(json_encode(['res' => true, 'msg' => '审核成功']));
            }
            $transaction->rollBack();
            die(json_encode(['res' => true, 'msg' => '审核失败1']));
        }
        //当前有正在进行中提现(取消提现)
        $withdraw->setAttributes([
            'order_status' => 2,
            'order_reason' => "包含异常投递金额"
        ]);

        $add = new SrUserIncomeHistory();
        $add->setAttributes([
            'user_id' => $delivery->user_id,
            'income_type' => 1,
            'income_source' => '环保金提现取消',
            'income_name' => '环保金提现取消',
            'income_time' => $time,
            'income_amount' => $withdraw->withdraw_amount,
            'create_date' => $time,
            'update_date' => $time,
            'income_unit' => '元',
            'income_direction' => 1,
            'source_id' => $withdraw->id,
            'source_code' => 'withdraw'
        ]);
        $account->setAttribute('current_env_amount', bcadd(bcsub($account->current_env_amount, $delivery->delivery_income, 2), $withdraw->withdraw_amount, 2));
        $transaction = yii::$app->db->beginTransaction();
        $bool = $special->save();
        $insert_reduce['source_id'] = $special->id;
        $reduce->setAttributes($insert_reduce);
        if ($bool && $delivery->save() && $reduce->save() && $account->save() && $withdraw->save() && $add->save()) {
            $transaction->commit();
            die(json_encode(['res' => true, 'msg' => '审核成功']));
        }
        $transaction->rollBack();
        die(json_encode(['res' => true, 'msg' => '审核失败2']));
    }

    public function actionChange()
    {
        $order_id = Yii::$app->request->post('order_id');
        $recycle = Yii::$app->request->post('recycle');
        $recycle_info = SrRecycler::find()
            ->where(['id' => $recycle])
            ->one();
        $book = SrUserBookOrder::find()
            ->where(['order_id' => $order_id])
            ->one();
        if (empty($book)) {
            die(json_encode(['res' => false, 'msg' => '订单号错误']));
        }
        if (!in_array($book['status'], [0, 1])) {
            die(json_encode(['res' => false, 'msg' => '订单状态错误错误']));
        }
        $book->recycl_id = $recycle;
        $book->status = 1;
        $transaction = Yii::$app->db->beginTransaction();
        $bookRecycle = new SrUserBookRecycler();
        $bookRecycle->admin_name = Yii::$app->user->identity->username;
        $bookRecycle->book_order_id = $order_id;
        $bookRecycle->recycler_id = $recycle;
        $bookRecycle->recycler_name = $recycle_info->nick_name;
        $bookRecycle->type = 1;
        if ($book->save() && $bookRecycle->save()) {
            $transaction->commit();
            die(json_encode(['res' => true, 'msg' => '派单成功']));
        }
        $transaction->rollBack();
        die(json_encode(['res' => false, 'msg' => '派单失败']));

    }

}