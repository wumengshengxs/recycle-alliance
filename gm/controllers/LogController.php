<?php


namespace gm\controllers;


use gm\models\SysAdminLog;
use Yii;
use yii\filters\AccessControl;

class LogController extends GController
{

    /**
     * {@inheritdoc}
     */
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
            ]
        ];
    }

    /**
     * 日志页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 日志数据
     */
    public function actionAjaxIndex()
    {
        list($start, $length) = $this->getOffset();
        $date_time = yii::$app->request->get('date_time');
        $where = [];
        $andWhere = [];
        $this->user_admin && $where['agent'] = $this->agent_id;

        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $andWhere = ['between', 'create_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        $log = SysAdminLog::find()
            ->select('id,agent,username,mark,create_time,description')
            ->where($where)
            ->andWhere($andWhere)
            ->offset($start)
            ->limit($length)
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $total = SysAdminLog::find()
            ->where($where)
            ->andWhere($andWhere)
            ->count();

        $data = [];
        foreach ($log as $value){
            array_push($data, array_values($value));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];

        die(json_encode($data_source));
    }




}