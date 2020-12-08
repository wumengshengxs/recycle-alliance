<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_history_child_brush".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property string $delivery_type 投递的垃圾类型
 * @property string $delivery_count 投递数量
 * @property string $delivery_income 投递收益
 * @property string $delivery_time 投递时间
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property string $parent_id 投递记录父表中的id
 * @property string $del_flag 逻辑删除
 */
class SrUserDeliveryHistoryChildBrush extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_history_child_brush';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'delivery_type', 'delivery_count', 'delivery_income', 'delivery_time', 'create_date', 'update_date', 'parent_id'], 'required'],
            [['user_id', 'delivery_type', 'parent_id'], 'integer'],
            [['delivery_count', 'delivery_income'], 'number'],
            [['delivery_time', 'create_date', 'update_date'], 'safe'],
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
            'delivery_type' => 'Delivery Type',
            'delivery_count' => 'Delivery Count',
            'delivery_income' => 'Delivery Income',
            'delivery_time' => 'Delivery Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'parent_id' => 'Parent ID',
            'del_flag' => 'Del Flag',
        ];
    }
}
