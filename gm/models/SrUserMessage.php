<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_message".
 *
 * @property string $id 逻辑删除出
 * @property string $user_id 用户id
 * @property string $message_type 消息类型  1 活动消息 2 投递通知 3普通消息(不带跳转)
 * @property string $message_title 消息标题
 * @property string $message_content 消息正文
 * @property string $message_url 消息链接  活动链接或 投递详情页面链接
 * @property string $message_receive_time 消息接收时间
 * @property string $message_status 消息状态 0 未读 1 已读
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 */
class SrUserMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'message_type', 'message_title', 'message_content', 'message_receive_time', 'message_status', 'create_date', 'update_date'], 'required'],
            [['user_id', 'message_type', 'message_status'], 'integer'],
            [['message_content'], 'string'],
            [['message_receive_time', 'create_date', 'update_date'], 'safe'],
            [['message_title', 'message_url'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'message_type' => 'Message Type',
            'message_title' => 'Message Title',
            'message_content' => 'Message Content',
            'message_url' => 'Message Url',
            'message_receive_time' => 'Message Receive Time',
            'message_status' => 'Message Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
