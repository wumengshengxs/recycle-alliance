<?php


namespace gm\controllers;


use common\models\Agent;
use gm\models\SrAgentTradeHistory;
use Yii;
use yii\filters\AccessControl;

class FinanceController extends GController{

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
     * 账务列表页面
     */
    public function actionList(){
        return $this->render('list');
    }

    /**
     * 账务列表数据
     */
    public function actionAjax_list(){
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取企业名称
        $company_name = yii::$app->request->get('company_name');

        //初始化查询条件
        $where = $andWhere = [];
        $this->user_admin && $where = ['id' => $this->agent_id];
        $company_name && $andWhere = ['LIKE', 'company_name', $company_name];
        //获取企业信息
        $res = Agent::find()
            ->where($where)->andWhere($andWhere);
        $agent = $res->offset($offset)->limit($length)->asArray()->all();
        $total = $res->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($agent as $v){
            $temp['uuid'] = explode('-', $v['uuid']);
            $temp['uuid'] = end($temp['uuid']);
            $temp['company_name'] = $v['company_name'];
            $temp['balance'] = $v['balance'];
            $temp['pay'] = $v['pay'];
            $temp['last_recharge_time'] = $v['last_recharge_time'];
            $temp['last_recharge'] = $v['last_recharge'];
            $temp['opt'] = '<a href="/finance/detail/?agent_id=' .$v['id']. '">查看环保金</a>';
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_data', ['data' => json_encode($data_source)]);
    }

    /**
     * 环保金明细页面
     */
    public function actionDetail(){
        $agent_id = yii::$app->request->get('agent_id');
        return $this->render('detail', ['agent_id' => $agent_id]);
    }

    /**
     * 环保金明细数据
     */
    public function actionAjax_detail(){
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        $agent_id = yii::$app->request->get('agent_id');

        //查找明细数据
        $where = ['agent' => $agent_id];
        $res = SrAgentTradeHistory::find()->where($where);
        $total = $res->count();

        $srAgentTradeHistory = $res
            ->offset($start)
            ->limit($length)
            ->asArray()->all();

        //生成dataTable格式数据
        $data = [];
        foreach ($srAgentTradeHistory as $v){
            $type = '支出';
            $content = '环保金支出';
            $amount = '<span class="text-success">' . $v['amount'] . '</span>';
            if($v['type'] == 1){
                $type = '收入';
                $content = '账户充值';
                $amount = '<span class="text-danger">' . $v['amount'] . '</span>';
            }
            $temp['type'] = $type;
            $temp['amount'] = $amount;
            $temp['content'] = $content;
            $temp['create_time'] = $v['create_time'];
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_data', ['data' => json_encode($data_source)]);
    }
}