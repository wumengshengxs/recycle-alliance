<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_device_code".
 *
 * @property string $id 主键id
 * @property string $zip_code 邮编
 * @property int $machine_code 机器自增编号
 */
class SrDeviceCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_device_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zip_code', 'machine_code'], 'required'],
            [['machine_code'], 'integer'],
            [['zip_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zip_code' => 'Zip Code',
            'machine_code' => 'Machine Code',
        ];
    }
}
