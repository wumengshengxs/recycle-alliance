<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recycler".
 *
 * @property string $id 主键id
 * @property int $agent 所属运营商ID 默认归属admin
 * @property string $nick_name 昵称
 * @property string $phone_num 手机号
 * @property string $password_laws 密码明文
 * @property string $password_secret 密码密文
 * @property string $open_id 小程序id
 * @property string $balance 当前可用余额
 * @property int $type 0散户，1物业
 * @property int $village_id 物业小区表id,散户默认为暂无
 * @property int $status 用户状态 0 不可用 1 可用
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除标记
 */
class SrExternalRecycler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recycler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent', 'type', 'village_id', 'status'], 'integer'],
            [['balance'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['nick_name', 'password_laws', 'password_secret', 'open_id'], 'string', 'max' => 255],
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
            'password_laws' => 'Password Laws',
            'password_secret' => 'Password Secret',
            'open_id' => 'Open ID',
            'balance' => 'Balance',
            'type' => 'Type',
            'village_id' => 'Village ID',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
