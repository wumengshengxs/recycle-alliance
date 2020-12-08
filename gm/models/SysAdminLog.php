<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sys_admin_log".
 *
 * @property int $id 主键id
 * @property int $agent 操作人联营方id
 * @property string $username 用户名
 * @property string $mark 操作说明
 * @property string $module 模块名
 * @property string $controller 控制器名
 * @property string $action 方法名
 * @property string $create_time 操作时间
 * @property string $description 操作明细
 */
class SysAdminLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_admin_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent'], 'integer'],
            [['create_time'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['mark', 'controller', 'action'], 'string', 'max' => 64],
            [['module'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agent' => 'Agent',
            'username' => 'Username',
            'mark' => 'Mark',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'create_time' => 'Create Time',
            'description' => 'Description',
        ];
    }
}
