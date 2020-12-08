<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycler_message_can_full".
 *
 * @property string $id 主键id
 * @property string $message_content 消息内容
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $machine_id 回收机id
 * @property string $full_can_type 满箱说明
 * @property int $message_status 消息状态 1 未查看 2 已查看
 * @property string $del_flag 逻辑删除
 * @property string $recycler_id 回收员id
 */
class SrRecyclerMessageCanFull extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycler_message_can_full';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_content', 'create_date', 'update_date', 'machine_id', 'full_can_type', 'message_status', 'recycler_id'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['machine_id', 'message_status', 'recycler_id'], 'integer'],
            [['message_content'], 'string', 'max' => 500],
            [['full_can_type'], 'string', 'max' => 255],
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
            'message_content' => 'Message Content',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'machine_id' => 'Machine ID',
            'full_can_type' => 'Full Can Type',
            'message_status' => 'Message Status',
            'del_flag' => 'Del Flag',
            'recycler_id' => 'Recycler ID',
        ];
    }
}
