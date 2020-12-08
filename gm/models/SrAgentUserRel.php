<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_agent_user_rel".
 *
 * @property int $id 主键id
 * @property int $agent 联营方id
 * @property int $user_id 用户id
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property int $del_flag 删除标识 0未删除 1删除
 */
class SrAgentUserRel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_agent_user_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent'], 'required'],
            [['agent', 'user_id', 'del_flag'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
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
            'user_id' => 'User ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
