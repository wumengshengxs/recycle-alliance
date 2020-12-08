<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_bin".
 *
 * @property string $id 主键id
 * @property string $name 打包站名称
 * @property string $create_date 创建时间
 * @property string $del_flag 逻辑删除标记
 */
class SrBin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_bin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'create_date' => 'Create Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
