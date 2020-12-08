<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_maintain".
 *
 * @property int $id 主键id
 * @property string $nick_name 维修员昵称
 * @property string $phone_num 手机号
 * @property string $password 密码
 * @property string $avatar_img 头像
 * @property int $agent 代理商id
 * @property int $type 状态 0:普通维修员，1:超级维修员，2:机动维修员
 * @property int $maintain_status 用户状态 0 不可用 1 可用
 * @property string $create_date 添加时间
 * @property string $update_date 修改时间
 * @property int $del_flag 逻辑删除标记  0未删除 1删除
 */
class SrMaintain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_maintain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'type', 'maintain_status', 'del_flag'], 'integer'],
            [['create_date', 'update_date'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['nick_name'], 'string', 'max' => 127],
            [['phone_num'], 'string', 'max' => 15],
            [['password', 'avatar_img'], 'string', 'max' => 255],
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
            'phone_num' => 'Phone Num',
            'password' => 'Password',
            'avatar_img' => 'Avatar Img',
            'agent' => 'Agent',
            'type' => 'Type',
            'maintain_status' => 'Maintain Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
