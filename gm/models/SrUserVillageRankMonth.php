<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_village_rank_month".
 *
 * @property string $id 主键id
 * @property int $agent 运营商ID
 * @property string $user_id 用户id
 * @property int $village_id 小区id
 * @property int $delivery_count 本月累计次数
 * @property string $delivery_income 本月收获环保金金额
 * @property string $delivery_weight 本月投递重量
 * @property int $rank 月度排名
 * @property string $month 月度
 * @property string $close_date 截止日期
 * @property string $update_date 记录修改时间
 * @property string $del_flag 逻辑删除
 */
class SrUserVillageRankMonth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_village_rank_month';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'user_id', 'village_id', 'delivery_count', 'rank'], 'integer'],
            [['delivery_income', 'delivery_weight'], 'number'],
            [['update_date'], 'safe'],
            [['month', 'close_date'], 'string', 'max' => 10],
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
            'agent' => 'Agent',
            'user_id' => 'User ID',
            'village_id' => 'Village ID',
            'delivery_count' => 'Delivery Count',
            'delivery_income' => 'Delivery Income',
            'delivery_weight' => 'Delivery Weight',
            'rank' => 'Rank',
            'month' => 'Month',
            'close_date' => 'Close Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
