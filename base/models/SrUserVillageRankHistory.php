<?php

namespace base\models;

use Yii;

/**
 * This is the model class for table "sr_user_village_rank_history".
 *
 * @property string $id 主键id
 * @property int $parent_id 父记录id
 * @property int $user_id 用户id
 * @property int $village_id 小区id
 * @property int $day_delivery_count 当日投递次数
 * @property string $day_delivery_income 本日收获环保金金额
 * @property int $month_day_delivery_count 截至本日月度总次数
 * @property string $month_day_delivery_income 截至当日月度总金额
 * @property int $month_day_rank 截至当日月度排名
 * @property int $week_day_delivery_count 截至本日周度总次数
 * @property string $week_day_delivery_income 截至当日周度总金额
 * @property int $week_day_rank 截至当日周度排名
 * @property string $close_date 日期
 * @property string $update_date 记录修改时间
 * @property string $del_flag 逻辑删除
 */
class SrUserVillageRankHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_village_rank_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'user_id', 'village_id', 'day_delivery_count', 'month_day_delivery_count', 'month_day_rank', 'week_day_delivery_count', 'week_day_rank'], 'integer'],
            [['day_delivery_income', 'month_day_delivery_income', 'week_day_delivery_income'], 'number'],
            [['update_date'], 'safe'],
            [['close_date'], 'string', 'max' => 10],
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
            'parent_id' => 'Parent ID',
            'user_id' => 'User ID',
            'village_id' => 'Village ID',
            'day_delivery_count' => 'Day Delivery Count',
            'day_delivery_income' => 'Day Delivery Income',
            'month_day_delivery_count' => 'Month Day Delivery Count',
            'month_day_delivery_income' => 'Month Day Delivery Income',
            'month_day_rank' => 'Month Day Rank',
            'week_day_delivery_count' => 'Week Day Delivery Count',
            'week_day_delivery_income' => 'Week Day Delivery Income',
            'week_day_rank' => 'Week Day Rank',
            'close_date' => 'Close Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
