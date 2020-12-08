<?php


namespace gm\controllers;

use common\models\Agent;
use gm\models\SrAgentContract;
use gm\models\SrAgentTradeHistory;
use gm\models\SrFinanceBank;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrTrashCan;
use yii;
use yii\filters\AccessControl;

class ManagerController extends GController
{
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
     * 联营方管理列表
     */
    public function actionList(){
        $province = $this->actionRegion_tx();
        return $this->render('list', ['province' => json_decode($province, 1)]);
    }

    /**
     * 联营方管理列表数据
     */
    public function actionAjax_list(){
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取入参与查询条件
        $province = yii::$app->request->get('province');
        $city = yii::$app->request->get('city');
        $district = yii::$app->request->get('district');
        $type = yii::$app->request->get('type');
        $company_name = yii::$app->request->get('company_name');
        $where = $andWhere = [];

        $this->user_admin && $where['id'] = $this->agent_id;
        empty($province) || $where['province_code'] = $province;
        empty($city) || $where['city_code'] = $city;
        empty($district) || $where['district_code'] = $district;
        $type == '' || $where['admin'] = $type;
        $company_name == '' || $andWhere = ['LIKE', 'company_name', $company_name];


        $agent = Agent::find()
            ->where($where)
            ->andWhere($andWhere)
            ->offset($offset)->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //获取记录总数
        $total = Agent::find()
            ->where($where)
            ->andWhere($andWhere)
            ->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($agent as $v) {
            //对获取到的uuid进行截断处理
            $temp['uuid'] = explode('-', $v['uuid']);
            $temp['uuid'] = end($temp['uuid']);

            $temp['create_time'] = $v['create_time'];
            $temp['admin'] = empty($v['admin']) ? '自营' : '联营';
            $temp['company_name'] = $v['company_name'];
            $temp['contact_address'] = $v['contact_address'];
            $temp['corporation'] = $v['corporation'];
            $temp['id_card'] = $v['id_card'];
            $temp['mobile'] = $v['mobile'];
            $temp['security'] = $v['security'];
            $temp['machine_num'] = $v['machine_num'];

            $temp['opt'] = '<a href="/manager/edit/?id='. $v['id'] .'">修改信息</a>';
            $temp['opt'] .= ' | <a href="/manager/sn_list/?id='. $v['id'] .'">激活码管理</a> ';
            $temp['opt'] .= ' | <a href="javascript:void(0)">重置密码</a>';

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
     * 创建联营账号页面
     */
    public function actionAdd(){
        $province = $this->actionRegion_tx();
        return $this->render('add', ['province' => json_decode($province, 1)]);
    }

    /**
     * 创建联营账号逻辑
     */
    public function actionAjax_add(){
        //获取入参数据并过滤sql注入
        $params = yii::$app->request->post();
        foreach ($params as & $v){
            $v = addslashes($v);
            unset($v);
        }

        //保存联营方信息
        $agent = new Agent();
        $params['security'] = get_rand_str(10);
        $params['uuid'] = get_guid();
        $agent->setAttributes($params);
        $agent->setPassword($params['security']);
        $res = $agent->save();
        if(!$res){
            return json_encode(['res' => $res]);
        }
        $params['agent'] = $agent->getAttribute('id');

        //创建设备id
        $recyclingMachine = new SrRecyclingMachine();
        $device_arr = $machine_id_arr = [];
        for($i = 1; $i <= $params['machine_num']; $i++){
            //获取当前device_id的最大值，并进行追加
            $srRecyclingMachine = SrRecyclingMachine::find()
                ->select('MAX(device_id) as device_id')->asArray()->one();
            $device_id = bcadd($srRecyclingMachine['device_id'], 1);

            //记录创建device_id
            array_push($device_arr, $device_id);

            //保存当前的设备信息
            $_recyclingMachine = clone $recyclingMachine;
            $_recyclingMachine->setAttributes([
                'device_id' => $device_id,
                'agent' => $params['agent'],
                'icc_id' => '',
                'longitude' => 0,
                'latitude' => 0,
                'community_name' => '',
                'create_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
                'location' => '',
                'del_flag' => '0',
                'divece_code' => 'squirrel',
                'county_name' => $params['district'],
                'city_name' => $params['city'],
                'province_name' => $params['province'],
            ]);
            $_recyclingMachine->save();
            array_push($machine_id_arr, $_recyclingMachine->getAttribute('id'));
        }

        //合同编号与设备号激活码绑定
        $srAgentContract = new SrAgentContract();
        foreach ($device_arr as $v){
            $_srAgentContract = clone $srAgentContract;
            $_srAgentContract->setAttributes($params);
            $_srAgentContract->setAttribute('device_id', $v);
            $_srAgentContract->save();
        }

        //创建箱体信息
        $srTrashCan = new SrTrashCan();
        foreach ($machine_id_arr as $id){
            //查询品类表获取品类属性
            $sr_rubbish_category = SrRubbishCategory::find()
                ->where(['category_type' => 0])->asArray()->all();
            foreach ($sr_rubbish_category as $v){
                $_srTrashCan = clone $srTrashCan;
                $_srTrashCan->setAttributes([
                    'machine_id' => $id,
                    'can_name' => $v['category_name'],
                    'max_quantity' => 100,
                    'max_weight' => 80,
                    'max_count' => 250,
                    'max_temperature' => 0,
                    'temperature' => 0,
                    'quantity' => 1,
                    'can_num' => $v['can_num'],
                    'weight' => 0,
                    'count' => 0,
                    'category' => $v['id'],
                    'can_full' => 0,
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'recycle_price' => $v['recycle_price'],
                    'sale_price' => $v['sale_price'],
                    'rubbish_unit' => $v['rubbish_unit'],
                    'price_unit' => $v['price_unit'],
                    'activation_status' => '0',
                    'del_flag' => '0',
                ]);
                $_srTrashCan->save();
            }
        }

        return json_encode(['res' => $res]);
    }

    /**
     * 编辑联营方信息页面
     */
    public function actionEdit(){
        $agent_id = yii::$app->request->get('id');
        $agent = Agent::findOne($agent_id);
        if(empty($agent)){
            $this->redirect(['/manager/list']);
            return;
        }
        return $this->render('edit', ['agent' => $agent->getAttributes()]);
    }

    /**
     * 编辑联营方信息逻辑
     */
    public function actionAjax_edit(){
        $params = yii::$app->request->post();
        foreach ($params as & $v){
            $v = addslashes($v);
        }
        $agent = Agent::findOne($params['agent_id']);
        $agent->setAttributes($params);
        $res = $agent->save();
        return json_encode(['res' => $res]);
    }

    /**
     * 校验表单数据
     */
    public function actionAjax_validate(){
        //获取入参数据
        $params = yii::$app->request->get();

        //校验用户名
        if(!empty($params['username'])){
            if(!is_mobile_num($params['username'])){
                return json_encode(['status' => false, 'msg' => '登录名必须是手机号']);
            }
            $count = Agent::find()->where(['username' => $params['username']])->count();
            if($count > 0){
                return json_encode(['status' => false, 'msg' => '登录名已经存在了']);
            }
        }

        //校验合同编号
        if(!empty($params['contract'])){
            $count = SrAgentContract::find()->where(['contract' => $params['contract']])->count();
            if($count > 0){
                return json_encode(['status' => false, 'msg' => '合同编号已存在']);
            }
        }

        return json_encode(['status' => true, 'msg' => '']);
    }

    /**
     * 激活码管理列表页面
     */
    public function actionSn_list(){
        $agent_id = yii::$app->request->get('id');
        $agent = Agent::findOne($agent_id);
        if(empty($agent)){
            $this->redirect(['/manager/list']);
            return;
        }
        return $this->render('sn_list', ['agent' => $agent->getAttributes()]);
    }

    /**
     * 激活码管理列表数据
     */
    public function actionAjax_sn_list(){
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //按agent_id获取激活码数据
        $agent_id = yii::$app->request->get('agent_id');
        $srAgentContract = SrAgentContract::find()
            ->where(['agent' => $agent_id])
            ->orderBy(['id' => SORT_DESC])
            ->offset($offset)->limit($length)
            ->asArray()->all();

        //获取agent所属激活码的记录数
        $total = SrAgentContract::find()
            ->where(['agent' => $agent_id])->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srAgentContract as $v){
            $temp['device_id'] = $v['device_id'];
            $temp['create_time'] = $v['create_time'];
            $temp['active_time'] = $v['active_time'];
            $temp['status'] = empty($v['status']) ? '未激活' : '已激活';
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
     * 激活码创建页面
     */
    public function actionSn_add(){
        $agent_id = yii::$app->request->get('id');
        return $this->renderPartial('sn_add', ['agent_id' => $agent_id]);
    }

    /**
     * 激活码创建逻辑
     */
    public function actionAjax_sn_add(){
        //获取入参数据并过滤sql注入
        $params = yii::$app->request->post();
        foreach ($params as & $v){
            $v = addslashes($v);
            unset($v);
        }

        //增加设备数量
        $agent = Agent::findOne($params['agent']);
        $machine_num = $agent->getAttribute('machine_num') + $params['machine_num'];
        $agent->setAttribute('machine_num', $machine_num);
        $agent->save();

        //创建设备id
        $recyclingMachine = new SrRecyclingMachine();
        $device_arr = $machine_id_arr = [];
        for($i = 1; $i <= $params['machine_num']; $i++){
            //获取当前device_id的最大值，并进行追加
            $srRecyclingMachine = SrRecyclingMachine::find()
                ->select('MAX(device_id) as device_id')->asArray()->one();
            $device_id = bcadd($srRecyclingMachine['device_id'], 1);

            //记录创建device_id
            array_push($device_arr, $device_id);

            //保存当前的设备信息
            $_recyclingMachine = clone $recyclingMachine;
            $_recyclingMachine->setAttributes([
                'device_id' => $device_id,
                'agent' => $params['agent'],
                'icc_id' => '',
                'longitude' => 0,
                'latitude' => 0,
                'community_name' => '',
                'create_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
                'location' => '',
                'del_flag' => '0',
                'divece_code' => 'squirrel',
                'county_name' => $agent->district,
                'city_name' => $agent->city,
                'province_name' => $agent->province,
            ]);
            $_recyclingMachine->save();
            array_push($machine_id_arr, $_recyclingMachine->getAttribute('id'));
        }

        //合同编号与设备号激活码绑定
        $srAgentContract = new SrAgentContract();
        foreach ($device_arr as $v){
            $_srAgentContract = clone $srAgentContract;
            $_srAgentContract->setAttributes($params);
            $_srAgentContract->setAttribute('device_id', $v);
            $_srAgentContract->save();
        }

        //创建箱体信息
        $srTrashCan = new SrTrashCan();
        foreach ($machine_id_arr as $id){
            //查询品类表获取品类属性
            $sr_rubbish_category = SrRubbishCategory::find()
                ->where(['category_type' => 0])->asArray()->all();
            foreach ($sr_rubbish_category as $v){
                $_srTrashCan = clone $srTrashCan;
                $_srTrashCan->setAttributes([
                    'machine_id' => $id,
                    'can_name' => $v['category_name'],
                    'max_quantity' => 100,
                    'max_weight' => 80,
                    'max_count' => 250,
                    'max_temperature' => 0,
                    'temperature' => 0,
                    'quantity' => 1,
                    'can_num' => $v['can_num'],
                    'weight' => 0,
                    'count' => 0,
                    'category' => $v['id'],
                    'can_full' => 0,
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'recycle_price' => $v['recycle_price'],
                    'sale_price' => $v['sale_price'],
                    'rubbish_unit' => $v['rubbish_unit'],
                    'price_unit' => $v['price_unit'],
                    'activation_status' => '0',
                    'del_flag' => '0',
                ]);
                $_srTrashCan->save();
            }
        }

        return json_encode(['res' => true]);
    }

    /**
     * 联营方环保金列表
     */
    public function actionRecharge_list(){
        return $this->render('recharge_list');
    }

    /**
     * 联营方环保金数据
     */
    public function actionAjax_recharge_list(){
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];
        //获取状态
        $status = yii::$app->request->get('status');
        $company_name = yii::$app->request->get('company_name');

        //组装条件
        $where = $andWhere = [];
        $this->user_admin && $where['a.agent'] = $this->agent_id;
        $where['a.type'] = 1;
        $status != '' && $where['a.status'] = $status;
        $company_name != '' && $andWhere = ['or',['LIKE', 'b.company_name', $company_name],['LIKE', 'b.mobile', $company_name]];

        //查询数据
        $res = SrAgentTradeHistory::find()
            ->select(['a.id', 'a.create_time', 'b.uuid', 'b.company_name', 'b.mobile', 'a.bank_name', 'a.amount', 'b.balance', 'a.status', 'a.verify', 'a.check'])
            ->from(SrAgentTradeHistory::tableName() . ' AS a')
            ->leftJoin(Agent::tableName() . ' AS b', 'a.agent = b.id')
            ->where($where)->andWhere($andWhere);
        $srAgentTradeHistory = $res->orderBy(['a.id' => SORT_DESC])
            ->offset($offset)->limit($length)
            ->asArray()->all();
        $total = $res->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srAgentTradeHistory as $v){
            $temp['create_time'] = $v['create_time'];
            $temp['uuid'] = explode('-', $v['uuid']);
            $temp['uuid'] = end($temp['uuid']);
            $temp['company_name'] = $v['company_name'];
            $temp['mobile'] = $v['mobile'];
            $temp['bank_name'] = $v['bank_name'];
            $temp['amount'] = $v['amount'];
            $temp['balance'] = $v['balance'];
            $status = '';
            $v['status'] == '0' && $status = '<span class="text-primary">待初审</span>';
            $v['status'] == '1' && $status = '<span class="text-warning">待复审</span>';
            $v['status'] == '2' && $status = '<span class="text-danger">已驳回</span>';
            $v['status'] == '3' && $status = '<span class="text-success">已通过</span>';
            $temp['status'] = $status;
            //已审核，已拒绝，当前账号已过审
            if(in_array($v['status'], [2, 3]) || ($v['status'] == 1 && $v['verify'] == $this->user['id']) || $this->user_admin){
                $temp['opt'] = '<a onclick="evidence(' . $v['id'] . ')" href="javascript:void(0)">查看到账回执</a>';
            }
            //当前账号未过审
            else if($v['status'] == 1 && $v['verify'] != $this->user['id']){
                $temp['opt'] = '<a onclick="chk(' . $v['id'] . ')" href="javascript:void(0)">到账复审</a>';
            }
            //未审核
            else if($v['status'] == 0){
                $temp['opt'] = '<a onclick="verify(' . $v['id'] . ')" href="javascript:void(0)">到账初审</a>';
            }
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
     * 环保金转账凭证查看页面
     */
    public function actionEvidence(){
        //获取账户流水id
        $id = yii::$app->request->get('id');

        //获取汇款记录
        $res = SrAgentTradeHistory::findOne($id);
        if(empty($res)){
            return;
        }
        $srAgentTradeHistory = $res->getAttributes();

        //获取联营方信息
        $res = Agent::findOne($srAgentTradeHistory['agent']);
        if(empty($res)){
            return;
        }
        $agent = $res->getAttributes();

        $data = [
            'agent' => $agent,
            'srAgentTradeHistory' => $srAgentTradeHistory
        ];

        return $this->renderPartial('evidence', $data);
    }

    /**
     * 环保金审核页面
     */
    public function actionVerify(){
        //获取账户流水id
        $id = yii::$app->request->get('id');

        //获取汇款记录
        $res = SrAgentTradeHistory::findOne($id);
        if(empty($res)){
            return;
        }
        $srAgentTradeHistory = $res->getAttributes();

        //获取联营方信息
        $res = Agent::findOne($srAgentTradeHistory['agent']);
        if(empty($res)){
            return;
        }
        $agent = $res->getAttributes();

        $data = [
            'agent' => $agent,
            'srAgentTradeHistory' => $srAgentTradeHistory
        ];

        return $this->renderPartial('verify', $data);
    }

    /**
     * 环保金审核逻辑
     */
    public function actionAjax_verify(){
        //获取入参
        $id = yii::$app->request->post('id');
        $amount = yii::$app->request->post('amount');
        $status = yii::$app->request->post('status');

        //查找id对应的记录
        $srAgentTradeHistory = SrAgentTradeHistory::findOne($id);
        if(empty($srAgentTradeHistory)){
            return json_encode(['res' => 0]);
        }
        $srAgentTradeHistory->setAttributes([
            'status' => $status,
            'verify' => $this->user['id'],
            'verify_time' => date('Y-m-d H:i:s'),
        ]);
        $res = $srAgentTradeHistory->save();
        return json_encode(['res' => $res]);
    }

    /**
     * 环保金复核页面
     */
    public function actionCheck(){
        //获取账户流水id
        $id = yii::$app->request->get('id');

        //获取汇款记录
        $res = SrAgentTradeHistory::findOne($id);
        if(empty($res)){
            return;
        }
        $srAgentTradeHistory = $res->getAttributes();

        //获取联营方信息
        $res = Agent::findOne($srAgentTradeHistory['agent']);
        if(empty($res)){
            return;
        }
        $agent = $res->getAttributes();

        $data = [
            'agent' => $agent,
            'srAgentTradeHistory' => $srAgentTradeHistory
        ];

        return $this->renderPartial('check', $data);
    }

    /**
     * 环保金复核逻辑
     */
    public function actionAjax_check(){
        //获取入参
        $id = yii::$app->request->post('id');
        $amount = yii::$app->request->post('amount');
        $status = yii::$app->request->post('status');

        //查找id对应的转账记录，并更新复核状态
        $srAgentTradeHistory = SrAgentTradeHistory::findOne($id);

        if(empty($srAgentTradeHistory)){
            return json_encode(['res' => 0]);
        }
        $srAgentTradeHistory->setAttributes([
            'status' => $status,
            'check' => $this->user['id'],
            'check_time' => date('Y-m-d H:i:s'),
        ]);
        $res = $srAgentTradeHistory->save();

        //复核过审，对用户环保金进行充值
        if($status == 3){
            $agent = Agent::findOne($srAgentTradeHistory->agent);
            if(empty($agent)){
                return json_encode(['res' => 0]);
            }
            $agent->setAttributes([
                'balance' => $agent->getAttribute('balance') + $amount,
                'last_recharge' => $amount,
                'last_recharge_time' => date('Y-m-d H:i:s')
            ]);
            $agent->save();
        }

        return json_encode(['res' => $res]);
    }

    /**
     * 财务管理页面
     */
    public function actionFinance(){
        //获取财务信息
        $srFinanceBank = SrFinanceBank::find()->asArray()->one();
        if(empty($srFinanceBank)){
            $srFinanceBank = [
                'id' => 0,
                'company_name' => '',
                'bank_account' => '',
                'bank_name' => '',
                'bank_number' => '',
            ];
        }
        return $this->render('finance', $srFinanceBank);
    }

    /**
     * 财务信息保存页面
     */
    public function actionAjax_finance(){
        $params = yii::$app->request->post();

        //对参数进行转义操作
        foreach ($params as & $v) {
            $v = addslashes($v);
        }

        //新增财务信息
        if(empty($params['finance_id'])){
            $srFinanceBank = new SrFinanceBank();
            $srFinanceBank->setAttributes($params);
        }
        //编辑财务信息
        else{
            $srFinanceBank = SrFinanceBank::findOne($params['finance_id']);
            $srFinanceBank->setAttributes($params);
        }

        //数据保存
        $res = $srFinanceBank->save();
        return json_encode(['res' => $res]);
    }
}