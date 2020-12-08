<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_book_recycler".
 *
 * @property string $id 主键id
 * @property string $book_order_id 预约订单编号
 * @property string $recycler_id 回收员id
 * @property string $recycler_name 回收员id
 * @property int $type 0:自动分配,1:人工派单
 * @property string $admin_name 派单操作员
 * @property string $create_date 创建时间
 * @property string $del_flag 逻辑删除
 */
class SrUserBookRecycler extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_book_recycler';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_order_id', 'recycler_id', 'type'], 'integer'],
            [['create_date'], 'safe'],
            [['recycler_name'], 'string', 'max' => 255],
            [['admin_name'], 'string', 'max' => 100],
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
            'book_order_id' => 'Book Order ID',
            'recycler_id' => 'Recycler ID',
            'recycler_name' => 'Recycler Name',
            'type' => 'Type',
            'admin_name' => 'Admin Name',
            'create_date' => 'Create Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
