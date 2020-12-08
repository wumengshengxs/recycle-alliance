<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_maintain_machine_rel".
 *
 * @property int $id 主键id
 * @property int $machine_id 回收机id
 * @property int $maintain_id 维修员id
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property int $del_flag 删除标识 0未删除 1删除
 */
class SrMaintainMachineRel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_maintain_machine_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'create_date', 'update_date'], 'required'],
            [['machine_id', 'maintain_id', 'del_flag'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'machine_id' => 'Machine ID',
            'maintain_id' => 'Maintain ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
