<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_special_child".
 *
 * @property string $id 主键id
 * @property string $user_child_id 用户投递id
 * @property string $recycling_child_id 回收记录id
 * @property string $recycling_special_child_id 回收异常记录id
 * @property string $check_type 核实方式
 * @property string $check_result 核实结果
 * @property string $check_imgs 核实截图
 * @property string $check_info 核实说明
 * @property string $special_num 异常重量
 * @property string $solved_num 处理重量
 * @property string $solved_amnt 处理金额
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property string $del_flag 逻辑删除
 * @property string $user_id 用户id
 */
class SrUserDeliverySpecialChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_special_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_child_id', 'recycling_child_id', 'recycling_special_child_id', 'user_id'], 'integer'],
            [['special_num', 'solved_num', 'solved_amnt'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['check_type'], 'string', 'max' => 20],
            [['check_result'], 'string', 'max' => 100],
            [['check_imgs', 'check_info'], 'string', 'max' => 255],
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
            'user_child_id' => 'User Child ID',
            'recycling_child_id' => 'Recycling Child ID',
            'recycling_special_child_id' => 'Recycling Special Child ID',
            'check_type' => 'Check Type',
            'check_result' => 'Check Result',
            'check_imgs' => 'Check Imgs',
            'check_info' => 'Check Info',
            'special_num' => 'Special Num',
            'solved_num' => 'Solved Num',
            'solved_amnt' => 'Solved Amnt',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'user_id' => 'User ID',
        ];
    }
}
