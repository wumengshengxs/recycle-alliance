<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user".
 *
 * @property string $id 主键id
 * @property string $nick_name 昵称 
 * @property string $avatar_url 头像地址
 * @property string $open_id 小程序openid
 * @property string $phone_num 手机号
 * @property int $user_status 用户状态 0 已删除 1正常 2 已拉黑 
 * @property string $remarks 封号备注
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 */
class SrUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_status', 'create_date', 'update_date'], 'required'],
            [['user_status'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['nick_name', 'avatar_url', 'open_id', 'phone_num', 'remarks'], 'string', 'max' => 255],
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
            'nick_name' => 'Nick Name',
            'avatar_url' => 'Avatar Url',
            'open_id' => 'Open ID',
            'phone_num' => 'Phone Num',
            'user_status' => 'User Status',
            'remarks' => 'Remarks',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
