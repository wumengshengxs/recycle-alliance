<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recyclers_order".
 *
 * @property string $id 主键id
 * @property int $agent 所属运营商ID 默认归属admin
 * @property int $recyclers_id 回收商id
 * @property int $external_recycler_id 外部回收员id
 * @property string $order_amount 本订单金额
 * @property string $actual_amount 本订单实际金额
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 */
class SrExternalRecyclersOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recyclers_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'recyclers_id', 'external_recycler_id'], 'integer'],
            [['order_amount', 'actual_amount'], 'number'],
            [['create_date', 'update_date'], 'safe'],
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
            'recyclers_id' => 'Recyclers ID',
            'external_recycler_id' => 'External Recycler ID',
            'order_amount' => 'Order Amount',
            'actual_amount' => 'Actual Amount',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
