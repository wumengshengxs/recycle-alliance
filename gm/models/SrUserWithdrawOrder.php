<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_withdraw_order".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property string $order_num 订单号
 * @property string $withdraw_amount 订单金额
 * @property int $order_status 订单状态
 0 申请中（对应后台待审核）
 1 已提现
 2 提现失败
 
 * @property string $order_reason 提现失败的原因
 * @property string $order_create_date 订单创建时间
 * @property string $order_update_date 订单修改时间
 * @property string $del_flag 逻辑删除
 */
class SrUserWithdrawOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_withdraw_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'order_num', 'withdraw_amount', 'order_status', 'order_create_date', 'order_update_date'], 'required'],
            [['user_id', 'order_status'], 'integer'],
            [['withdraw_amount'], 'number'],
            [['order_create_date', 'order_update_date'], 'safe'],
            [['order_num', 'order_reason'], 'string', 'max' => 255],
            [['del_flag'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_num' => 'Order Num',
            'withdraw_amount' => 'Withdraw Amount',
            'order_status' => 'Order Status',
            'order_reason' => 'Order Reason',
            'order_create_date' => 'Order Create Date',
            'order_update_date' => 'Order Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
