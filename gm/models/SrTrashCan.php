<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_trash_can".
 *
 * @property string $id 主键id
 * @property string $machine_id 回收机id，标记这个垃圾桶数据哪个回收机
 * @property string $can_name 垃圾桶名称
 * @property string $max_quantity 垃圾桶最大容量，单位dm³
 * @property double $max_weight 垃圾桶承受的最大重量，单位kg
 * @property int $max_count 瓶子最大个数
 * @property double $max_temperature 最高温度 
 * @property double $temperature 垃圾桶温度，单位℃
 * @property int $temperature_warn 温度预警状态 0 正常 1 警告
 * @property double $quantity 垃圾容量，单位dm³
 * @property int $can_num 箱体编号
 * @property double $weight 垃圾重量，单位kg
 * @property int $count 垃圾数量（对于按数量来计算的垃圾，如饮料瓶）
 * @property int $category 垃圾分类 垃圾类别 （1：饮料瓶， 5：纺织物 7：玻璃， 3：书籍 8：有害垃圾， 6：金属 2：纸类， 4：塑料） 
 * @property int $can_full 是否满桶 0：容量满1：超重 2：两种均超标
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $recycle_price 回收价格
 * @property string $sale_price 出售价格
 * @property string $rubbish_unit 计量单位
 * @property string $price_unit 价格单位
 * @property string $fault_state 故障代码
 * @property string $activation_status 0代表未激活，1代表激活
 * @property string $work_state 工作状态代码
 * @property string $del_flag 逻辑删除
 */
class SrTrashCan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_trash_can';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['machine_id', 'max_quantity', 'max_weight', 'max_temperature', 'quantity', 'weight', 'count', 'category', 'can_full', 'create_date', 'update_date'], 'required'],
            [['machine_id', 'max_count', 'temperature_warn', 'can_num', 'count', 'category', 'can_full'], 'integer'],
            [['max_quantity', 'max_weight', 'max_temperature', 'temperature', 'quantity', 'weight', 'recycle_price', 'sale_price'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['can_name', 'rubbish_unit', 'price_unit', 'fault_state', 'work_state'], 'string', 'max' => 255],
            [['activation_status', 'del_flag'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'machine_id' => 'Machine ID',
            'can_name' => 'Can Name',
            'max_quantity' => 'Max Quantity',
            'max_weight' => 'Max Weight',
            'max_count' => 'Max Count',
            'max_temperature' => 'Max Temperature',
            'temperature' => 'Temperature',
            'temperature_warn' => 'Temperature Warn',
            'quantity' => 'Quantity',
            'can_num' => 'Can Num',
            'weight' => 'Weight',
            'count' => 'Count',
            'category' => 'Category',
            'can_full' => 'Can Full',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'recycle_price' => 'Recycle Price',
            'sale_price' => 'Sale Price',
            'rubbish_unit' => 'Rubbish Unit',
            'price_unit' => 'Price Unit',
            'fault_state' => 'Fault State',
            'activation_status' => 'Activation Status',
            'work_state' => 'Work State',
            'del_flag' => 'Del Flag',
        ];
    }
}
