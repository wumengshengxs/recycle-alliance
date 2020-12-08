<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_history_abnormal".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property string $parent_id 投递父记录中的id
 * @property int $delivery_type 投递的垃圾类型
 * @property string $delivery_count 投递数量/重量
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property int $category 分类名称
 * @property int $can_num 箱体编号
 * @property int $machine_id 机器编号
 * @property string $del_flag 逻辑删除
 */
class SrUserDeliveryHistoryAbnormal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_history_abnormal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'delivery_type', 'category', 'can_num', 'machine_id'], 'integer'],
            [['delivery_count'], 'number'],
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
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'delivery_type' => 'Delivery Type',
            'delivery_count' => 'Delivery Count',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'category' => 'Category',
            'can_num' => 'Can Num',
            'machine_id' => 'Machine ID',
            'del_flag' => 'Del Flag',
        ];
    }
}