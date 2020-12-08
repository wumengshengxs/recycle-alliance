<?php

namespace bigdata\models;

use Yii;

/**
 * This is the model class for table "sr_recycling_special_order_parent".
 *
 * @property string $id 主键id
 * @property string $recycler_id 回收员(申请人id)
 * @property string $recycling_order_id 回收订单id
 * @property int $recycling_parent_id 回收记录id
 * @property string $amount 涉及金额
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 */
class SrRecyclingSpecialOrderParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycling_special_order_parent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recycler_id', 'recycling_order_id', 'amount', 'create_date', 'update_date'], 'required'],
            [['recycler_id', 'recycling_order_id', 'recycling_parent_id'], 'integer'],
            [['amount'], 'number'],
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
            'recycler_id' => 'Recycler ID',
            'recycling_order_id' => 'Recycling Order ID',
            'recycling_parent_id' => 'Recycling Parent ID',
            'amount' => 'Amount',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
