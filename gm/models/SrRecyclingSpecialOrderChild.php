<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycling_special_order_child".
 *
 * @property int $id 主键id
 * @property int $recycling_child_id 回收记录子记录id
 * @property string $category_name 分类名称
 * @property string $amount 金额
 * @property double $weight_or_count 数量或重量，申报重量
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除
 * @property string $parent_id 父记录id
 * @property int $type 异常类型 1 人为异常  2 自然异常  默认2
 * @property int $cun_num 申报箱体
 * @property string $confirm_num 确认重量
 * @property string $price 当时种类单价
 * @property int $status 0:待审核，1:审核通过，2:审核失败
 * @property string $special_type 异常原因
 * @property string $special_info 异常说明
 * @property string $special_imgs 异常图片
 */
class SrRecyclingSpecialOrderChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycling_special_order_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recycling_child_id', 'category_name', 'weight_or_count', 'create_date', 'update_date', 'parent_id'], 'required'],
            [['recycling_child_id', 'parent_id', 'type', 'cun_num', 'status'], 'integer'],
            [['weight_or_count', 'confirm_num', 'price'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['category_name', 'amount', 'special_imgs'], 'string', 'max' => 255],
            [['del_flag'], 'string', 'max' => 1],
            [['special_type'], 'string', 'max' => 100],
            [['special_info'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recycling_child_id' => 'Recycling Child ID',
            'category_name' => 'Category Name',
            'amount' => 'Amount',
            'weight_or_count' => 'Weight Or Count',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
            'parent_id' => 'Parent ID',
            'type' => 'Type',
            'cun_num' => 'Cun Num',
            'confirm_num' => 'Confirm Num',
            'price' => 'Price',
            'status' => 'Status',
            'special_type' => 'Special Type',
            'special_info' => 'Special Info',
            'special_imgs' => 'Special Imgs',
        ];
    }
}
