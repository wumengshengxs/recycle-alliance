<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_recycler_rest".
 *
 * @property string $id 主键id
 * @property int $recycler_id 回收员id
 * @property string $day 休息日期
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property string $del_flag 逻辑删除标记
 */
class SrRecyclerRest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_recycler_rest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recycler_id'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['day'], 'string', 'max' => 30],
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
            'recycler_id' => 'Recycler ID',
            'day' => 'Day',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'del_flag' => 'Del Flag',
        ];
    }
}
