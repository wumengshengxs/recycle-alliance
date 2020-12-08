<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recyclers_history".
 *
 * @property string $id 主键id
 * @property int $agent 所属运营商ID 默认归属admin
 * @property int $external_recycler_id 回收商id
 * @property string $income_amount 金额
 * @property string $income_unit 单位
 * @property string $income_bank 银行卡号
 * @property int $status 审核状态 1待审核 2 审核通过 
 * @property int $income_direction 收益方向  1 收入 2 支出
 * @property string $current_amount 当前余额
 * @property string $reach_amount 到账金额
 * @property string $reach_bank 到账回执银行流水编号
 * @property string $reach_img 回执到账图片
 * @property string $reach_time 到账日期
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 * @property int $source_id 对应的来源id
 * @property string $source_type order:订单产生;recharge:充值产生
 */
class SrExternalRecyclersHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recyclers_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'external_recycler_id', 'status', 'income_direction', 'source_id'], 'integer'],
            [['income_amount', 'current_amount', 'reach_amount'], 'number'],
            [['reach_time', 'create_date', 'update_date'], 'safe'],
            [['income_unit', 'source_type'], 'string', 'max' => 10],
            [['income_bank', 'reach_bank'], 'string', 'max' => 32],
            [['reach_img'], 'string', 'max' => 126],
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
            'external_recycler_id' => 'External Recycler ID',
            'income_amount' => 'Income Amount',
            'income_unit' => 'Income Unit',
            'income_bank' => 'Income Bank',
            'status' => 'Status',
            'income_direction' => 'Income Direction',
            'current_amount' => 'Current Amount',
            'reach_amount' => 'Reach Amount',
            'reach_bank' => 'Reach Bank',
            'reach_img' => 'Reach Img',
            'reach_time' => 'Reach Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'source_id' => 'Source ID',
            'source_type' => 'Source Type',
        ];
    }
}
