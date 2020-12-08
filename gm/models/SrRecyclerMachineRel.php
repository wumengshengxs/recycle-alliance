<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycler_machine_rel".
 *
 * @property string $id 主键id
 * @property string $machine_id 回收机id
 * @property string $recycler_id 回收员id
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 */
class SrRecyclerMachineRel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycler_machine_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'recycler_id', 'create_date', 'update_date'], 'required'],
            [['machine_id', 'recycler_id'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
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
            'machine_id' => 'Machine ID',
            'recycler_id' => 'Recycler ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
