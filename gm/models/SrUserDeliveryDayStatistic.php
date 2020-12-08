<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_delivery_day_statistic".
 *
 * @property string $id 投递数据日汇总id
 * @property int $agent 运营商ID
 * @property int $machine_id 设备id
 * @property string $province_name 省/直辖市
 * @property string $county_name 地级市/区/县
 * @property string $street_name 街/镇
 * @property string $community_name 小区名称
 * @property string $category_1 饮料瓶
 * @property string $category_2 纸类
 * @property string $category_3 书籍
 * @property string $category_4 塑料
 * @property string $category_5 纺织物
 * @property string $category_6 金属
 * @property string $category_7 玻璃
 * @property string $category_8 有害物
 * @property int $last_delivery_child_id 最后一条投递明细的id
 * @property string $create_date 汇总日期
 */
class SrUserDeliveryDayStatistic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_delivery_day_statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'machine_id', 'last_delivery_child_id'], 'integer'],
            [['category_1', 'category_2', 'category_3', 'category_4', 'category_5', 'category_6', 'category_7', 'category_8'], 'number'],
            [['create_date'], 'safe'],
            [['province_name', 'county_name', 'street_name', 'community_name'], 'string', 'max' => 255],
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
            'machine_id' => 'Machine ID',
            'province_name' => 'Province Name',
            'county_name' => 'County Name',
            'street_name' => 'Street Name',
            'community_name' => 'Community Name',
            'category_1' => 'Category 1',
            'category_2' => 'Category 2',
            'category_3' => 'Category 3',
            'category_4' => 'Category 4',
            'category_5' => 'Category 5',
            'category_6' => 'Category 6',
            'category_7' => 'Category 7',
            'category_8' => 'Category 8',
            'last_delivery_child_id' => 'Last Delivery Child ID',
            'create_date' => 'Create Date',
        ];
    }
}
