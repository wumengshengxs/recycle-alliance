<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_agent_sn".
 *
 * @property string $id 设备序列号表
 * @property string $agent 代理商id
 * @property string $contract_id 合同表
 * @property string $machine_id 设备序列号
 * @property string $create_time 创建时间
 */
class SrAgentSn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_agent_sn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'contract_id'], 'integer'],
            [['create_time'], 'safe'],
            [['machine_id'], 'string', 'max' => 255],
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
            'contract_id' => 'Contract ID',
            'machine_id' => 'Machine ID',
            'create_time' => 'Create Time',
        ];
    }
}
