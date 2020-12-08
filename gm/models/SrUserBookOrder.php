<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_book_order".
 *
 * @property int $book_id 用户预约表
 * @property int $user_id
 * @property string $user_name 用户名称
 * @property string $user_phone 用户手机号
 * @property string $user_door_number 详细地址
 * @property int $community_id 小区ID
 * @property string $community_name 小区名称
 * @property string $book_time 预约时间
 * @property string $create_time 创建时间
 * @property string $arrive_time 到达时间
 * @property string $confirm_time 结算时间
 * @property int $status 预约订单状态（0:用户发起，1:用户取消，2:司机到达，3:司机结算完成）
 * @property string $mark 备注
 * @property string $cancel_info 取消原因
 * @property string $arrive_distance
 * @property int $recycl_id 回收员ID
 */
class SrUserBookOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_book_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'community_id', 'status', 'recycl_id'], 'integer'],
            [['user_name', 'user_phone', 'user_door_number', 'community_name', 'book_time', 'arrive_time', 'confirm_time'], 'required'],
            [['create_time', 'arrive_time', 'confirm_time'], 'safe'],
            [['user_name', 'user_phone', 'book_time', 'mark', 'cancel_info', 'arrive_distance'], 'string', 'max' => 100],
            [['user_door_number', 'community_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'user_door_number' => 'User Door Number',
            'community_id' => 'Community ID',
            'community_name' => 'Community Name',
            'book_time' => 'Book Time',
            'create_time' => 'Create Time',
            'arrive_time' => 'Arrive Time',
            'confirm_time' => 'Confirm Time',
            'status' => 'Status',
            'mark' => 'Mark',
            'cancel_info' => 'Cancel Info',
            'arrive_distance' => 'Arrive Distance',
            'recycl_id' => 'Recycl ID',
        ];
    }
}
