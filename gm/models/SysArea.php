<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sys_area".
 *
 * @property int $id 唯一标识
 * @property int $parent_id 上级id
 * @property string $parent_ids 所有上级id字符串 逗号分隔
 * @property string $name 地区名称
 * @property string $code 地区编码
 * @property int $type 地区类型
 * @property int $sort 排序
 * @property int $area_level 地区级别
 * @property int $create_by 创建人
 * @property string $create_date 创建时间
 * @property int $update_by 修改人
 * @property string $update_date 修改时间
 * @property string $remarks 备注
 * @property string $del_flag 删除标识 1-删除 0-正常
 */
class SysArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'name', 'code', 'create_by', 'create_date', 'del_flag'], 'required'],
            [['parent_id', 'type', 'sort', 'area_level', 'create_by', 'update_by'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['parent_ids'], 'string', 'max' => 300],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 20],
            [['remarks'], 'string', 'max' => 200],
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
            'parent_id' => 'Parent ID',
            'parent_ids' => 'Parent Ids',
            'name' => 'Name',
            'code' => 'Code',
            'type' => 'Type',
            'sort' => 'Sort',
            'area_level' => 'Area Level',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
            'remarks' => 'Remarks',
            'del_flag' => 'Del Flag',
        ];
    }
}
