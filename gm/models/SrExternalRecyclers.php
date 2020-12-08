<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recyclers".
 *
 * @property string $id 主键id
 * @property int $agent 所属运营商ID 默认归属admin
 * @property string $nick_name 昵称
 * @property string $phone_num 手机号
 * @property string $password 密码
 * @property string $open_id 小程序id
 * @property string $balance 当前可用余额
 * @property int $type 默认0,保留字段
 * @property int $status 用户状态 0 不可用 1 可用
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除标记
 */
class SrExternalRecyclers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recyclers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'type', 'status'], 'integer'],
            [['balance'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['del_flag'], 'required'],
            [['nick_name', 'password', 'open_id'], 'string', 'max' => 255],
            [['phone_num'], 'string', 'max' => 15],
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
            'agent' => 'Agent',
            'nick_name' => 'Nick Name',
            'phone_num' => 'Phone Num',
            'password' => 'Password',
            'open_id' => 'Open ID',
            'balance' => 'Balance',
            'type' => 'Type',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
