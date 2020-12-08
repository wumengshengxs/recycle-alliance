<?php


namespace gm\controllers;


use common\models\AdminLog;
use common\models\Category;
use common\models\ExternalRecycler;
use common\models\ExternalRecyclers;
use common\models\Village;
use gm\models\Excel;
use gm\models\SrExternalRecycler;
use gm\models\SrExternalRecyclerHistory;
use gm\models\SrExternalRecyclers;
use gm\models\SrExternalRecyclersHistory;
use gm\models\SrExternalRecyclersImg;
use \Exception;
use yii\filters\AccessControl;
use Yii;

class ExternalController extends  GController
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
     * 商户页面
     */
    public function actionExternalRecyclerIndex()
    {
        return $this->render('external-recycler-index');
    }

    /**
     * 商户页面
     */
    public function actionAjaxExternalRecyclerIndex()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $like_Where = [];
        $nickname = Yii::$app->request->get('nick_name');
        $phone = Yii::$app->request->get('phone');
        empty($nickname) || $like_Where  = ['like', 'nick_name', $nickname];
        empty($phone) || $where['phone_num'] = $phone;

        $externalRecycler = SrExternalRecyclers::find()
            ->select('id,nick_name,phone_num,balance,type,status,create_date')
            ->andWhere($where)
            ->andWhere($like_Where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //获取记录总数
        $total = SrExternalRecyclers::find()
            ->andWhere($where)
            ->andWhere($like_Where)
            ->asArray()
            ->count();

        $rid = array_column($externalRecycler,'id');

        $History = SrExternalRecyclersHistory::find()
            ->select('sum(income_amount) as income_amount,external_recycler_id')
            ->where(['external_recycler_id'=>$rid,'income_direction'=>1])
            ->asArray()
            ->groupBy('external_recycler_id')
            ->all();

        $History = array_column($History,'income_amount','external_recycler_id');

        $type = Yii::$app->params['ExternalRecyclerType'];
        $data = [];
        foreach ($externalRecycler as $val){
            $temp = [];
            $temp['id'] = $val['id'];
            $temp['nick_name'] = $val['nick_name'];
            $temp['phone_num'] = $val['phone_num'];
            $temp['sum_balance'] = !empty($History[$val['id']]) ? $History[$val['id']] : "暂无充值记录";
            $temp['balance'] = $val['balance'];
            $temp['status'] = $type[$val['status']];
            $temp['create_date'] = $val['create_date'];
            $temp['opt'] =   $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$val['id'].')">编辑</a> 
             <a href="javascript:(0)" onclick="RecyclerList('.$val['id'].')">充值记录</a>';
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
     * 添加
     * @return false|string
     */
    public function actionExternalRecyclerAdd()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $time = date('Y-m-d H:i:s');
            //判断
            $Recyclers = ExternalRecyclers::getClass()->getExternalRecyclers(['phone_num'=>$param['phone_num']]);

            if (!empty($Recyclers)){
                return $this->AjaxResult(FAILD,'存在相同的手机号');
            }
            $data['SrExternalRecyclers'] = [
                'nick_name' => $param['nick_name'],
                'agent' =>$this->user['id'],
                'phone_num' => $param['phone_num'],
                'password'  => '',
                'status'    => $param['status'],
                'open_id'   => '',
                'balance'   => '0.00',
                'type'      => 0,
                'create_date' => $time,
                'update_date' => $time,
                'del_flag' => '0',
            ];
            $SrExternalRecyclers = new SrExternalRecyclers();

            //添加记录到主表
            if (!$SrExternalRecyclers->load($data) || !$SrExternalRecyclers->save()){
                return $this->AjaxResult(FAILD,'添加失败',$SrExternalRecyclers->getErrors());
            }

            $info = '运营商: '.$this->user['company_name'].'添加回收商'.$param['nick_name'];
            AdminLog::getClass()->addLog('添加回收商',$info);

            return $this->AjaxResult(SUCCESS,'添加成功');
        }else{

            return $this->renderPartial('external-recycler-add');
        }
    }

    /**
     * 回收商编辑
     * @return false|string
     */
    public function actionExternalRecyclerEdit()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $model = new SrExternalRecyclers();
            $time = date('Y-m-d H:i:s');
            //判断
            $Recyclers = SrExternalRecyclers::find()
                ->select('nick_name,phone_num')
                ->where(['phone_num'=>$param['phone_num']])
                ->andWhere(['!=','id',$param['id']])
                ->asArray()
                ->one();
            if (!empty($Recyclers)){
                return $this->AjaxResult(FAILD,'该手机号已存在');
            }

            $ExternalRecycler = $model::findOne($param['id']);

            if (empty($ExternalRecycler)){
                return $this->AjaxResult(FAILD,'数据异常');
            }
            $param['update_time'] = $time;
            $data['SrExternalRecyclers'] = $param;

            if ($ExternalRecycler->load($data) && $ExternalRecycler->save()){

                $info = '运营商: '.$this->user['company_name'].'编辑回收商'.$param['nick_name'];
                AdminLog::getClass()->addLog('编辑回收商',$info);
                return $this->AjaxResult(SUCCESS,'修改成功');
            }

            return $this->AjaxResult(FAILD,'修改失败',$ExternalRecycler->getErrors());
        }else{
            $id = Yii::$app->request->get('id');
            $Recyclers = ExternalRecyclers::getClass()->getExternalRecyclers(['id'=>$id,'del_flag'=>0]);

            return $this->renderPartial('external-recycler-edit', [
                'recyclers'=>$Recyclers
            ]);
        }
    }

    /**
     * 回收商资金流水
     * @return string
     */
    public function actionExternalRecyclerHistoryList()
    {
        $id = Yii::$app->request->get('id');
        $Recyclers = ExternalRecyclers::getClass()->getExternalRecyclers(['id'=>$id,'del_flag'=>0]);

        return $this->render('external-recycler-history-list',['recyclers'=>$Recyclers]);
    }

    /**
     * 资金流水列表
     */
    public function actionAjaxExternalRecyclerHistoryList()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $where['del_flag'] = 0;
        $where['external_recycler_id'] = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');

        empty($status) || $where['income_direction'] = $status;
        $History = SrExternalRecyclersHistory::find()
            ->select('id,income_direction,income_amount,current_amount,create_date')
            ->where($where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //获取记录总数
        $total = SrExternalRecyclersHistory::find()
            ->where($where)
            ->asArray()
            ->count();

        $type = Yii::$app->params['RecyclerHistoryType'];

        $data = [];
        foreach ($History as $val){
            $temp = [];
            $temp['income_direction'] =  $type[$val['income_direction']];
            $temp['create_date'] = $val['create_date'];
            $temp['income_amount'] = $val['income_amount'];
            $temp['current_amount'] = $val['current_amount'];
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
     * 充值管理
     */
    public function actionRecyclerHistory()
    {
        return $this->render('recycler-history');
    }

    /**
     * 充值管理数据
     */
    public function actionAjaxRecyclerHistory()
    {
        list($start,$length) = $this->getOffset();
        $where['m.del_flag'] = 0;
        $where['m.income_direction'] = 1;//记录收入部分
        $nick_name = Yii::$app->request->get('nick_name');
        $phone_num = Yii::$app->request->get('phone_num');
        $end_status = Yii::$app->request->get('end_status');
        $status = Yii::$app->request->get('status');
        $this->user_admin && $where['r.agent'] = $this->agent_id;
        $like_where = [];
        $andWhere = [];
        $end_time = 60*60*72;//超时时间 72小时
        $time = time();
        empty($nick_name) || $like_where = ['like', 'r.nick_name', $nick_name];
        empty($phone_num) || $where['r.phone_num'] = $phone_num;
        if (!empty($end_status)){
            $where['m.status'] = 1;
            if ($end_status == 1){//超时
                $andWhere = ['<','UNIX_TIMESTAMP(m.create_date) + '.$end_time,$time];
            }else{//未超时
                $andWhere = ['>','UNIX_TIMESTAMP(m.create_date) + '.$end_time,$time];
            }
        }

        empty($status) || $where['m.status'] = $status;

        $History = SrExternalRecyclersHistory::find()
            ->alias('m')
            ->select('m.id,m.external_recycler_id,m.income_amount,m.create_date,m.income_bank,m.status,r.nick_name,r.phone_num')
            ->join('LEFT JOIN', 'sr_external_recyclers as r', 'm.external_recycler_id = r.id')
            ->where($where)
            ->andWhere($andWhere)
            ->andWhere($like_where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('m.id desc')
            ->all();

        //获取记录总数
        $total = SrExternalRecyclersHistory::find()
            ->alias('m')
            ->join('LEFT JOIN', 'sr_external_recyclers as r', 'm.external_recycler_id = r.id')
            ->where($where)
            ->andWhere($andWhere)
            ->andWhere($like_where)
            ->asArray()
            ->count();

        $hid = array_column($History,'id');//记录id获取图片

        $imgs = SrExternalRecyclersImg::find()
            ->select('count(image) as num,id,external_recycler_id')
            ->andWhere(['external_recycler_id'=>$hid])
            ->groupBy('external_recycler_id')
            ->asArray()
            ->all();

        $imgs = array_column($imgs,'num','external_recycler_id');


        $status = Yii::$app->params['RecyclerHistoryStatus'];

        $data = [];
        foreach ($History as $val){
            $create_time = strtotime($val['create_date']);
            $temp = [];
            $temp['external_recycler_id'] =  $val['external_recycler_id'];
            $temp['nick_name'] = $val['nick_name'];
            $temp['phone_num'] = $val['phone_num'];
            $temp['income_amount'] = $val['income_amount'];
            $temp['income_bank'] = $val['income_bank'];
            $num = !empty($imgs[$val['id']]) ? $imgs[$val['id']] : 0 ;
            $temp['num'] = '<a href="javascript:(0)" onclick="look('.$val['id'].')">'.$num.'</a>';
            $temp['end_status'] = $val['status'] != 1 ? '已审核' : ($time > ($create_time+$end_time)   ? '<span style="color: red">已超时</span>' : '未超时') ;
            if ($val['status'] == 1){
                if ($time > ($create_time+$end_time)){
                    $temp['end_show'] = '超时' . time_date_dhs($time - ($create_time+$end_time));
                }else{
                    $temp['end_show'] =  time_date_dhs(($create_time+$end_time) - $time ).'超时';
                }
            }else{
                $temp['end_show'] = '已审核';
            }
            $temp['status'] = $status[$val['status']];
            if ($val['status'] == 1){
                $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$val['id'].')">确认到账</a>';
            }else{
                $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$val['id'].')">查看到账回执</a>';
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
     *获取图片
     * @return string
     */
    public function actionRecyclerHistoryImgs()
    {
        $id = Yii::$app->request->get('id');
        $Img = SrExternalRecyclersImg::find()
            ->select('id,image')
            ->where(['external_recycler_id'=>$id])
            ->asArray()
            ->all();

        return $this->render('recycler-history-imgs', [
            'img'=>$Img
        ]);
    }

    /**
     * 充值管理编辑
     */
    public function actionRecyclerHistoryEdit()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $model = new SrExternalRecyclersHistory();
            $time = date('Y-m-d H:i:s');
            //判断
            $ExternalRecycler = $model::findOne($param['id']);

            if (empty($ExternalRecycler)){
                return $this->AjaxResult(FAILD,'数据异常');
            }
            if ($ExternalRecycler['status'] != 1){
                return $this->AjaxResult(FAILD,'该记录已审核通过');
            }
            if($ExternalRecycler['income_amount'] < $param['reach_amount']){
                return $this->AjaxResult(FAILD,'确认金额不能超过'.$ExternalRecycler['income_amount'].'元');
            }

            $bank = $model::find()
                ->select('reach_bank,income_amount')
                ->where(['reach_bank'=>$param['reach_bank']])
                ->asArray()
                ->one();
            if (!empty($bank)){
                return $this->AjaxResult(FAILD,'此交易流水已经审核并到账'.$bank['income_amount'].'元，请勿重复提交。');
            }
            $param['update_date'] = $time;
            $param['income_amount'] = $param['reach_amount'];//添加成功修改小程序提交的金额
            $param['status'] = 2;
            $data['SrExternalRecyclersHistory'] = $param;
            $transaction = Yii::$app->db->beginTransaction();
            try {

                if (!$ExternalRecycler->load($data) || !$ExternalRecycler->save()){
                    throw new Exception($ExternalRecycler->getFirstStrErrors());
                }

                SrExternalRecyclers::updateAllCounters(['balance' => $param['reach_amount']],['id' => $ExternalRecycler['external_recycler_id']]);

                $info = '运营商: '.$this->user['company_name'].'审核了一笔回收商充值记录,回收商id为: '.$ExternalRecycler['external_recycler_id'].'金额为: '.$param['reach_amount'];
                AdminLog::getClass()->addLog('审核回收商充值记录',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS,'提交成功');
            }catch(Exception $e){
                $transaction->rollBack();

                return $this->AjaxResult(FAILD,'提交失败',$e->getMessage());
            }
        }else{
            $id = Yii::$app->request->get('id');
            $History = SrExternalRecyclersHistory::find()
                ->alias('m')
                ->select('m.id,m.external_recycler_id,m.income_amount,m.income_bank,m.status,r.nick_name,m.reach_amount,m.reach_bank,m.reach_img,m.reach_time,m.create_date')
                ->join('LEFT JOIN', 'sr_external_recyclers as r', 'm.external_recycler_id = r.id')
                ->where(['m.id'=>$id])
                ->asArray()
                ->orderBy('m.id desc')
                ->one();

            return $this->renderPartial('recycler-history-edit', [
                'history'=>$History
            ]);
        }
    }

    /**
     * 回收员
     */
    public function actionRecyclerStaffIndex()
    {
        return $this->render('recycler-staff-index');
    }

    /**
     * 回收员
     */
    public function actionAjaxRecyclerStaffIndex()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $like_Where = [];
        $nickname = Yii::$app->request->get('nick_name');
        $phone_num = Yii::$app->request->get('phone_num');
        empty($nickname) || $like_Where  = ['like', 'nick_name', $nickname];
        empty($phone_num) || $where['phone_num'] = $phone_num;

        $Recycler = SrExternalRecycler::find()
            ->select('id,nick_name,phone_num,village_id,balance,status,create_date,type')
            ->andWhere($where)
            ->andWhere($like_Where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //获取记录总数
        $total = SrExternalRecycler::find()
            ->andWhere($where)
            ->andWhere($like_Where)
            ->asArray()
            ->count();

        $village_id = array_column($Recycler,'village_id');
        $rid = array_column($Recycler,'id');

        $Village = Village::getClass()->getVillage(['p_id'=>$village_id,'del_flag'=>0]);

        $Village = array_column($Village,'village_name','p_id');

        $History = SrExternalRecyclerHistory::find()
            ->select('sum(income_amount) as income_amount,external_recycler_id')
            ->where(['external_recycler_id'=>$rid,'income_direction'=>1])
            ->asArray()
            ->groupBy('external_recycler_id')
            ->all();

        $History = array_column($History,'income_amount','external_recycler_id');

        $status = Yii::$app->params['RecyclerStaffStatus'];
        $type = Yii::$app->params['RecyclerStaffType'];
        $data = [];
        foreach ($Recycler as $val){
            $temp = [];
            $temp['id'] = $val['id'];
            $temp['type'] = $type[$val['type']];
            $temp['nick_name'] = $val['nick_name'];
            $temp['village'] = !empty($Village[$val['village_id']]) ? $Village[$val['village_id']] : '暂未绑定' ;
            $temp['phone_num'] = $val['phone_num'];
            $temp['sum_balance'] = !empty($History[$val['id']]) ? $History[$val['id']] : "暂无收益记录";
            $temp['balance'] = $val['balance'];
            $temp['status'] = $status[$val['status']];
            $temp['create_date'] = $val['create_date'];
            $temp['opt'] =   $temp['opt'] = '<a href="javascript:(0)" onclick="edit('.$val['id'].')">编辑</a> 
             <a href="javascript:(0)" onclick="RecyclerList('.$val['id'].')">充值记录</a>';
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
     * 编辑
     */
    public function actionRecyclerStaffEdit()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $model = new SrExternalRecycler();
            $time = date('Y-m-d H:i:s');
            //判断
            $Recycler = $model::findOne($param['id']);
            if (empty($Recycler)){
                return $this->AjaxResult(FAILD,'数据异常');
            }

            $param['update_date'] = $time;
            $data['SrExternalRecycler'] = $param;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$Recycler->load($data) || !$Recycler->save()){
                    throw new Exception($Recycler->getFirstStrErrors());
                }
                $info = '运营商: '.$this->user['company_name'].'编辑回收商回收员'.$param['nick_name'];
                AdminLog::getClass()->addLog('编辑回收商回收员',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS,'修改成功');
            }catch(Exception $e){
                $transaction->rollBack();

                return $this->AjaxResult(FAILD,'修改失败',$e->getMessage());
            }
        }else{
            $id = Yii::$app->request->get('id');
            $Village = Village::getClass()->getVillage(['del_flag'=>0]);
            $staff = ExternalRecycler::getClass()->getExternalRecycler(['del_flag'=>0,'id'=>$id]);

            return $this->renderPartial('recycler-staff-edit', [
                'Village'=>$Village,
                'staff'=>$staff
            ]);
        }
    }

    /**
     * 回收商资金流水
     * @return string
     */
    public function actionRecyclerStaffHistory()
    {
        $id = Yii::$app->request->get('id');
        $Recyclers = ExternalRecycler::getClass()->getExternalRecycler(['del_flag'=>0,'id'=>$id]);

        return $this->render('recycler-staff-history',['recyclers'=>$Recyclers]);
    }

    /**
     * 资金流水列表
     */
    public function actionAjaxRecyclerStaffHistory()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $where['del_flag'] = 0;
        $where['external_recycler_id'] = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');

        empty($status) || $where['income_direction'] = $status;
        $History = SrExternalRecyclerHistory::find()
            ->select('id,income_direction,income_amount,current_amount,create_date')
            ->where($where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //获取记录总数
        $total = SrExternalRecyclerHistory::find()
            ->where($where)
            ->asArray()
            ->count();

        $type = Yii::$app->params['RecyclerHistoryType'];
        $data = [];
        foreach ($History as $val){
            $temp = [];
            $temp['income_direction'] =  $type[$val['income_direction']];
            $temp['create_date'] = $val['create_date'];
            $temp['income_amount'] = $val['income_amount'];
            $temp['current_amount'] = $val['current_amount'];
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
     * 收货量统计
     */
    public function actionRecyclerOrderAmount()
    {
        $type = Yii::$app->request->get('type');
        $status = Yii::$app->request->get('status');
        $excel = Yii::$app->request->get('excel');
        $dateInfo = "'%Y-%m-%d'";
        if ($status == 2){
            $dateInfo = "'%Y-%m'";
        }
        $sql = "SELECT c.order_id, c.category_parent ,sum(c.num) AS amount,DATE_FORMAT(c.create_date,".$dateInfo.") AS days FROM
		    sr_external_recyclers_order_child c LEFT JOIN  sr_external_recycler as b on (c.external_recycler_id = b.id) WHERE   ";
        if (!empty($this->user_admin)){
            $sql .= " b.agent = ".$this->agent_id." and ";
        }
        if (!empty($type) && $type > 0){
            $whereType = $type - 1;
            $sql .= " b.type = ".$whereType." and ";
        }
        $today = 14;  //默认显示14天
        //筛选日期
        switch ($status)
        {
            case 1://日期
                $startDate = Yii::$app->request->get('daystart');
                $endDate = Yii::$app->request->get('dayend');
                if (!empty($startDate) && !empty($endDate)){
                    $sql .= "c.create_date
                    BETWEEN  '".$startDate." 00:00:00' AND '".$endDate." 23:59:59' AND ";
                }
            break;
            case 2://月份
                $startDate = Yii::$app->request->get('monthstart');
                $endDate = Yii::$app->request->get('monthend');
                if (!empty($startDate) && !empty($endDate)) {
                    $startDate = date('Y-m-d', mktime(00, 00, 00, date('m', strtotime($startDate)), 01));
                    $endDate = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($endDate)) + 1, 00));
                    $sql .= "c.create_date
                    BETWEEN  '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59' AND ";
                }
            break;
            default://默认
                $startDate = Yii::$app->request->get('daystart');
                $endDate = Yii::$app->request->get('dayend');
                if (!empty($startDate) && !empty($endDate)){
                    $sql .= "c.create_date
                    BETWEEN  '".$startDate." 00:00:00' AND '".$endDate." 23:59:59' AND ";
                }else{
                    $startDate = date('Y-m-d',strtotime('-'.$today.'day'));
                    $endDate = date('Y-m-d');
                }
                $sql .= "c.create_date
                    BETWEEN  '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59' AND ";
                break;
        }

        $sql .= " c.del_flag = 0 GROUP BY c.category_parent,days order by days ";
        $order = Yii::$app->db->createCommand($sql)
            ->queryAll();

        $category_id = array_column($order,'category_parent');

        $category = Category::getClass()->getCategoryList(['id'=>$category_id]);
        $category = array_column($category,NULL,'id');

        $day = array_unique(array_column($order,'days'));
        array_unshift($day,"品类");

        $column = [];
        foreach ($order as $key => $val){
            $column[$val['category_parent']]['品类'] = !empty($category[$val['category_parent']]['category_name']) ?
                $category[$val['category_parent']]['category_name'].'('.$category[$val['category_parent']]['rubbish_unit'].')' : '未知';

            foreach ($day as $v) {
                if ($v != '品类'){
                    if ($val['days'] == $v){
                        $column[$val['category_parent']][$v] = empty($column[$val['category_parent']][$v]) ? 0 : $column[$val['category_parent']][$v];
                        $column[$val['category_parent']][$v] += !empty($val['amount'])  ? $val['amount'] : 0;
                    }else{
                        $column[$val['category_parent']][$v] = !empty($column[$val['category_parent']][$v]) ? $column[$val['category_parent']][$v] : 0;
                    }
                }
            }
        }
        ksort($column);

        //导出excel报表
        if (!empty($excel) && $excel == 1){
            $name = '数量统计表' . time();
            $key = [];
            foreach ($day as  $ks=>$v){
                $k = substr(str_shuffle(MD5($v)),0,6);
                $key[$k] = $v;
            }
            $key = array_flip($key);
            $data = [];
            foreach ($column as $k => $val){
                foreach ($day as $v){
                    $data[$k][$key[$v]] = $val[$v];
                }
            }

            $key = array_values($key);
            Excel::Export($name, $day, $data, $key);
        }
        return $this->render('recycler-order-amount', [
            'column'=>$column,'day'=>$day,'today'=>$today
        ]);
    }


    /**
     * 收货金额统计
     */
    public function actionRecyclerOrderPay()
    {
        $type = Yii::$app->request->get('type');
        $status = Yii::$app->request->get('status');
        $excel = Yii::$app->request->get('excel');
        $dateInfo = "'%Y-%m-%d'";
        if ($status == 2){
            $dateInfo = "'%Y-%m'";
        }
        $sql = "SELECT c.order_id, c.category_parent ,sum(c.amount) AS amount,DATE_FORMAT(c.create_date,".$dateInfo.") AS days FROM
		    sr_external_recyclers_order_child c LEFT JOIN
 sr_external_recycler as b
on (c.external_recycler_id = b.id) WHERE  ";

        if (!empty($this->user_admin)){
            $sql .= " b.agent = ".$this->agent_id." and ";
        }

        if (!empty($type) && $type > 0){
            $whereType = $type - 1;
            $sql .= " b.type = ".$whereType." and ";
        }
        $today = 14;//默认显示14天
        //删选日期
        switch ($status)
        {
            case 1:
                $startDate = Yii::$app->request->get('daystart');
                $endDate = Yii::$app->request->get('dayend');
                if (!empty($startDate) && !empty($endDate)){
                    $sql .= "c.create_date
                    BETWEEN  '".$startDate." 00:00:00' AND '".$endDate." 23:59:59' AND ";
                }
                break;
            case 2:
                $startDate = Yii::$app->request->get('monthstart');
                $endDate = Yii::$app->request->get('monthend');
                if (!empty($startDate) && !empty($endDate)) {
                    $startDate = date('Y-m-d', mktime(00, 00, 00, date('m', strtotime($startDate)), 01));
                    $endDate = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($endDate)) + 1, 00));
                    $sql .= "c.create_date
                    BETWEEN  '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59' AND ";
                }
                break;
            default:
                $startDate = Yii::$app->request->get('daystart');
                $endDate = Yii::$app->request->get('dayend');
                if (!empty($startDate) && !empty($endDate)){
                    $sql .= "c.create_date
                    BETWEEN  '".$startDate." 00:00:00' AND '".$endDate." 23:59:59' AND ";
                }else{
                    $startDate = date('Y-m-d',strtotime('-'.$today.'day'));
                    $endDate = date('Y-m-d');
                }
                $sql .= "c.create_date
                    BETWEEN  '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59' AND ";
                break;
        }


        $sql .= " c.del_flag = 0 GROUP BY c.category_parent,days order by days ";
        $order = Yii::$app->db->createCommand($sql)
            ->queryAll();

        $category_id = array_column($order,'category_parent');
        $category = Category::getClass()->getCategoryList(['id'=>$category_id]);
        $category = array_column($category,'category_name','id');
        $day = array_unique(array_column($order,'days'));
        array_unshift($day,"品类");

        $column = [];
        foreach ($order as $key => $val){
            $column[$val['category_parent']]['品类'] = !empty($category[$val['category_parent']]) ? $category[$val['category_parent']].'(元)' : '未知';
            foreach ($day as $v) {
                if ($v != '品类'){
                    if ($val['days'] == $v){
                        $column[$val['category_parent']][$v] = empty($column[$val['category_parent']][$v]) ? 0 : $column[$val['category_parent']][$v];
                        $column[$val['category_parent']][$v] += !empty($val['amount'])  ? $val['amount'] : 0;
                    }else{
                        $column[$val['category_parent']][$v] = !empty($column[$val['category_parent']][$v]) ? $column[$val['category_parent']][$v] : 0;
                    }
                }
            }
        }
        ksort($column);

        //导出excel报表
        if (!empty($excel) && $excel == 1){
            $name = '数量统计表' . time();
            $key = [];
            foreach ($day as  $ks=>$v){
                $k = substr(str_shuffle(MD5($v)),0,6);
                $key[$k] = $v;
            }
            $key = array_flip($key);

            $data = [];
            foreach ($column as $k => $val){
                foreach ($day as $v){
                    $data[$k][$key[$v]] = $val[$v];
                }
            }
            $key = array_values($key);
            Excel::Export($name, $day, $data, $key);
        }
        return $this->render('recycler-order-pay', [
            'column'=>$column,'day'=>$day,'today'=>$today
        ]);
    }




}