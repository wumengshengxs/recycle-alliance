<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_agent_trade_history".
 *
 * @property string $id 账户明细表
 * @property string $agent 代理商id
 * @property int $type 明细类型 1:收入 2:支出
 * @property string $trade_num 打款银行流水号(支出明细无需录入此项)
 * @property string $bank_name 打款开户行名称(支出明细无需录入此项)
 * @property string $amount 明细金额
 * @property string $content 打款附言(支出明细无需录入此项)
 * @property string $receipt_url 转账凭证截图链接(支出明细无需录入此项)
 * @property int $status 交易状态 0:审核中 1:完成 2:驳回
 * @property string $verify 审核人id(支出明细无需录入此项)
 * @property string $check 复核人id(支出明细无需录入此项)
 * @property string $create_time 创建时间
 * @property string $verify_time 审核时间(支出明细无需录入此项)
 * @property string $check_time 复核时间(支出明细无需录入此项)
 */
class SrAgentTradeHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_agent_trade_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'type', 'status', 'verify', 'check'], 'integer'],
            [['amount'], 'number'],
            [['create_time', 'verify_time', 'check_time'], 'safe'],
            [['trade_num', 'bank_name', 'content', 'receipt_url'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'trade_num' => 'Trade Num',
            'bank_name' => 'Bank Name',
            'amount' => 'Amount',
            'content' => 'Content',
            'receipt_url' => 'Receipt Url',
            'status' => 'Status',
            'verify' => 'Verify',
            'check' => 'Check',
            'create_time' => 'Create Time',
            'verify_time' => 'Verify Time',
            'check_time' => 'Check Time',
        ];
    }
}
