<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_bin_purchase".
 *
 * @property string $id 主键id
 * @property string $serial_num 流水号
 * @property string $bin_id 打包站id
 * @property string $gross_weight 毛重
 * @property string $tare_weight 皮重
 * @property string $net_weight 净重
 * @property string $recycler_weight 回收员清运总重量
 * @property string $recycler_id 回收员id
 * @property string $bin_employee_id 回收站管理员id
 * @property string $purchase_time 回收时间
 * @property string $create_date 记录创建时间
 * @property string $update_date 记录修改时间
 * @property int $purchase_status 0:待审核，1:自动无异常，2:手动全无异常，3:有异常
 * @property string $del_flag 逻辑删除
 */
class SrBinPurchase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_bin_purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bin_id', 'recycler_id', 'bin_employee_id', 'purchase_status'], 'integer'],
            [['gross_weight', 'tare_weight', 'net_weight', 'recycler_weight'], 'number'],
            [['purchase_time', 'create_date', 'update_date'], 'safe'],
            [['serial_num'], 'string', 'max' => 50],
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
            'serial_num' => 'Serial Num',
            'bin_id' => 'Bin ID',
            'gross_weight' => 'Gross Weight',
            'tare_weight' => 'Tare Weight',
            'net_weight' => 'Net Weight',
            'recycler_weight' => 'Recycler Weight',
            'recycler_id' => 'Recycler ID',
            'bin_employee_id' => 'Bin Employee ID',
            'purchase_time' => 'Purchase Time',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'purchase_status' => 'Purchase Status',
            'del_flag' => 'Del Flag',
        ];
    }
}
