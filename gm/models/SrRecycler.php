<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycler".
 *
 * @property string $id 主键id
 * @property string $nick_name 昵称
 * @property string $phone_num 手机号
 * @property int $agent 代理商id
 * @property string $avatar_img 头像图片地址
 * @property string $open_id 小程序id
 * @property int $type 0:普通回收员，1:超级回收员，2:机动队员
 * @property int $recycler_status 用户状态 0 不可用 1 可用
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除标记
 * @property string $password 密码
 * @property string $cooperation_start_time 合作开始时间
 * @property string $cooperation_end_time 合作结束时间
 * @property string $balance 可用余额
 * @property string $bank_card_number 银行卡号
 * @property string $id_card_number 身份证号
 */
class SrRecycler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nick_name', 'phone_num', 'recycler_status', 'create_date', 'update_date', 'del_flag', 'password', 'cooperation_start_time', 'cooperation_end_time', 'balance'], 'required'],
            [['agent', 'type', 'recycler_status'], 'integer'],
            [['create_date', 'update_date', 'cooperation_start_time', 'cooperation_end_time'], 'safe'],
            [['balance'], 'number'],
            [['nick_name', 'avatar_img', 'open_id', 'password', 'bank_card_number', 'id_card_number'], 'string', 'max' => 255],
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
            'nick_name' => 'Nick Name',
            'phone_num' => 'Phone Num',
            'agent' => 'Agent',
            'avatar_img' => 'Avatar Img',
            'open_id' => 'Open ID',
            'type' => 'Type',
            'recycler_status' => 'Recycler Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'password' => 'Password',
            'cooperation_start_time' => 'Cooperation Start Time',
            'cooperation_end_time' => 'Cooperation End Time',
            'balance' => 'Balance',
            'bank_card_number' => 'Bank Card Number',
            'id_card_number' => 'Id Card Number',
        ];
    }
}
