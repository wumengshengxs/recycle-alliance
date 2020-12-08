<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_machine_maintain".
 *
 * @property int $id 主键id
 * @property int $agent 联营方id
 * @property int $machine_id 机器id
 * @property int $maintain_id 维修员id
 * @property int $save_id 维修确认维修员id
 * @property int $status 状态 1进行中 2已完成
 * @property int $type 故障类型 1 日常检修 2 机器故障
 * @property string $cause 原因
 * @property string $admin_mark 管理员备注
 * @property string $maintain_mark 维修员备注
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 * @property string $end_time 结束完成时间
 * @property int $del_flag 删除标识 0未删除 1已删除
 */
class SrMachineMaintain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_machine_maintain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'machine_id', 'maintain_id', 'save_id', 'status', 'type', 'del_flag'], 'integer'],
            [['machine_id', 'maintain_id'], 'required'],
            [['create_time', 'update_time', 'end_time'], 'safe'],
            [['cause', 'admin_mark', 'maintain_mark'], 'string', 'max' => 128],
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
            'machine_id' => 'Machine ID',
            'maintain_id' => 'Maintain ID',
            'save_id' => 'Save ID',
            'status' => 'Status',
            'type' => 'Type',
            'cause' => 'Cause',
            'admin_mark' => 'Admin Mark',
            'maintain_mark' => 'Maintain Mark',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'end_time' => 'End Time',
            'del_flag' => 'Del Flag',
        ];
    }
}
