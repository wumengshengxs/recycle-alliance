<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_agent_contract".
 *
 * @property string $id 联营合同表
 * @property string $contract 联营合同编号
 * @property string $agent 合同所属联营方id
 * @property int $device_id 合同设备数量
 * @property string $create_time 合同创建时间
 */
class SrAgentContract extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_agent_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent'], 'integer'],
            [['create_time'], 'safe'],
            [['contract', 'device_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contract' => 'Contract',
            'agent' => 'Agent',
            'device_id' => 'Device ID',
            'create_time' => 'Create Time',
        ];
    }
}
