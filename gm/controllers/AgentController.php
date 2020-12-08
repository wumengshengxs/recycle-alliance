<?php


namespace gm\controllers;

use gm\models\SrAgentTradeHistory;
use gm\models\SrRecyclingMachine;
use yii;
use yii\filters\AccessControl;

class AgentController extends GController{
    /**
     * AccessControl
     * 访问权限控制
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
            ],
        ];
    }

    /**
     * 用户中心
     */
    public function actionCenter(){
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $qurey = SrRecyclingMachine::find()
            ->asArray();
        $machine_num =  $qurey->where($where)->count();//全部机器
        $where['machine_activation_status'] = 1;
        $machine_activation_num =  $qurey->where($where)->count();//已激活机器
        $this->user['machine_num'] = $machine_num;
        $this->user['machine_activation_num'] = $machine_activation_num;
        return $this->render('center', $this->user);
    }

    /**
     * 企业充值
     */
    public function actionRecharge(){
        return $this->render('recharge', $this->user);
    }

    /**
     * 汇款通知
     */
    public function actionRemit(){
        return $this->render('remit', $this->user);
    }

    /**
     * 提交企业汇款信息
     */
    public function actionAjax_recharge(){
        $params = yii::$app->request->post();
        $params['type'] = 1;
        $srAgentTradeHistory = new SrAgentTradeHistory();
        $srAgentTradeHistory->setAttributes($params);
        $flag = $srAgentTradeHistory->validate();
        if(!$flag){
            return json_encode(['res' => 0]);
        }
        $res = $srAgentTradeHistory->save();
        return json_encode(['res' => $res]);
    }
}