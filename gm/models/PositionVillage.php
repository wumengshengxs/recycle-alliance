<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "position_village".
 *
 * @property int $p_id 主键
 * @property int $agent 联营方id
 * @property string $province_name 省份名字
 * @property string $city 市
 * @property string $county_name 区县级名字
 * @property string $town_name 街道详细地址
 * @property string $street_name 所属街道名称
 * @property string $village_code 小区编号
 * @property string $village_name 小区名字
 * @property double $longitude 经度
 * @property double $latitude 纬度
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 * @property int $machine_num 机器数量
 * @property string $works 可执行的业务类型集合(1,2,3....) 1上门回收 2 上门待丢垃圾 3带扩展
 * @property string $del_flag 逻辑删除  0 正常  1 删除
 */
class PositionVillage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position_village';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'province_name', 'city', 'county_name', 'town_name', 'street_name', 'village_name'], 'required'],
            [['agent', 'machine_num'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['create_time', 'update_time'], 'safe'],
            [['province_name', 'county_name', 'town_name', 'street_name', 'village_name'], 'string', 'max' => 255],
            [['city'], 'string', 'max' => 25],
            [['village_code'], 'string', 'max' => 20],
            [['works'], 'string', 'max' => 100],
            [['del_flag'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'agent' => 'Agent',
            'province_name' => 'Province Name',
            'city' => 'City',
            'county_name' => 'County Name',
            'town_name' => 'Town Name',
            'street_name' => 'Street Name',
            'village_code' => 'Village Code',
            'village_name' => 'Village Name',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'machine_num' => 'Machine Num',
            'works' => 'Works',
            'del_flag' => 'Del Flag',
        ];
    }
}
