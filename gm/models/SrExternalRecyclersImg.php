<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_external_recyclers_img".
 *
 * @property int $id 主键ID
 * @property string $image 图片地址
 * @property int $external_recycler_id 回收商充值记录ID
 * @property string $create_date 添加时间
 */
class SrExternalRecyclersImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_external_recyclers_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['external_recycler_id'], 'integer'],
            [['create_date'], 'safe'],
            [['image'], 'string', 'max' => 126],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'external_recycler_id' => 'External Recycler ID',
            'create_date' => 'Create Date',
        ];
    }
}
