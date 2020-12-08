<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sys_ad_space".
 *
 * @property int $id 主键id
 * @property string $agent 所属联营方id
 * @property string $name 广告位名称
 * @property string $code 广告位code：xcx：用户端小程序，dj:回收机待机轮播，db:回收机底部轮播
 * @property int $type 0公共，1自营，2联营
 * @property string $version 版本号
 * @property string $tiny_img 缩略图
 * @property string $resolution_ratio 分辨率
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property int $del_flag 删除标志
 */
class SysAdSpace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_ad_space';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'type', 'del_flag'], 'integer'],
            [['type', 'create_time', 'update_time', 'del_flag'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'version'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 14],
            [['tiny_img'], 'string', 'max' => 1000],
            [['resolution_ratio'], 'string', 'max' => 16],
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
            'name' => 'Name',
            'code' => 'Code',
            'type' => 'Type',
            'version' => 'Version',
            'tiny_img' => 'Tiny Img',
            'resolution_ratio' => 'Resolution Ratio',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'del_flag' => 'Del Flag',
        ];
    }
}
