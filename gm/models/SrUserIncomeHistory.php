<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_income_history".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property int $income_type 收益类型
1 环保金 2积分
 * @property string $income_source 收益来源
 * @property string $income_name 收益名称
 * @property string $income_time 记录产生时间
 * @property string $income_amount 金额
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $income_unit 单位
 * @property int $income_direction 收益方向  1 收入 2 支出
 * @property string $del_flag 逻辑删除
 * @property int $source_id 对应的来源id
 * @property string $source_code special:用户异常投递，withdraw：用户提现，delivery:用户投递，book：上门回收
 */
class SrUserIncomeHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_income_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'income_type', 'income_name', 'income_time', 'income_amount', 'create_date', 'update_date', 'income_unit', 'income_direction'], 'required'],
            [['user_id', 'income_type', 'income_direction', 'source_id'], 'integer'],
            [['income_time', 'create_date', 'update_date'], 'safe'],
            [['income_amount'], 'number'],
            [['income_source', 'income_name', 'income_unit', 'source_code'], 'string', 'max' => 255],
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
            'income_type' => 'Income Type',
            'income_source' => 'Income Source',
            'income_name' => 'Income Name',
            'income_time' => 'Income Time',
            'income_amount' => 'Income Amount',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'income_unit' => 'Income Unit',
            'income_direction' => 'Income Direction',
            'del_flag' => 'Del Flag',
            'source_id' => 'Source ID',
            'source_code' => 'Source Code',
        ];
    }
}
