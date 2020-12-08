<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_apk_repository".
 *
 * @property int $id 主键id
 * @property string $apk_name 回收机软件更新名称
 * @property string $apk_version 版本号
 * @property string $create_date 创建时间
 * @property string $update_time 最后修改时间
 * @property string $del_flag 逻辑删除
 * @property string $apk_download 回收机软件下载地址
 * @property string $apk_md5 文件md5值
 */
class SrApkRepository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_apk_repository';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date', 'update_time'], 'safe'],
            [['apk_name', 'apk_version', 'apk_download'], 'string', 'max' => 155],
            [['del_flag'], 'string', 'max' => 1],
            [['apk_md5'], 'string', 'max' => 255],
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
            'apk_version' => 'Apk Version',
            'create_date' => 'Create Date',
            'update_time' => 'Update Time',
            'del_flag' => 'Del Flag',
            'apk_download' => 'Apk Download',
            'apk_md5' => 'Apk Md5',
        ];
    }
}
