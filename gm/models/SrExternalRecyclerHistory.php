<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recycler_history".
 *
 * @property string $id 主键id
 * @property int $agent 所属运营商ID 默认归属admin
 * @property int $order_number 订单号
 * @property string $external_recycler_id 外部回收员id
 * @property string $current_amount 当前余额
 * @property string $income_amount 金额
 * @property string $income_unit 单位
 * @property int $income_direction 收益方向  1 收入 2 支出
 * @property int $order_status 订单状态 0 申请中（对应后台待审核） 1 已提现 2 提现失败 
 * @property string $order_reason 提现失败的原因
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 * @property int $source_id 对应的来源id
 */
class SrExternalRecyclerHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recycler_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'order_number', 'external_recycler_id', 'income_direction', 'order_status', 'source_id'], 'integer'],
            [['current_amount', 'income_amount'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['income_unit', 'order_reason'], 'string', 'max' => 255],
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
            'agent' => 'Agent',
            'order_number' => 'Order Number',
            'external_recycler_id' => 'External Recycler ID',
            'current_amount' => 'Current Amount',
            'income_amount' => 'Income Amount',
            'income_unit' => 'Income Unit',
            'income_direction' => 'Income Direction',
            'order_status' => 'Order Status',
            'order_reason' => 'Order Reason',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'source_id' => 'Source ID',
        ];
    }
}
