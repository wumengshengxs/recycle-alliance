<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "system_region".
 *
 * @property int $id
 * @property int $pid 行政区域父ID，例如区县的pid指向市，市的pid指向省，省的pid则是0
 * @property string $name
 * @property int $type
 * @property int $code 行政区域编码
 */
class SystemRegion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'code'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'type' => 'Type',
            'code' => 'Code',
        ];
    }
}
