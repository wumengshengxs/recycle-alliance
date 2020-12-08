<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sys_ad".
 *
 * @property int $id 主键id
 * @property int $parent_id 父id
 * @property string $name 广告名称
 * @property string $img 图片url
 * @property string $resolution_ratio 图片分辨率
 * @property string $jump_link 跳转链接
 * @property int $stay_time 停留时间
 * @property string $time_start 广告时间开始
 * @property string $time_end 广告时间结束
 * @property int $top_sort 置顶顺序1代表第一张2代表第二张以此类推'
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property int $del_flag 删除标志符
 */
class SysAd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_ad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'stay_time', 'top_sort', 'del_flag'], 'integer'],
            [['time_start', 'time_end'], 'required'],
            [['time_start', 'time_end', 'create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['img', 'resolution_ratio', 'jump_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'img' => 'Img',
            'resolution_ratio' => 'Resolution Ratio',
            'jump_link' => 'Jump Link',
            'stay_time' => 'Stay Time',
            'time_start' => 'Time Start',
            'time_end' => 'Time End',
            'top_sort' => 'Top Sort',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'del_flag' => 'Del Flag',
        ];
    }
}
