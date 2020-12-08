<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recyclers_order_child".
 *
 * @property string $id 主键id
 * @property string $order_id 订单id
 * @property int $external_recycler_id 外部回收员id
 * @property int $category_parent 所属的一级垃圾品类
 * @property int $category 垃圾类别
 * @property string $recycling_amount 回收数量
 * @property string $recycling_pay 回收金额
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 * @property string $purchase_serial_num 采购流水id -1是历史数据
 */
class SrExternalRecyclersOrderChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recyclers_order_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'external_recycler_id', 'category_parent', 'category'], 'integer'],
            [['recycling_amount', 'recycling_pay'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['del_flag'], 'string', 'max' => 1],
            [['purchase_serial_num'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'external_recycler_id' => 'External Recycler ID',
            'category_parent' => 'Category Parent',
            'category' => 'Category',
            'recycling_amount' => 'Recycling Amount',
            'recycling_pay' => 'Recycling Pay',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'purchase_serial_num' => 'Purchase Serial Num',
        ];
    }
}
