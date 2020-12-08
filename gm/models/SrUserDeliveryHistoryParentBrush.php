<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_history_parent_brush".
 *
 * @property string $id 主键ID
 * @property string $machine_id 回收机id
 * @property string $income_amount 回收获得的总金额
 * @property string $delivery_time 投递时间
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property int $total_points 获得总积分
 * @property string $user_id 投递用户的id
 * @property string $serial_num 投递序列号
 * @property string $del_flag 逻辑删除
 */
class SrUserDeliveryHistoryParentBrush extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_history_parent_brush';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'delivery_time', 'create_date', 'update_date', 'user_id', 'serial_num'], 'required'],
            [['machine_id', 'total_points', 'user_id'], 'integer'],
            [['income_amount'], 'number'],
            [['delivery_time', 'create_date', 'update_date'], 'safe'],
            [['serial_num'], 'string', 'max' => 255],
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
            'machine_id' => 'Machine ID',
            'income_amount' => 'Income Amount',
            'delivery_time' => 'Delivery Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'total_points' => 'Total Points',
            'user_id' => 'User ID',
            'serial_num' => 'Serial Num',
            'del_flag' => 'Del Flag',
        ];
    }
}
