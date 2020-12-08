<?php


namespace common\models;


use Yii;
use gm\models\SysAdminLog;

class AdminLog
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 添加操作日志
     * @param $mark => 操作记录名称
     * @param $description =>详细
     */
    public function addLog($mark,$description)
    {
        $action = Yii::$app->controller->action->id;
        $module = Yii::$app->controller->module->id;
        $controller = Yii::$app->controller->id;
        $id = Yii::$app->user->identity->id;//操作人员id
        $userName = Yii::$app->user->identity->username;//操作人员名
        $time = date('Y-m-d H:i:s');
        $data['SysAdminLog'] = [
            'agent' => $id,
            'username' => $userName,
            'mark' => $mark,
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'create_time' => $time,
            'description' => $description
        ];
        $model = new SysAdminLog();

        $model->load($data);
        $model->save();

    }
}