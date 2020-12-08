<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_bin_employee".
 *
 * @property string $id 主键id
 * @property string $bin_id 打包站id
 * @property string $nick_name 昵称
 * @property string $phone_num 手机号
 * @property string $avatar_img 头像图片地址
 * @property string $open_id open_id
 * @property int $employee_status 状态 0 不可用 1 可用
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除标记
 * @property string $password 密码
 * @property string $bank_card_number 银行卡号
 * @property string $id_card_number 身份证号
 */
class SrBinEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_bin_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bin_id', 'employee_status'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
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
            'bin_id' => 'Bin ID',
            'nick_name' => 'Nick Name',
            'phone_num' => 'Phone Num',
            'avatar_img' => 'Avatar Img',
            'open_id' => 'Open ID',
            'employee_status' => 'Employee Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'password' => 'Password',
            'bank_card_number' => 'Bank Card Number',
            'id_card_number' => 'Id Card Number',
        ];
    }
}
