<?php
namespace gm\controllers;


use gm\models\SrRecyclingMachine;
use gm\models\SrTrashCan;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ConsoleController extends GController{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * 数据刷量模块
     */
    public function actionFlush(){
        phpinfo();exit;
        //获取已注册回收机
        $recycling_machine = SrRecyclingMachine::find()->select(['id'])->asArray()->all();
        $recycling_machine_id = array_column($recycling_machine, 'id');

        //获取对应箱体类型
        $trash_can = SrTrashCan::find()
            ->where(['in', 'machine_id', $recycling_machine_id])->asArray()->all();
        $machine_trash_can = [];
        foreach ($trash_can as $v){
            empty($machine_trash_can[$v['machine_id']]) && $machine_trash_can[$v['machine_id']] = [];
            array_push($machine_trash_can[$v['machine_id']], $v['category']);
        }



        echo "<pre>";
        print_r($machine_trash_can);exit;
        return $this->render('flush');
    }
}