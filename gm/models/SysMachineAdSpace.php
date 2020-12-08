<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sys_machine_ad_space".
 *
 * @property int $id 主键id
 * @property int $ad_space_id 广告位表id
 * @property int $position_village_id 所属小区id
 * @property string $code 广告位code：xcx：用户端小程序，dj:回收机待机轮播，db:回收机底部轮播
 * @property string $create_time 创建时间
 * @property int $del_flag 删除标志
 */
class SysMachineAdSpace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_machine_ad_space';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_space_id', 'position_village_id', 'del_flag'], 'integer'],
            [['code', 'create_time', 'del_flag'], 'required'],
            [['create_time'], 'safe'],
            [['code'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_space_id' => 'Ad Space ID',
            'position_village_id' => 'Position Village ID',
            'code' => 'Code',
            'create_time' => 'Create Time',
            'del_flag' => 'Del Flag',
        ];
    }
}
