<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_rubbish_category".
 *
 * @property string $id 主键id
 * @property string $category_name 分类名称
 * @property string $recycle_price 回收价格
 * @property string $sale_price 出售价格
 * @property string $rubbish_unit 计量单位
 * @property string $price_unit 价格单位
 * @property string $category_img_url 分类图片 
 * @property string $category_type 0代表取机器分类，1代表公共回收分类
 * @property int $can_num 箱体编号
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除 0 正常 1 删除
 */
class SrRubbishCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_rubbish_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_name', 'create_date', 'update_date'], 'required'],
            [['recycle_price', 'sale_price'], 'number'],
            [['can_num'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['category_name', 'rubbish_unit', 'price_unit', 'category_img_url'], 'string', 'max' => 255],
            [['category_type', 'del_flag'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'recycle_price' => 'Recycle Price',
            'sale_price' => 'Sale Price',
            'rubbish_unit' => 'Rubbish Unit',
            'price_unit' => 'Price Unit',
            'category_img_url' => 'Category Img Url',
            'category_type' => 'Category Type',
            'can_num' => 'Can Num',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
