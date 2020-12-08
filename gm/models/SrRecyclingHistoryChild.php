<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycling_history_child".
 *
 * @property string $id 主键id
 * @property int $category 垃圾类别
 * @property string $recycling_amount 回收数量
 * @property string $recycling_pay 回收金额
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $parent_id 父记录id
 * @property string $del_flag 逻辑删除
 * @property string $recycling_time 回收时间
 * @property int $recycler_id 回收员ID
 * @property string $can_name 箱体名称
 * @property int $can_num 箱体编号
 * @property int $machine_id 机器ID
 * @property int $status 0:未审核，1:正常，2:异常
 * @property int $declarable_status 123
 * @property string $purchase_serial_num 采购流水id
 */
class SrRecyclingHistoryChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycling_history_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'recycling_amount', 'recycling_pay', 'parent_id'], 'required'],
            [['category', 'parent_id', 'recycler_id', 'can_num', 'machine_id', 'status', 'declarable_status'], 'integer'],
            [['recycling_amount', 'recycling_pay'], 'number'],
            [['create_date', 'update_date', 'recycling_time'], 'safe'],
            [['del_flag'], 'string', 'max' => 1],
            [['can_name'], 'string', 'max' => 100],
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
            'category' => 'Category',
            'recycling_amount' => 'Recycling Amount',
            'recycling_pay' => 'Recycling Pay',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'parent_id' => 'Parent ID',
            'del_flag' => 'Del Flag',
            'recycling_time' => 'Recycling Time',
            'recycler_id' => 'Recycler ID',
            'can_name' => 'Can Name',
            'can_num' => 'Can Num',
            'machine_id' => 'Machine ID',
            'status' => 'Status',
            'declarable_status' => 'Declarable Status',
            'purchase_serial_num' => 'Purchase Serial Num',
        ];
    }
}
