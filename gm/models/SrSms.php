<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_sms".
 *
 * @property string $id 短信id
 * @property string $mobile 手机号
 * @property string $content 短信内容
 * @property int $type 短信类型 1:验证码
 * @property int $time_stamp 时间戳
 */
class SrSms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'time_stamp'], 'integer'],
            [['mobile'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'content' => 'Content',
            'type' => 'Type',
            'time_stamp' => 'Time Stamp',
        ];
    }
}
