<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property string $id 购物车表自增
 * @property string $user_id 用户id
 * @property string $business_id 商户id
 * @property string $goods_id 商品id
 * @property string $goods_name 商品名称
 * @property string $goods_price 商品单价
 * @property string $goods_unit 商品单位
 * @property string $buy_num 购买数量
 * @property string $goods_thumb 商品缩略图地址
 * @property string $create_time 创建时间
 */
class Cart extends BaseModel{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'business_id', 'goods_id'], 'integer'],
            [['goods_price', 'buy_num'], 'number'],
            [['create_time'], 'safe'],
            [['goods_name'], 'string', 'max' => 255],
            [['goods_unit'], 'string', 'max' => 16],
            [['goods_thumb'], 'string', 'max' => 2000],
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
            'business_id' => 'Business ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_price' => 'Goods Price',
            'goods_unit' => 'Goods Unit',
            'buy_num' => 'Buy Num',
            'goods_thumb' => 'Goods Thumb',
            'create_time' => 'Create Time',
        ];
    }
}