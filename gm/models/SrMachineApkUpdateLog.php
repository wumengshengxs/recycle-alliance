<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_machine_apk_update_log".
 *
 * @property int $id 主键id
 * @property int $machine_id 回收机id
 * @property int $apk_id 需要更新版本apk软件库主键
 * @property string $create_date 创建时间
 * @property string $del_flag 逻辑删除
 */
class SrMachineApkUpdateLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_machine_apk_update_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'apk_id'], 'integer'],
            [['create_date'], 'safe'],
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
            'apk_id' => 'Apk ID',
            'create_date' => 'Create Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
