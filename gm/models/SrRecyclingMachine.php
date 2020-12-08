<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycling_machine".
 *
 * @property string $id 主键id
 * @property string $device_id 产品全球唯一编号96bit
 * @property string $icc_id SIM卡串号
 * @property double $longitude 经度
 * @property double $latitude 纬度
 * @property string $community_name 小区名称
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $location 详细位置信息
 * @property string $county_name 区县级名字
 * @property string $street_name 所属街道地址
 * @property int $position_village_id 绑定地址ID
 * @property int $full_count 此机器满箱数量
 * @property string $time_start 使用时间开始
 * @property string $time_end 使用时间结束
 * @property int $handshake_cycle 握手周期，单位分钟
 * @property string $del_flag
 * @property string $sign_key 数据校验密钥
 * @property int $status php专用
 * @property string $can_full_list 满箱状态集合
 * @property int $machine_status 0:正常，1:维修中，2:停止运营
 * @property int $current_apk_id 当前版本apk软件库主键
 * @property int $update_apk_id 需要更新版本apk软件库主键
 * @property string $divece_code 区分厂家
 * @property string $divece_version 区分厂家机器版本号
 * @property string $is_ams_apk 是否自动更新1自动，0手动
 * @property string $province_name 省份名字
 * @property string $version 固件版本
 * @property int $total 垃圾分类桶总数
 */
class SrRecyclingMachine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycling_machine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'longitude', 'latitude', 'create_date', 'update_date', 'del_flag'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['create_date', 'update_date', 'time_start', 'time_end'], 'safe'],
            [['agent', 'position_village_id', 'full_count', 'handshake_cycle', 'status', 'machine_status', 'current_apk_id', 'update_apk_id', 'total'], 'integer'],
            [['device_id', 'icc_id', 'community_name', 'location', 'county_name', 'street_name', 'sign_key', 'can_full_list', 'province_name', 'city_name', 'version'], 'string', 'max' => 255],
            [['del_flag', 'is_ams_apk'], 'string', 'max' => 1],
            [['divece_code', 'divece_version'], 'string', 'max' => 155],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'icc_id' => 'Icc ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'community_name' => 'Community Name',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'location' => 'Location',
            'county_name' => 'County Name',
            'street_name' => 'Street Name',
            'position_village_id' => 'Position Village ID',
            'full_count' => 'Full Count',
            'time_start' => 'Time Start',
            'time_end' => 'Time End',
            'handshake_cycle' => 'Handshake Cycle',
            'del_flag' => 'Del Flag',
            'sign_key' => 'Sign Key',
            'status' => 'Status',
            'can_full_list' => 'Can Full List',
            'machine_status' => 'Machine Status',
            'current_apk_id' => 'Current Apk ID',
            'update_apk_id' => 'Update Apk ID',
            'divece_code' => 'Divece Code',
            'divece_version' => 'Divece Version',
            'is_ams_apk' => 'Is Ams Apk',
            'province_name' => 'Province Name',
            'version' => 'Version',
            'total' => 'Total',
        ];
    }
}
