<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_usage_statistics".
 *
 * @property int $id 主键id
 * @property string $user_id 用户id
 * @property int $number_of_delivery 投递次数
 * @property string $cumulative_income 累计收益
 * @property string $cumulative_integral 累计积分
 * @property string $current_env_amount 当前可用环保金
 * @property string $book_income 上门回收累计收益
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间 
 * @property string $del_flag 逻辑删除
 * @property int $cumulative_count 累计投递个数(计算饮料瓶)
 * @property string $cumulative_weight 累计投递重量(非饮料瓶)
 * @property string $has_cumulative 是否统计历史重量
 */
class SrUserUsageStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_usage_statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'create_date', 'update_date'], 'required'],
            [['user_id', 'number_of_delivery', 'cumulative_integral', 'cumulative_count'], 'integer'],
            [['cumulative_income', 'current_env_amount', 'book_income', 'cumulative_weight'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['del_flag'], 'string', 'max' => 10],
            [['has_cumulative'], 'string', 'max' => 1],
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
            'number_of_delivery' => 'Number Of Delivery',
            'cumulative_income' => 'Cumulative Income',
            'cumulative_integral' => 'Cumulative Integral',
            'current_env_amount' => 'Current Env Amount',
            'book_income' => 'Book Income',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'cumulative_count' => 'Cumulative Count',
            'cumulative_weight' => 'Cumulative Weight',
            'has_cumulative' => 'Has Cumulative',
        ];
    }
}
