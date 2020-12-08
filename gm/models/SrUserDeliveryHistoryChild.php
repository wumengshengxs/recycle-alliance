<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_history_child".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property int $agent 所属运营商id
 * @property int $withdraw_id 提现订单主键id,-1历史数据
 * @property int $delivery_type 投递的垃圾类型
 * @property string $delivery_count 投递数量
 * @property string $delivery_income 投递收益
 * @property int $delivery_check 检测称重状态 0:正常;1:称重异常
 * @property string $delivery_time 投递时间
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property string $parent_id 投递记录父表中的id
 * @property string $del_flag 逻辑删除
 * @property int $declarable_status 0:待审核，1:自动无异常，2:手动全无异常，3:有异常
 * @property string $can_name 箱体名称
 * @property int $can_num 箱体编号
 * @property int $machine_id 箱体编号
 * @property int $recycle_child_id 回收清理记录ID
 * @property int $order_withdraw 是否被提现 1未提现  2已提现
 * @property string $has_rank 是否被记录到排行榜
 * @property string $admin_mark 客服备注
 */
class SrUserDeliveryHistoryChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_history_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'agent', 'withdraw_id', 'delivery_type', 'delivery_check', 'parent_id', 'declarable_status', 'can_num', 'machine_id', 'recycle_child_id', 'order_withdraw'], 'integer'],
            [['delivery_count', 'delivery_income'], 'number'],
            [['delivery_time', 'create_date', 'update_date'], 'safe'],
            [['del_flag', 'has_rank'], 'string', 'max' => 1],
            [['can_name'], 'string', 'max' => 100],
            [['admin_mark'], 'string', 'max' => 64],
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
            'agent' => 'Agent',
            'withdraw_id' => 'Withdraw ID',
            'delivery_type' => 'Delivery Type',
            'delivery_count' => 'Delivery Count',
            'delivery_income' => 'Delivery Income',
            'delivery_check' => 'Delivery Check',
            'delivery_time' => 'Delivery Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'parent_id' => 'Parent ID',
            'del_flag' => 'Del Flag',
            'declarable_status' => 'Declarable Status',
            'can_name' => 'Can Name',
            'can_num' => 'Can Num',
            'machine_id' => 'Machine ID',
            'recycle_child_id' => 'Recycle Child ID',
            'order_withdraw' => 'Order Withdraw',
            'has_rank' => 'Has Rank',
            'admin_mark' => 'Admin Mark',
        ];
    }
}
