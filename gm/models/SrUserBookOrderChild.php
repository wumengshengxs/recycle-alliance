<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_user_book_order_child".
 *
 * @property string $id 主键id
 * @property string $user_id 用户id
 * @property int $delivery_type 结算的垃圾类型
 * @property string $delivery_count 结算数量
 * @property string $delivery_income 结算收益
 * @property string $delivery_time 结算时间
 * @property string $create_date 预约结算创建时间
 * @property string $update_date 预约结算修改时间
 * @property string $book_id 预约记录父表中的id
 * @property string $del_flag 逻辑删除
 * @property int $is_flush 是否刷量
 * @property string $has_rank 是否被记录到排行榜
 */
class SrUserBookOrderChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_user_book_order_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'delivery_type', 'delivery_count', 'delivery_income', 'delivery_time', 'book_id'], 'required'],
            [['user_id', 'delivery_type', 'book_id', 'is_flush'], 'integer'],
            [['delivery_count', 'delivery_income'], 'number'],
            [['delivery_time', 'create_date', 'update_date'], 'safe'],
            [['del_flag', 'has_rank'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'delivery_type' => 'Delivery Type',
            'delivery_count' => 'Delivery Count',
            'delivery_income' => 'Delivery Income',
            'delivery_time' => 'Delivery Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'book_id' => 'Book ID',
            'del_flag' => 'Del Flag',
            'is_flush' => 'Is Flush',
            'has_rank' => 'Has Rank',
        ];
    }
}
