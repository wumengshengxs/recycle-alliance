<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycling_history_parent".
 *
 * @property string $id 主键id
 * @property string $recycler_id 回收员id
 * @property string $recycling_time 回收时间
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property string $del_flag 逻辑删除
 * @property string $machine_id 回收机id
 * @property string $serial_num 流水号
 * @property string $status 是否有效: 0无效 1有效
 * @property int $declarable_status 0:待审核，1:自动无异常，2:手动全无异常，3:有异常
 * @property string $purchase_serial_num 采购流水id
 */
class SrRecyclingHistoryParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycling_history_parent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recycler_id', 'recycling_time', 'machine_id', 'serial_num', 'status'], 'required'],
            [['recycler_id', 'machine_id', 'declarable_status'], 'integer'],
            [['recycling_time', 'create_date', 'update_date'], 'safe'],
            [['del_flag', 'status'], 'string', 'max' => 1],
            [['serial_num'], 'string', 'max' => 255],
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
            'recycler_id' => 'Recycler ID',
            'recycling_time' => 'Recycling Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'machine_id' => 'Machine ID',
            'serial_num' => 'Serial Num',
            'status' => 'Status',
            'declarable_status' => 'Declarable Status',
            'purchase_serial_num' => 'Purchase Serial Num',
        ];
    }
}
