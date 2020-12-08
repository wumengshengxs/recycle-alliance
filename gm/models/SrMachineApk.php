<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_machine_apk".
 *
 * @property int $id 主键id
 * @property string $apk_name apk名字
 * @property int $machine_id 机器序列号
 * @property int $current_version_id 当前版本id
 * @property int $update_version_id 需要更新版本的id
 * @property string $apk_md5 回收机软件md5摘要
 * @property string $apk_download 回收机软件下载地址
 * @property string $create_date 创建时间
 * @property string $update_time 最后修改时间
 * @property string $del_flag 逻辑删除
 */
class SrMachineApk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_machine_apk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'current_version_id', 'update_version_id'], 'integer'],
            [['create_date', 'update_time'], 'safe'],
            [['apk_name', 'apk_md5', 'apk_download'], 'string', 'max' => 155],
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
            'apk_name' => 'Apk Name',
            'machine_id' => 'Machine ID',
            'current_version_id' => 'Current Version ID',
            'update_version_id' => 'Update Version ID',
            'apk_md5' => 'Apk Md5',
            'apk_download' => 'Apk Download',
            'create_date' => 'Create Date',
            'update_time' => 'Update Time',
            'del_flag' => 'Del Flag',
        ];
    }
}
