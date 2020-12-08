<?php

namespace gm\controllers;

use common\models\AdminLog;
use common\models\Agent;
use common\models\Machine;
use common\models\Maintain;
use gm\models\Excel;
use gm\models\PHPEmail;
use gm\models\PositionVillage;
use gm\models\SrMachineMaintain;
use gm\models\SrMaintain;
use gm\models\SrRecycler;
use gm\models\SrRecyclingHistoryChild;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrTrashCan;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class MachineController extends GController
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
     * 机器列表
     */
    public function actionList(){
        $province = $this->actionRegion_tx();
        $agent = Agent::findOne($this->user['id']);

        //小区
        $Village = PositionVillage::find()
            ->select('p_id,province_name, village_name, county_name,street_name, town_name')
            ->where(['agent'=>$this->agent_id,'del_flag' => 0])
            ->orderBy(['p_id' => SORT_DESC])
            ->asArray()->all();
        //回收员
        $recycler = SrRecycler::find()
            ->select('id,nick_name,phone_num')
            ->where(['del_flag'=>0,'recycler_status'=>1,'agent'=>$this->agent_id])
            ->asArray()
            ->all();
        return $this->render('list', [
            'province' => json_decode($province, 1),
            'agent' => $agent->getAttributes(),
            'village' => $Village,
            'recycler' => $recycler
        ]);
    }


    /**
     * 机器列表
     */
    public function actionAjax_list()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取入参与查询条件
        $province = yii::$app->request->get('province');
        $city = yii::$app->request->get('city');
        $district = yii::$app->request->get('district');
        $active = yii::$app->request->get('active');
        $status = yii::$app->request->get('status');
        $company_name = yii::$app->request->get('company_name');
        $company_agent = yii::$app->request->get('company_agent');
        $village_id = yii::$app->request->get('village_id');
        $recycler_id = yii::$app->request->get('recycler_id');
        $agent_admin = Agent::findOne($this->user['id']);

        $where = $andWhere = $agentWhere = [];
        $this->user_admin && $where['m.agent'] = $this->agent_id;
        empty($province) || $where['m.province_name'] = $province;
        empty($city) || $where['m.city_name'] = $city;
        empty($district) || $where['m.county_name'] = $district;
        empty($village_id) || $where['m.position_village_id'] = $village_id;
        empty($recycler_id) || $where['r.recycler_id'] = $recycler_id;
        $active == '' || $where['m.machine_activation_status'] = $active;
        $status == '' || $where['m.machine_status'] = $status;
        $company_name == '' || $andWhere = ['LIKE', 'm.community_name', $company_name];
        $company_agent == '' || $agentWhere = ['or',['LIKE', 'company_name', $company_agent],['LIKE', 'mobile', $company_agent]];

        //获取机器设备信息
        $res = SrRecyclingMachine::find()
            ->select('m.*,r.recycler_id,w.maintain_id')
            ->alias('m')
            ->join('LEFT JOIN', 'sr_recycler_machine_rel as r', 'm.id = r.machine_id')
            ->join('LEFT JOIN', 'sr_maintain_machine_rel as w', 'm.id = w.machine_id')
             ->where($where)->andWhere($andWhere);
        if (!empty($company_agent)){
            $agent_id = Agent::find()
                ->select('id')
                ->where($agentWhere)->asArray()->all();
            $agent_id = array_column($agent_id,'id');
            $res->andWhere(['m.agent'=>$agent_id]);
        }

        $total = $res->count();

        $srRecyclingMachine = $res->offset($start)->limit($length)->asArray()->all();

        //查询联营方信息
        $agent_id = array_unique(array_column($srRecyclingMachine, 'agent'));
        $agent = Agent::find()
            ->where(['IN', 'id', $agent_id])->asArray()->all();
        $agent = array_column($agent, null, 'id');

        //获取维修员和清运人员
        //回收员
        $rid = array_column($srRecyclingMachine,'recycler_id');
        $recycler = SrRecycler::find()
            ->select('id,nick_name')
            ->where(['del_flag'=>0,'recycler_status'=>1,'id'=>$rid])
            ->asArray()
            ->all();
        $recycler = array_column($recycler,NULL,'id');

        //维修员
        $rid = array_column($srRecyclingMachine,'maintain_id');
        $maintain = SrMaintain::find()
            ->select('id,nick_name')
            ->where(['del_flag'=>0,'maintain_status'=>1,'id'=>$rid])
            ->asArray()
            ->all();

        $maintain = array_column($maintain,NULL,'id');
        //生成dataTable格式数据
        $data = [];
        foreach ($srRecyclingMachine as $v){
            $temp = [];
            $temp['id'] = '<label class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" value="'.$v['id'].'" /><span class="custom-control-label">'.$v['device_id'].'</span></label>';
            $temp['machine_activation_status'] = empty($v['machine_activation_status']) ? '未激活' : '已激活';
            $temp['region'] = $v['province_name'] . $v['city_name'];
            $temp['community_name'] = $v['community_name'];
            $temp['location'] = $v['location'];
            $v['machine_status'] == 0 && $temp['machine_status'] = '<span class="text-success">正常</span>';
            $v['machine_status'] == 1 && $temp['machine_status'] = '<span class="text-warning">维修中</span>';
            $v['machine_status'] == 2 && $temp['machine_status'] = '<span class="text-danger">停止运营</span>';
            if (!$agent_admin->admin){
                $temp['company_name'] = $agent[$v['agent']]['company_name'];
                $temp['mobile'] = $agent[$v['agent']]['mobile'];;
            }else{
                $temp['recycler_name'] = empty($recycler[$v['recycler_id']]['nick_name']) ? '暂无' : $recycler[$v['recycler_id']]['nick_name'];
                $temp['maintain_name'] = empty($maintain[$v['maintain_id']]['nick_name']) ? '暂无' : $maintain[$v['maintain_id']]['nick_name'];
            }

            $temp['version'] = $v['version'];
            $temp['opt'] = '<select onchange="option(this, ' . $v['id'] . ')" class="form-control">';
            $temp['opt'] .= '<option value="0">请选择</option>';
            $temp['opt'] .= '<option value="1">修改回收机信息</option>';
            $temp['opt'] .= '<option value="2">修改回收价格</option>';
//            $temp['opt'] .= '<option value="3">设置清运员</option>';
//            $temp['opt'] .= '<option value="4">设置维修员</option>';
            $temp['opt'] .= '<option value="5">查看回收机状态</option>';
            $temp['opt'] .= '</select>';

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
     * 获取设备信息
     */
    public function actionInfo(){
        $id = yii::$app->request->get('id');

        //设置查询条件
        $where = [];
        $this->user_admin && $where = ['agent' => $this->agent_id];
        empty($id) || $where['id'] = $id;

        //获取机器设备信息
        $srRecyclingMachine = SrRecyclingMachine::find()
            ->where($where)->asArray()->one();

        //获取联营方信息
        $agent = Agent::find()
            ->where(['id' => $srRecyclingMachine['agent']])->one();

        $data = [
            'agent' => $agent,
            'srRecyclingMachine' => $srRecyclingMachine
        ];
        return $this->renderPartial('info', $data);
    }

    /**
     * 保存设备信息
     */
    public function actionAjax_info(){
        $params = yii::$app->request->post();
        if(empty($params)){
            return json_encode(['res' => 0]);
        }

        //获取查询条件
        $where = [];
        $this->user_admin && $where = ['agent' => $this->agent_id];
        $where['id'] = $params['id'];

        //获取机器设备信息并更新数据
        $srRecyclingMachine = SrRecyclingMachine::find()
            ->where($where)->one();
        $srRecyclingMachine->setAttributes($params);
        $res = $srRecyclingMachine->save();

        return json_encode(['res' => $res]);
    }

    /**
     * 获取设备品类价格
     */
    public function actionPrice(){
        $id = yii::$app->request->get('id');
        if(empty($id)){
            return;
        }
        $id = explode(',', $id);

        //设置查询条件
        $where = [];
        empty($id) || $where = ['IN', 'machine_id', $id];

        //获取机器设备信息
        $srTrashCan = SrTrashCan::find()
            ->where($where)
            ->groupBy('category')->asArray()->all();

        $data = [
            'srTrashCan' => $srTrashCan,
            'id' => implode(',', $id)
        ];
        return $this->renderPartial('price', $data);
    }

    /**
     * 批量设置品类价格兼容单个设置品类价格
     */
    public function actionAjax_price(){
        $params = yii::$app->request->post();
        //machine_id转换为数组
        $id = explode(',', $params['id']);
        //批量操作
        $res = SrTrashCan::updateAll([$params['type'] => $params['price']], ['AND', ['category' => $params['category']], ['IN', 'machine_id', $id]]);
        return json_encode(['res' => $res]);
    }

    /**
     * 查看回收机状态
     */
    public function actionStatus(){
        $id = yii::$app->request->get('id');
        //组装查询条件
        $where = [];
        empty($id) || $where['machine_id'] = $id;
        //查询设备的箱体信息
        $srTrashCan = SrTrashCan::find()
            ->where($where)->andWhere(['del_flag'=>0])->asArray()->all();

        //查询回收记录
        $history = SrRecyclingHistoryChild::find()
            ->select('category,sum(recycling_amount) as recycling_amount,sum(recycling_pay) as recycling_pay')
            ->where($where)
            ->andWhere(['del_flag'=>0])
            ->groupBy('category')
            ->asArray()
            ->all();

        $category = SrRubbishCategory::find()
            ->select('id,category_name,category_img_url')
            ->andWhere(['del_flag'=>0])
            ->asArray()
            ->all();

        $category = array_column($category,NULL,'id');

        foreach ($history as &$value){
            $value['can_name'] = $category[$value['category']]['category_name'];
        }

        return $this->renderPartial('status', ['srTrashCan' =>  $srTrashCan,'history' => $history]);
    }

    public function actionRestoration()
    {
        $id = yii::$app->request->post('id');

        $res = SrTrashCan::updateAll(['quantity'=>0,'weight'=>0,'count'=>0,'can_full'=>0],['id'=>$id]);
        if ($res){
            return json_encode(['status'=>200,'msg'=>'复位成功']);
        }
        return json_encode(['status'=>100,'msg'=>'复位失败']);
    }

    /**
     * 设备维修管理
     */
    public function actionMaintainList()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //小区
        $machine = Machine::getClass()->getMachineList($where);
        //维修员
        $where['maintain_status'] = 1;
        $maintain = Maintain::getClass()->getMaintainList($where);

        return $this->render('maintain-list',['maintain' => $maintain,'machine'=>$machine]);
    }

    /**
     * 设备维修管理列表
     */
    public function actionAjaxMaintainList()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $maintain_id = yii::$app->request->get('maintain_id');
        $machine_id = yii::$app->request->get('machine_id');
        $status = yii::$app->request->get('status');
        $type = yii::$app->request->get('type');
        $date_time = yii::$app->request->get('date_time');
        $where = [];
        $andWhere = [];
        $dateWhere = [];
        $this->user_admin && $andWhere['agent'] = $this->agent_id;
        empty($maintain_id) || $andWhere['maintain_id'] = $maintain_id;
        empty($machine_id) || $andWhere['machine_id'] = $machine_id;
        empty($status) || $andWhere['status'] = $status;
        empty($type) || $andWhere['type'] = $type;
        if (!empty($date_time)){
            list($start, $end) = explode(' - ',$date_time);
            $dateWhere = ['between','create_time',$start.' 00:00:00',$end.' 23:59:59'];
        }
        //查询投递数据列表
        $MachineMaintain = SrMachineMaintain::find()
            ->select('id,agent, machine_id, maintain_id,save_id,status, cause, create_time,end_time,type')
            ->andWhere($andWhere)
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->andWhere(['del_flag' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->offset($start)
            ->limit($length)->asArray()->all();
        //获取记录总数
        $total = SrMachineMaintain::find()
            ->andWhere($where)
            ->andWhere($andWhere)
            ->andWhere($dateWhere)
            ->andWhere(['del_flag' => 0])
            ->count();

        //机器id
        $machine_ids = array_column($MachineMaintain,'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_ids]);
        $machine = array_column($machine,NULL,'id');
        //维修员
        $maintain_ids = array_column($MachineMaintain,'maintain_id');
        $maintain =  Maintain::getClass()->getMaintainList(['id'=>$maintain_ids]);
        $maintain = array_column($maintain,NULL,'id');

        //生成dataTable格式数据
        $data = [];
        $status = Yii::$app->params['MaintainStatus'];
        $type = Yii::$app->params['MaintainType'];
        foreach ($MachineMaintain as $v) {
            $temp = [];
            $temp['device_id'] = $machine[$v['machine_id']]['device_id'];
            $temp['community_name'] = $machine[$v['machine_id']]['community_name'];
            $temp['nick_name'] = $maintain[$v['maintain_id']]['nick_name'];
            $temp['save_name'] = empty($maintain[$v['save_id']]['nick_name']) ? '暂无' : $maintain[$v['save_id']]['nick_name'];
            $temp['type'] = $type[$v['type']];
            $temp['cause'] = $v['cause'];
            $temp['status'] = $status[$v['status']];
            $temp['create_time'] = $v['create_time'];
            $temp['end_time'] = $v['end_time'];
            if ($v['status'] == 1){
                $temp['end_time'] = '未完成';
            }
            if ($v['status'] == 1){
                $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$v['id'].')">编辑</a>';
            }else{
                $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$v['id'].')">查看详情</a>';
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
        die(json_encode($data_source));
    }

    /**
     * excel
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function actionGetMaintainExcel()
    {
        $maintain_id = yii::$app->request->get('maintain_id');
        $machine_id = yii::$app->request->get('machine_id');
        $status = yii::$app->request->get('status');
        $type = yii::$app->request->get('type');
        $date_time = yii::$app->request->get('date_time');

        $where = [];
        $andWhere = [];
        $dateWhere = [];
        $this->user_admin && $where['agent'] = $this->agent_id;
        empty($maintain_id) || $andWhere['maintain_id'] = $maintain_id;
        empty($machine_id) || $andWhere['machine_id'] = $machine_id;
        empty($status) || $andWhere['status'] = $status;
        empty($type) || $andWhere['type'] = $type;
        //默认导出当月数据
        if (!empty($date_time)){
            list($start, $end) = explode(' - ',$date_time);
            $dateWhere = ['between','create_time',$start.' 00:00:00',$end.' 23:59:59'];
        }
        if (empty($andWhere)){
            $defaultDate = date('Y-m-d',strtotime(date('Y-m')));
            $dateWhere = ['between','create_time',$defaultDate.' 00:00:00', date('Y-m-d').' 23:59:59'];
        }
        //查询投递数据列表
        $MachineMaintain = SrMachineMaintain::find()
            ->select('id,agent, machine_id, maintain_id,save_id,admin_mark,maintain_mark,status, cause, create_time,end_time,type')
            ->andWhere($andWhere)
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->andWhere(['del_flag' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()->all();

        //机器id
        $machine_ids = array_column($MachineMaintain,'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_ids]);
        $machine = array_column($machine,NULL,'id');
        //维修员
        $maintain_ids = array_column($MachineMaintain,'maintain_id');
        $maintain =  Maintain::getClass()->getMaintainList(['id'=>$maintain_ids]);
        $maintain = array_column($maintain,NULL,'id');

        //生成dataTable格式数据
        $status = Yii::$app->params['MaintainStatus'];
        $type = Yii::$app->params['MaintainType'];
        $data = [];
        foreach ($MachineMaintain as $k=>$v) {
            $data[$k]['device_id'] = $machine[$v['machine_id']]['device_id'];
            $data[$k]['community_name'] = $machine[$v['machine_id']]['community_name'];
            $data[$k]['street_name'] = $machine[$v['machine_id']]['street_name'];
            $data[$k]['nick_name'] = $maintain[$v['maintain_id']]['nick_name'];
            $data[$k]['save_name'] = empty($maintain[$v['save_id']]['nick_name']) ? '暂无' : $maintain[$v['save_id']]['nick_name'];
            $data[$k]['type'] = $type[$v['type']];
            $data[$k]['cause'] = $v['cause'];
            $data[$k]['admin_mark'] = $v['admin_mark'];
            $data[$k]['maintain_mark'] = $v['maintain_mark'];
            $data[$k]['status'] = $status[$v['status']];
            $data[$k]['create_time'] = $v['create_time'];
            $data[$k]['end_time'] = $v['end_time'];
            if ($v['status'] == 1){
                $data[$k]['end_time'] = '未完成';
            }
        }
        $name = '维修记录表' . time();
        $key = ['device_id', 'street_name',  'community_name', 'nick_name',
            'save_name', 'type', 'cause', 'admin_mark', 'maintain_mark','status','create_time','end_time'];
        $head = ['机器ID', '回收机所属街道',  '回收机所在小区', '所属维修员', '完成维修员', '故障类型', '维修原因', '管理员备注', '维修员备注','维修状态','创建时间','完成时间'];

        Excel::Export($name, $head, $data, $key);
    }

    /**
     * 设备维修管理添加
     * @return string
     */
    public function actionMaintainAdd()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $time = date('Y-m-d H:i:s');
            $data['SrMachineMaintain'] = [
                'agent' => $this->user['id'],
                'machine_id' => $param['machine_id'],
                'maintain_id' => $param['maintain_id'],
                'type' => $param['type'],
                'status' => 1,
                'cause' => $param['cause'],
                'admin_mark' => $param['admin_mark'],
                'create_date' => $time,
                'update_date' => $time,
                'del_flag' => 0,
            ];
            $Maintain = new SrMachineMaintain();
            //添加记录到主表
            if ($Maintain->load($data) && $Maintain->save()){

                $info = '运营商: '.$this->user['company_name'].'添加维修记录,维修原因'.$param['cause'];
                AdminLog::getClass()->addLog('添加维修记录',$info);

                return $this->AjaxResult(SUCCESS,'添加成功');
            }
            return $this->AjaxResult(FAILD,'添加失败',$Maintain->getErrors());
        }else{
            $where['m.del_flag'] = 0;
            $this->user_admin && $where['agent'] = $this->agent_id;
            $machine = Machine::getClass()->getMaintainMachineRel($where);

            return $this->renderPartial('maintain-add', [
                'machine' => $machine,
            ]);
        }
    }

    /**
     * 设备维修管理编辑
     * @return string
     */
    public function actionMaintainEdit()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $model = new SrMachineMaintain();
            $time = date('Y-m-d H:i:s');
            $Maintain = $model::findOne($param['id']);

            if (empty($Maintain)){
                return $this->AjaxResult(FAILD,'数据异常');
            }
            $param['update_time'] = $time;

            if ($param['status'] == 2){
                $param['save_id'] = $param['maintain_id'];
                $param['end_time'] = $time;
            }
            $data['SrMachineMaintain'] = $param;

            //添加记录到主表
            if ($Maintain->load($data) && $Maintain->save()){

                $info = '运营商: '.$this->user['company_name'].'修改维修记录,维修原因'.$param['cause'];
                AdminLog::getClass()->addLog('修改维修记录',$info);

                return $this->AjaxResult(SUCCESS,'修改成功');
            }

            return $this->AjaxResult(FAILD,'修改失败',$Maintain->getErrors());
        }else{
            $where['m.del_flag'] = 0;
            $andWhere['del_flag'] = 0;
            $andWhere['maintain_status'] = 1;
            $id = Yii::$app->request->get('id');

            $this->user_admin  && $andWhere['agent'] = $this->agent_id;
            $this->user_admin  && $where['agent'] = $this->agent_id;
            $info = Maintain::getClass()->getMaintainMachine(['id'=>$id,'del_flag'=>0]);
            $machine = Machine::getClass()->getMaintainMachineRel($where);
            $maintain = Maintain::getClass()->getMaintainList($andWhere);

            return $this->renderPartial('maintain-edit', [
                'machine' => $machine,
                'info' => $info,
                'maintain' => $maintain
            ]);
        }
    }

    /**
     *
     * 获取维修员列表
     */
    public function actionGetMaintainList()
    {
        $id = Yii::$app->request->post('id');
        $where['del_flag'] = 0;
        $where['maintain_status'] = 1;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $maintain = SrMaintain::find()
            ->select('id,nick_name,type')
            ->asArray()
            ->where($where)
            ->orderBy('type desc')
            ->all();

       $html = '';
        foreach ($maintain as $value){
            $html .= '<option  value="'.$value['id'].'"';
            if ($value['id'] == $id){
                $html .= ' selected';
            }
            $html .= ">".$value['nick_name']."</option>";
        }

        die(json_encode($html));
    }

    /**
     * 发送邮件
     */
    public function actionAddEmail(){
        $PHPEmail = new PHPEmail();
        $title = '今日机器维修记录';
        //发送h5 页面
        $body = '<!DOCTYPE html><html><body><table style="border-collapse: collapse;width: 100%;">
        <tr><th style="border: 1px solid #936943;height: 40px;text-align: center;" colspan="5">'.$title.'</th></tr>';

        //标题
        $body .= ' <tr>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">机器ID</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">回收机所在小区</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">故障类型</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">所属维修员</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">完成维修员</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">维修原因</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">维修状态</th>
            <th style="border: 1px solid #936943;height: 40px;text-align: center;">创建时间</th>
        </tr>';
        //内容
//        $startTime = strtotime(date("Y-m-d",strtotime("-1 day")));
//        $start = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime("-1 day"))));//昨日0点
//        $end   = date("Y-m-d H:i:s",$startTime + (60 * 60 * 24) -1);
        $startTime = strtotime(date("Y-m-d"));
        $start = date("Y-m-d H:i:s",strtotime(date("Y-m-d")));//0点
        $end   = date("Y-m-d H:i:s",time());

        $MachineMaintain = SrMachineMaintain::find()
            ->select('id, machine_id, maintain_id,save_id, cause,status,type')
            ->andWhere(['BETWEEN','create_time',$start,$end])
            ->andWhere(['del_flag' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()->all();
        //机器id
        $machine_ids = array_column($MachineMaintain,'machine_id');

        $machine = Machine::getClass()->getMachineList(['id'=>$machine_ids]);
        $machine = array_column($machine,NULL,'id');
        //维修员
        $maintain_ids = array_column($MachineMaintain,'maintain_id');
        $maintain = Maintain::getClass()->getMaintainList(['id'=>$maintain_ids]);
        $maintain = array_column($maintain,NULL,'id');
        $status = Yii::$app->params['MaintainStatus'];
        $type = Yii::$app->params['MaintainType'];
        foreach ($MachineMaintain as $value){
            $save_name = empty($maintain[$value['save_id']]['nick_name']) ? '暂无' : $maintain[$value['save_id']]['nick_name'];
            $body .= '<tr>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$machine[$value['machine_id']]['device_id'].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$machine[$value['machine_id']]['community_name'].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$type[$value['type']].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$maintain[$value['maintain_id']]['nick_name'].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$save_name.'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$value['cause'].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$status[$value['status']].'</td>
            <td style="border: 1px solid #936943;height: 40px;text-align: center;">'.$value['create_time'].'</td>
        </tr>';
        }
        $body .= '   </table></body></html>';
        if (!empty($MachineMaintain)){
            $email = $PHPEmail->email($title,$body);
            var_dump($email);die();
        }
    }


}
