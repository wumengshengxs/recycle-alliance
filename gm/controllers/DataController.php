<?php

namespace gm\controllers;

use common\models\AdminLog;
use common\models\Category;
use common\models\models;
use common\models\Recycler;
use common\models\UserDeliveryHistory;
use common\models\Machine;
use common\models\TrashCan;
use common\models\UserRank;
use common\models\Users;
use common\models\Village;
use gm\models\Excel;
use gm\models\SrAgentTradeHistory;
use gm\models\SrAgentUserRel;
use gm\models\SrRecyclerMachineRel;
use gm\models\SrTrashCan;
use gm\models\SrUserDeliveryDayStatistic;
use gm\models\SrUserDeliveryHistoryParent;
use gm\models\SrUserIncomeHistory;
use gm\models\SrUserMessage;
use gm\models\SrUserUsageStatistics;
use gm\models\SrUserVillageRankMonth;
use gm\models\SrUserWithdrawOrder;
use yii;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrRecycler;
use gm\models\SrRecyclingHistoryChild;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrUser;
use common\models\Agent;
use Exception;
use yii\filters\AccessControl;

class DataController extends GController
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
     * 用户投递列表
     */
    public function actionDelivery()
    {
        $where = [];
        $this->user_admin && $where['agent'] = $this->agent_id;
        $srRecyclingMachine = Machine::getClass()->getMachineList($where);

        return $this->render('delivery', [
            'srRecyclingMachine' => $srRecyclingMachine,
        ]);
    }

    /**
     * 首页获取用户投递数据
     */
    public function actionAjaxDelivery()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();

        //获取条件参数并组装条件值
        $phone_num = yii::$app->request->get('phone');
        $machine_id = yii::$app->request->get('machine_id');
        $device_id = yii::$app->request->get('device_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent_id'] = $this->agent_id;
        $dateWhere = [];

        //搜索查询数据量大的情况下使用 避免连表查询
        if (!empty($machine_id) || !empty($device_id)) {
            $m_where['del_flag'] = 0;
            empty($machine_id) || $m_where['id'] = $machine_id;
            empty($device_id) || $m_where['device_id'] = $device_id;
            $machine_id = Machine::getClass()->getMachineList($m_where);
            $machine_id = array_column($machine_id, 'id');
            $where['machine_id'] = $machine_id;
        }
        //用户搜索
        if (!empty($phone_num)) {
            $u_where['u.phone_num'] = $phone_num;
            $this->user_admin && $u_where['r.agent'] = $this->agent_id;
            $user_id = Users::getClass()->getAgentUserList($u_where);
            $user_id = array_column($user_id, 'id');
            $where['user_id'] = $user_id;
        }

        if (!empty($delivery_time)) {
            list($start_time, $end_time) = explode(' - ', $delivery_time);
            $dateWhere = ['between', 'create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        //查询投递数据列表
        $userDeliveryHistoryParent = SrUserDeliveryHistoryParent::find()
            ->select('id,user_id, machine_id, delivery_time,machine_id')
            ->andWhere(['>', 'machine_id', 0])
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->orderBy('id desc')
            ->offset($start)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrUserDeliveryHistoryParent::find()
            ->andWhere(['>', 'machine_id', 0])
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->count();

        //机器
        $mid = array_column($userDeliveryHistoryParent, 'machine_id');
        $machine = Machine::getClass()->getMachineList(['id' => $mid]);
        $machine = array_column($machine, 'community_name', 'id');
        //user
        $uid = array_column($userDeliveryHistoryParent, 'user_id');
        $user = Users::getClass()->getUserList(['id' => $uid]);
        $user = array_column($user, NULL, 'id');
        //生成dataTable格式数据
        $data = [];
        foreach ($userDeliveryHistoryParent as $item) {
            $v['id'] = $item['id'];
            $v['user_id'] = $item['user_id'];
            $v['nick_name'] = !empty($user[$item['user_id']]['nick_name']) ? $user[$item['user_id']]['nick_name'] : "未知";
            $v['phone_num'] = !empty($user[$item['user_id']]['phone_num']) ? $user[$item['user_id']]['phone_num'] : "未知";
            $v['community_name'] = !empty($machine[$item['machine_id']]) ? $machine[$item['machine_id']] : "暂无";
            $v['delivery_time'] = $item['delivery_time'];
            $v['opt'] = '<a href="javascript:(0)" onclick="delivery_detail(' . $item['id'] . ')">投递明细</a>';
            $v['opt'] .= '  <a href="javascript:(0)"onclick="check_log(\'' . $item['machine_id'] . '\')" >查看日志</a>';
            array_push($data, array_values($v));
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
     * 查询投递的品类
     */
    public function actionDeliveryDetail()
    {
        $id = Yii::$app->request->get('id');
        //查询投递明细
        $historyChild = UserDeliveryHistory::getClass()->getChildList(['parent_id' => $id, 'del_flag' => 0]);
        $uid = "";
        $status = Yii::$app->params['HistoryChildStatus'];//获取状态
        foreach ($historyChild as &$value) {
            $value['status'] = $status[$value['declarable_status']];
            if (!empty($value['user_id']) && empty($uid)) {
                $uid = $value['user_id'];
            }
        }
        $srUser = Users::getClass()->getUser(['id' => $uid]);//获取用户信息

        return $this->renderPartial('delivery-detail', [
            'nick_name' => empty($srUser['nick_name']) ? '' : $srUser['nick_name'],
            'phone_num' => empty($srUser['phone_num']) ? '' : $srUser['phone_num'],
            'detail' => $historyChild,
        ]);
    }

    /**
     * 修改投递信息
     * @return string
     */
    public function actionDeliveryDetailEdit()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $trash_id = Yii::$app->request->post('trash_id');
            //修改前的箱体id用于减去该箱体数据
            $old_trash_id = Yii::$app->request->post('old_trash_id');
            $delivery_count = Yii::$app->request->post('delivery_count');
            //获取该条投递记录
            $historyChild = UserDeliveryHistory::getClass()->getChild(['id' => $id, 'del_flag' => 0]);
            if (empty($historyChild) || $historyChild['declarable_status'] != 0) {
                return $this->AjaxResult(FAILD, '该投递记录已审核通过或已被修改');
            }
            //获取要改为的箱体
            $trashCan = TrashCan::getClass()->getTrashCan(['id' => $trash_id]);
            if (empty($trashCan)) {
                return $this->AjaxResult(FAILD, '回收箱体获取失败');
            }
            if (empty($delivery_count) || $delivery_count <= 0) {
                return $this->AjaxResult(FAILD, '重量不能为空或填写错误');
            }
            //根据品类价格获取投递金额 activity_status = 1 活动价格
            $price = $trashCan['recycle_price'];
            if ($trashCan['activity_status'] == 1) {
                $price = $trashCan['activity_price'];
            }
            $delivery_income = round(bcmul($delivery_count, $price, 3), 2);//修改的金额
            $delivery_old = $historyChild['delivery_income'];//历史金额
            $delivery_count_old = $historyChild['delivery_count'];//历史重量
            $time = date('Y-m-d H:i:s');
            //上次箱体的数据
            $old_trash = TrashCan::getClass()->getTrashCan(['id'=>$old_trash_id]);
            //减去之前箱体的重量/数量
            $old_UserEdit  = Users::getClass()->getEditStatistics($old_trash['category'], $delivery_count_old * -1);
            //新增的数据
            $UserEdit  = Users::getClass()->getEditStatistics($trashCan['category'], $delivery_count);

            $transaction = yii::$app->db->beginTransaction();
            try {
                $child = SrUserDeliveryHistoryChild::updateAll([
                    'delivery_type' => $trashCan['category'], 'delivery_income' => $delivery_income,
                    'delivery_count' => $delivery_count, 'can_name' => $trashCan['can_name'],
                    'update_date' => $time
                ], ['id' => $id]);
                $History = SrUserIncomeHistory::updateAll(
                    ['income_amount' => $delivery_income, 'update_date' => $time],
                    ['source_id' => $historyChild['parent_id'], 'source_code' => 'delivery']
                );

                //父表记录减去历史金额
                $income = bcsub($delivery_income, $delivery_old, 2);
                $Parent = SrUserDeliveryHistoryParent::updateAllCounters(
                    ['income_amount' => $income],
                    ['id' => $historyChild['parent_id']]
                );

                //减少的垃圾桶数据
                $old_Statistics = SrUserUsageStatistics::updateAllCounters($old_UserEdit, ['user_id' => $historyChild['user_id']]);
                //添加的垃圾桶增加数据
                $Statistics = SrUserUsageStatistics::updateAllCounters($UserEdit, ['user_id' => $historyChild['user_id']]);

                if (!$child || !$History ) {
                    throw new Exception('修改失败: child' . $child . ' History:' . $History .
                        ' Parent:' . $Parent . ' Statistics:' . $Statistics .
                        'old_Statistics:'. $old_Statistics);
                }

                $info = '运营商: '.$this->user['company_name'].' 修改投递记录id为: '.$historyChild['id'].' 将品类 '.
                        $old_trash['can_name'].' 修改为: '.$trashCan['can_name'] .
                        ' 将重量 '.$delivery_count_old.' 修改为: '.$delivery_count.' 修改后的收益为: '.$delivery_income;
                AdminLog::getClass()->addLog('修改投递记录',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS, '修改成功', $id);
            } catch (Exception $e) {
                $transaction->rollBack();

                return $this->AjaxResult(FAILD, '修改失败或提交错误', $e->getMessage());
            }
        } else {
            $id = Yii::$app->request->get('id');
            //查询投递明细
            $historyChild = UserDeliveryHistory::getClass()->getChild(['id' => $id]);
            $category_name = Category::getClass()->getCategory(['id' => $historyChild['delivery_type']]);
            $historyChild['category_name'] = $category_name['category_name'];

            $category = [];
            if ($historyChild['delivery_type'] != 1) {
                $delivery_type = [2, 3, 4, 5, 6, 7, 8];//可修改的品类 如:1.饮料瓶不能修改
                $category = TrashCan::getClass()->getTrashCanList([
                    'machine_id' => $historyChild['machine_id'], 'category' => $delivery_type, 'del_flag' => 0
                ]);
            }
            if ($historyChild['delivery_type'] == 1) {
                $category = TrashCan::getClass()->getTrashCanList([
                    'machine_id' => $historyChild['machine_id'], 'category' => 1, 'del_flag' => 0
                ]);
            }

            return $this->renderPartial('delivery-detail-edit', [
                'detail' => $historyChild,
                'category' => $category,
            ]);
        }
    }


    /**
     * 查看日志
     */
    public function actionLogs()
    {
        $machine_id = Yii::$app->request->get('machine_id');//设备列表入口

        $device_id = Machine::getClass()->getMachine(['id' => $machine_id])['device_id'];

        $date = Yii::$app->params['LogsDate'];
        $data = [];
        foreach ($date as $key => $val) {
            $data[$key]['title'] = $val;
            $data[$key]['url'] = 'https://xiaosongshulog.oss-cn-shanghai.aliyuncs.com/' . $device_id . '_' . $val . '.txt';
        }

        return $this->renderPartial('logs', ['logs' => $data]);
    }

    /**
     * 添加投递记录
     * @return mixed|string
     */
    public function actionDeliveryAdd()
    {
        if (Yii::$app->request->post()) {
            $machine_id = yii::$app->request->post('machine_id');
            $phone_num = yii::$app->request->post('phone_num');
            $trash_id = yii::$app->request->post('trash_id');
            $datetime = yii::$app->request->post('datetime');
            $delivery_count = yii::$app->request->post('delivery_count');
            if ($delivery_count < 0) {
                $this->AjaxResult(FAILD, '投递重量错误');
            }
            $DeliveryParent = new SrUserDeliveryHistoryParent();
            $DeliveryChild = new SrUserDeliveryHistoryChild();
            $UserMessage = new SrUserMessage();
            $IncomeHistory = new SrUserIncomeHistory();
            $time = date('Y-m-d H:i:s');
            //获取垃圾桶和用户信息
            $trashCan = TrashCan::getClass()->getTrashCan(['id' => $trash_id]);
            if (empty($trashCan)) {
                return $this->AjaxResult(FAILD, '回收箱体获取失败');
            }
            $user = Users::getClass()->getUser(['phone_num' => $phone_num, 'del_flag' => 0]);
            if (empty($user)) {
                return $this->AjaxResult(FAILD, '该用户不存在');
            }
            $agent = $this->user['id'];
            //根据品类价格获取投递金额 activity_status = 1 活动价格
            $price = $trashCan['recycle_price'];
            if ($trashCan['activity_status'] == 1) {
                $price = $trashCan['activity_price'];
            }
            $delivery_income = round(bcmul($delivery_count, $price, 3), 2);
            $serial_num = 'admin' . date("YmdHis");//订单号

            //添加投递父表
            $parent['SrUserDeliveryHistoryParent'] = [
                'agent_id' => $agent,
                'machine_id' => $machine_id,
                'income_amount' => $delivery_income,
                'total_points' => 0,
                'user_id' => $user['id'],
                'serial_num' => $serial_num,
                'delivery_time' => $datetime,
                'create_date' => $time,
                'update_date' => $time,
            ];
            //信息表
            $message['SrUserMessage'] = [
                'user_id' => $user['id'],
                'message_type' => 2,
                'message_title' => '投递成功',
                'message_content' => '恭喜您投递成功，共已获环保金' . $delivery_income . '元环保金，可在我的投递页面查看详情',
                'message_status' => 0,
                'message_receive_time' => $datetime,
                'create_date' => $time,
                'update_date' => $time,
            ];

            //流水记录表
            $income['SrUserIncomeHistory'] = [
                'user_id' => $user['id'],
                'income_type' => 1,
                'income_source' => '投递废品',
                'income_name' => '回收订单',
                'income_amount' => $delivery_income,
                'income_unit' => '元',
                'income_direction' => 1,
                'source_id' => 0,
                'source_code' => 'delivery',
                'income_time' => $datetime,
                'create_date' => $time,
                'update_date' => $time,
                'third_status' => 0
            ];

            //子表
            $child['SrUserDeliveryHistoryChild'] = [
                'agent' => $agent,
                'user_id' => $user['id'],
                'delivery_type' => $trashCan['category'],
                'delivery_count' => $delivery_count,
                'delivery_income' => $delivery_income,
                'delivery_check' => 0,//都为正常
                'parent_id' => 0,
                'declarable_status' => 1,//后台添加的为审核通过
                'can_name' => $trashCan['can_name'],
                'recycle_child_id' => -2,//表示是客服增加的记录
                'can_num' => $trashCan['can_num'],
                'machine_id' => $machine_id,
                'has_rank' => "0",
                'delivery_time' => $datetime,
                'create_date' => $time
            ];

            $trashEdit = TrashCan::getClass()->getEditTrash($trashCan, $delivery_count, $time);
            $userEdit = Users::getClass()->getEditStatistics($trashCan['category'], $delivery_count, 1);
            $userEdit['current_env_amount'] = $delivery_income;//可用环保金增加
            $transaction = yii::$app->db->beginTransaction();
            try {
                //投递父表添加
                if (!$DeliveryParent->load($parent) || !$DeliveryParent->save()) {
                    throw new Exception($DeliveryParent->getFirstStrErrors());
                }
                //投递子表
                $child['SrUserDeliveryHistoryChild']['parent_id'] = $DeliveryParent->id;
                if (!$DeliveryChild->load($child) || !$DeliveryChild->save()) {
                    throw new Exception($DeliveryChild->getFirstStrErrors());
                }
                //收益记录表
                $income['SrUserIncomeHistory']['source_id'] = $DeliveryParent->id;
                if (!$IncomeHistory->load($income) || !$IncomeHistory->save()) {
                    throw new Exception($IncomeHistory->getFirstStrErrors());
                }
                //信息表
                if (!$UserMessage->load($message) || !$UserMessage->save()) {
                    throw new Exception($UserMessage->getFirstStrErrors());
                }
                //投递次数 按照本次投递流程结束算一次
                $Statistics = SrUserUsageStatistics::updateAllCounters($userEdit, ['user_id' => $user['id']]);
                //垃圾桶信息
                $trash = SrTrashCan::updateAll($trashEdit, ['id' => $trash_id]);

                if (!$Statistics || !$trash) {
                    throw new Exception('修改失败:Statistics:' . $Statistics . ' trash' . $trash);
                }

                $info = '运营商: '.$this->user['company_name'].'给用户: '.$user['nick_name'].' 添加投递记录投递父级ID为: '.$DeliveryParent->id.
                        ' 品类为: '.$trashCan['can_name'].' 金额为: '.$delivery_income.' 重量为: '.$delivery_count;
                AdminLog::getClass()->addLog('添加投递记录',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS, '添加成功', $DeliveryParent->id);
            } catch (Exception $e) {
                $transaction->rollBack();

                return $this->AjaxResult(FAILD, '添加失败', $e->getMessage());
            }
        } else {
            $where['del_flag'] = 0;
            $this->user_admin && $where['agent'] = $this->agent_id;
            $machine = Machine::getClass()->getMachineList($where);

            return $this->renderPartial('delivery-add', [
                'machine' => $machine,
            ]);
        }
    }

    /**
     * 通过机器获取品类列表
     * @return mixed
     */
    public function actionGetCommunityList()
    {
        $machine_id = yii::$app->request->post('id');
        //通过machine_id去查垃圾表的品类
        $category = TrashCan::getClass()->getTrashCanList(['machine_id' => $machine_id, 'del_flag' => 0]);

        return $this->AjaxResult(SUCCESS, '获取成功', $category);
    }

    /**
     * 注册用户列表
     */
    public function actionUser()
    {
        return $this->render('user');
    }

    /**
     * 抓取注册用户数据
     */
    public function actionAjaxUser()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();

        //获取条件参数并组装条件值
        $date_time = yii::$app->request->get('date_time');
        $phone_num = yii::$app->request->get('phone');
        $status = yii::$app->request->get('status');
        $where['del_flag'] = 0;
        $dateWhere = [];
        $rel_where = [];
        //短路运算
        empty($phone_num) || $where['phone_num'] = $phone_num;

        if ($status != "") {
            $where['user_status'] = $status;
        }
        $this->user_admin && $rel_where['agent'] = $this->agent_id;
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $dateWhere = ['between', 'create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        if ($date_time || $phone_num || $status){
            $uid = SrUser::find()
                ->select('id')
                ->where($where)
                ->andWhere($dateWhere)
                ->asArray()
                ->all();

            $rel_where['user_id'] = array_column($uid,'id');
        }
        $UserRel = SrAgentUserRel::find()
            ->select('id,user_id')
            ->where($rel_where)
            ->offset($start)
            ->limit($length)
            ->groupBy('user_id')
            ->orderBy('user_id desc')
            ->asArray()
            ->all();


        //获取记录总数
        $total = SrAgentUserRel::find()
            ->where($rel_where)
            ->count();

        $uid = array_column($UserRel,'user_id');
        $srUser = SrUser::find()
            ->select('id, nick_name, phone_num, user_status,open_id, create_date,update_date,remarks')
            ->where(['id'=>$uid])
            ->asArray()
            ->all();

        //生成dataTable格式数据
        $status = Yii::$app->params['UserStatus'];
        $data = [];
        foreach ($srUser as $v) {
            $temp = [];
            $temp['withdraw_amount'] = $v['id'];
            $temp['nick_name'] = $v['nick_name'];
            $temp['phone_num'] = $v['phone_num'];
            $temp['user_status'] = $status[$v['user_status']];
            $temp['open_id'] = $v['open_id'];
            $temp['create_date'] = $v['create_date'];
            $temp['update_date'] = $v['update_date'];
            $temp['remarks'] = $v['remarks'];
            $temp['opt1'] = '<a href="javascript:(0)"  onclick="seal(' . $v['id'] .','. $v['user_status'] .')">封号</a>';
            if ($v['user_status'] == 2){
                $temp['opt1'] = '<a href="javascript:(0)"onclick="seal(' . $v['id'] . ','. $v['user_status'] .')">解封</a>';
            }
            $temp['opt1'] .= '  <a href="javascript:(0)"onclick="rank(' . $v['phone_num'] . ')">投递排名查询</a>';
            $temp['opt1'] .= '  <a href="javascript:(0)"onclick="withdraw(' . $v['id'] . ')">提现详情</a>';
            $temp['opt1'] .= '  <a href="javascript:(0)"onclick="remark(' . $v['id'] . ')">书写备注</a>';

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
     * 封号/解封操作
     * @return mixed
     */
    public function actionUserEditStatus()
    {
        $id = yii::$app->request->post('id');
        $status = yii::$app->request->post('status');
        $msg    = ($status == 1) ? '封号操作成功' : '解封操作成功';
        $status = ($status == 1) ? 2 : 1;
        $up = SrUser::updateAll(
            ['user_status'=>$status,'update_date'=>date('Y-m-d H:i:s')],
            ['id'=>$id]);

        if ($up){
            $info = '运营商: '.$this->user['company_name'].'将用户ID为'.$id.'的账号'.$msg;
            AdminLog::getClass()->addLog($msg,$info);

            return $this->AjaxResult(SUCCESS,$msg);
        }
        return $this->AjaxResult(FAILD,'操作失败');
    }

    /**
     * 添加备注
     * @return mixed|string
     */
    public function actionUserRemark()
    {
        if (Yii::$app->request->post()){
            $id = Yii::$app->request->post('id');
            $remark = Yii::$app->request->post('remark');
            $time = date('Y-m-d H:i:s');
            $up = SrUser::updateAll(
                ['remarks'=>$remark,'update_date'=>$time],
                ['id'=>$id]
            );
            if ($up){
                $info = '运营商: '.$this->user['company_name'].'给用户ID为: '. $id. '添加备注'.$remark;
                AdminLog::getClass()->addLog('添加用户备注',$info);
                return $this->AjaxResult(SUCCESS,'操作成功');
            }
            return $this->AjaxResult(FAILD,'操作失败');
        }else{
            $id = Yii::$app->request->get('id');
            $user = Users::getClass()->getUser(['id'=>$id]);

            return $this->renderPartial('user-remark', [
                'user' => $user,
            ]);
        }
    }

    /**
     * 提现详情
     */
    public function actionUserWithdraw()
    {
        $id = Yii::$app->request->get('id');
        $static = Users::getClass()->getUserStatistics(['user_id' => $id]);

        return $this->renderPartial('user-withdraw', [
            'id' => $id,
            'static' => $static
        ]);
    }

    /**
     * 提现详情列表
     */
    public function actionAjaxUserWithdraw()
    {
        $id = Yii::$app->request->get('id');
        if(empty($id)){
            //id为空
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            die(json_encode($data_source));
        }
        list($start, $length) = $this->getOffset();
        $where['del_flag'] = 0;
        $where['user_id'] = $id;

        $withdraw = SrUserWithdrawOrder::find()
            ->select('id,withdraw_amount,order_status,order_create_date,order_update_date,user_id')
            ->where($where)
            ->orderBy('id desc')
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->all();

        //获取记录总数
        $total = SrUserWithdrawOrder::find()
            ->where($where)
            ->count();

        //生成dataTable格式数据
        $status = Yii::$app->params['OrderStatus'];
        $data = [];
        foreach ($withdraw as $v) {
            $temp = [];
            $temp['withdraw_amount'] = $v['withdraw_amount'];
            $temp['order_status']    = $status[$v['order_status']];
            $temp['order_create_date'] = $v['order_create_date'];
            $temp['order_update_date'] = $v['order_update_date'];
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
     * 获取用户排行
     * @return string
     */
    public function actionUserRank()
    {
        $phone_num = Yii::$app->request->get('phone_num');
        $u_where['phone_num'] = $phone_num;
        $user = Users::getClass()->getUser($u_where);
        if(empty($user)){
            return $this->renderPartial('user-rank', [
                'nick_name' => empty($user['nick_name']) ? '该用户不存在' : $user['nick_name'],
                'phone_num' => empty($user['phone_num']) ? '该用户不存在' : $user['phone_num'],
                'rankMonth' => [],
            ]);
        }
        $rankMonth  = UserRank::getClass()->getRankMonth(['user_id' => $user['id']]);
        $village_id = array_unique(array_column($rankMonth, 'village_id'));
        $village    = Village::getClass()->getVillageList(['p_id'=>$village_id]);
        $village_name = array_column($village, 'village_name', 'p_id');

        foreach ($rankMonth as &$value){
            $value['village_name'] = $village_name[$value['village_id']];
            if($value['rank'] == 0){
                $month = UserRank::getClass()->getRankMonth(
                    ['month' => $value['month'],'village_id'=>$value['village_id']],
                    'delivery_income desc'
                );
                $i = 0;
                foreach ($month as $val){
                    $i ++;
                    if ($value['user_id'] == $val['user_id']){
                        $value['rank'] = $i;
                        $i = 0;
                        continue;
                    }
                }
            }
        }

        return $this->renderPartial('user-rank', [
            'nick_name' => empty($user['nick_name']) ? '' : $user['nick_name'],
            'phone_num' => empty($user['phone_num']) ? '' : $user['phone_num'],
            'rankMonth' => $rankMonth
        ]);
    }

    /**
     * 设备清运页面
     */
    public function actionRecycle()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //查找所有设备
        $machine = Machine::getClass()->getMachineList($where);
        //查找回收员
        $recycler = Recycler::getClass()->getRecyclerList($where);

        return $this->render('recycle', [
            'machine'  => $machine,
            'recycler' => $recycler
        ]);
    }

    /**
     * 设备清运列表
     */
    public function actionAjaxRecycle()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        //获取条件参数并组装条件值
        $recycler_id = yii::$app->request->get('recycler_id');
        $machine_id = yii::$app->request->get('machine_id');
        $date_time = yii::$app->request->get('date_time');
        $dateWhere = [];
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;

        //搜索查询数据量大的情况下使用 避免连表查询
        if (!empty($machine_id)) {
            $machine_id = Machine::getClass()->getMachineList(['id'=>$machine_id]);
            $machine_id = array_column($machine_id, 'id');
            $where['machine_id'] = $machine_id;
        }
        if (!empty($recycler_id)) {
            $recycler_id = Recycler::getClass()->getRecyclerList(['id'=>$recycler_id]);
            $recycler_id = array_column($recycler_id, 'id');
            $where['recycler_id'] = $recycler_id;
        }
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $dateWhere = ['between', 'recycling_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }
        //查询清运数据列表
        $child = SrRecyclingHistoryChild::find()
            ->select('id,machine_id,recycler_id,category,recycling_amount,recycling_pay, recycling_time')
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->orderBy('id desc')
            ->offset($start)
            ->limit($length)->asArray()
            ->all();

        //获取记录总数
        $total = SrRecyclingHistoryChild::find()
            ->andWhere($where)
            ->andWhere($dateWhere)
            ->count();

        //品类
        $category_id = array_column($child,'category');
        $category = Category::getClass()->getCategoryList(['id'=>$category_id]);
        $category = array_column($category,NULL,'id');
        //所属机器
        $machine_id = array_column($child,'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_id]);
        $machine = array_column($machine,NULL,'id');
        //所属回收员
        $recycler_id = array_column($child,'recycler_id');
        $recycler = Recycler::getClass()->getRecyclerList(['id'=>$recycler_id]);
        $recycler = array_column($recycler,NULL,'id');

        //生成dataTable格式数据
        $data = [];
        foreach ($child as $v) {
            $temp = [];
            $temp['category'] = !empty($category[$v['category']]['category_name']) ? $category[$v['category']]['category_name'] : '暂无';
            $temp['recycling_amount'] = $v['recycling_amount'];
            $temp['recycling_pay'] = $v['recycling_pay'];
            $temp['machine'] = !empty($machine[$v['machine_id']]['community_name']) ? $machine[$v['machine_id']]['community_name'] : '暂无';
            $temp['nick_name'] = !empty($recycler[$v['recycler_id']]['nick_name']) ? $recycler[$v['recycler_id']]['nick_name'] : '暂无';
            $temp['phone_num'] = !empty($recycler[$v['recycler_id']]['phone_num']) ? $recycler[$v['recycler_id']]['phone_num'] : '暂无';
            $temp['recycling_time'] = $v['recycling_time'];
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
     * 投递人次页面
     * @return string
     */
    public function actionPeople()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //查找所有设备
        $machine = Machine::getClass()->getMachineList($where);

        return $this->render('people', [
            'machine'  => $machine,
        ]);
    }

    /**
     * 投递人次列表
     */
    public function actionAjaxPeople()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');//投递统计时间
        $user_time = yii::$app->request->get('user_time');//用户注册区间时间
        if (empty($delivery_time)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ];
            die(json_encode($data_source));
        }
        //查询条件预处理
        $where['del_flag'] = 0;
        $this->user_admin && $u_where['agent'] = $this->agent_id;
        $this->user_admin && $where['agent'] = $this->agent_id;

        empty($machine_id) || $where['machine_id'] = $machine_id;
        list($start_time, $end_time) = explode(' - ', $delivery_time);
        $andWhere = ['between', 'delivery_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];

        if (!empty($user_time)) {
            list($start_time, $end_time) = explode(' - ', $user_time);
            $u_where = ['between','u.create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
            $this->user_admin && $u_where['r.agent'] = $this->agent_id;
            $user = Users::getClass()->getAgentUserList($u_where);
            $user_id = array_column($user, 'id');
            $where['user_id'] = $user_id;
        }

        if (!empty($machine_id)) {
            $machine_id = Machine::getClass()->getMachineList(['id'=>$machine_id]);
            $machine_id = array_column($machine_id, 'id');
            $where['machine_id'] = $machine_id;
        }
        //查询记录
        $child = SrUserDeliveryHistoryChild::find()
            ->select('id,machine_id,count(id) as count,count(DISTINCT user_id) as num')
            ->where($where)
            ->andWhere($andWhere)
            ->offset($start)
            ->limit($length)
            ->orderBy('num desc')
            ->groupBy('machine_id')
            ->asArray()
            ->all();
        //统计数量
        $count = SrUserDeliveryHistoryChild::find()
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy('machine_id')
            ->count();

        $machine_ids = array_column($child, 'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_ids]);
        $machine = array_column($machine,NULL,'id');
        $data = [];
        foreach ($child as $item) {
            $temp = [];
            $temp['street_name'] = !empty($machine[$item['machine_id']]['street_name']) ? $machine[$item['machine_id']]['street_name'] : "无效数据";
            $temp['community_name'] = !empty($machine[$item['machine_id']]['community_name']) ? $machine[$item['machine_id']]['community_name'] : "无效数据";
            $temp['num'] = $item['num'];
            $temp['count'] = $item['count'];
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
        ];

        die(json_encode($data_source));
    }

    /**
     * 用户投递明细页面
     * @return string
     */
    public function actionDeliveryUser()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //查找所有设备
        $machine = Machine::getClass()->getMachineList($where);
        $category = Category::getClass()->getCategoryList(['del_flag'=>0,'category_type'=>0]);

        return $this->render('delivery-user', [
            'machine'  => $machine,
            'category' => $category
        ]);
    }

    /**
     * 用户投递明细列表
     */
    public function actionAjaxDeliveryUser()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        //获取条件参数并组装条件值
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $category_id = yii::$app->request->get('category_id');
        //如果没有参数则不做处理
        if (empty($machine_id) || empty($delivery_time)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            die(json_encode($data_source));
        }
        $andWhere = [];
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;

        //搜索查询数据量大的情况下使用 避免连表查询
        if (!empty($machine_id)) {
            $machine_id = Machine::getClass()->getMachineList(['id'=>$machine_id]);
            $machine_id = array_column($machine_id, 'id');
            $where['machine_id'] = $machine_id;
        }
        if (!empty($category_id)) {
            $category_id = Category::getClass()->getCategoryList(['id'=>$category_id]);
            $category_id = array_column($category_id, 'id');
            $where['delivery_type'] = $category_id;
        }
        if (!empty($delivery_time)) {
            list($start_time, $end_time) = explode(' - ', $delivery_time);
            $andWhere = ['between', 'delivery_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        //查询清运数据列表
        $child = SrUserDeliveryHistoryChild::find()
            ->select('id,machine_id,user_id,delivery_type,delivery_income,delivery_count, delivery_time')
            ->andWhere($where)
            ->andWhere($andWhere)
            ->orderBy('id desc')
            ->offset($start)
            ->limit($length)->asArray()
            ->all();

        //获取记录总数
        $total = SrUserDeliveryHistoryChild::find()
            ->andWhere($where)
            ->andWhere($andWhere)
            ->count();

        //品类
        $category_id = array_column($child,'delivery_type');
        $category = Category::getClass()->getCategoryList(['id'=>$category_id]);
        $category = array_column($category,NULL,'id');
        //所属机器
        $machine_id = array_column($child,'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_id]);
        $machine = array_column($machine,NULL,'id');
        //所属用户
        $user_id = array_column($child,'user_id');
        $user = Users::getClass()->getUserList(['id'=>$user_id]);
        $user = array_column($user,NULL,'id');

        //生成dataTable格式数据
        $data = [];
        foreach ($child as $v) {
            $temp = [];
            $temp['id'] = $v['id'];
            $temp['user_id'] = $v['user_id'];
            $temp['phone_num'] = !empty($user[$v['user_id']]['phone_num']) ? $user[$v['user_id']]['phone_num'] : '暂无';
            $temp['machine'] = !empty($machine[$v['machine_id']]['community_name']) ? $machine[$v['machine_id']]['community_name'] : '暂无';
            $temp['category'] = !empty($category[$v['delivery_type']]['category_name']) ? $category[$v['delivery_type']]['category_name'] : '暂无';
            $temp['delivery_count'] = $v['delivery_count'];
            $temp['delivery_income'] = $v['delivery_income'];
            $temp['delivery_time'] = $v['delivery_time'];
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
     * 获取筛选的投递总人数
     */
    public function actionDeliveryUserNum()
    {
        $machine_id = yii::$app->request->post('machine_id');
        $delivery_time = yii::$app->request->post('delivery_time');

        //查询条件预处理
        $where['del_flag'] = 0;
        $andWhere = [];
        empty($machine_id) || $where['machine_id'] = $machine_id;
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($delivery_time)) {
            list($start_time, $end_time) = explode(' - ', $delivery_time);
            $andWhere = ['between', 'delivery_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        $number = SrUserDeliveryHistoryParent::find()
            ->andWhere($where)
            ->andWhere($andWhere)
            ->asArray()
            ->groupBy('user_id')
            ->count();
        return $this->AjaxResult(SUCCESS,'获取成功',$number);
    }

    /**
     * 获取筛选的投递总环保金
     */
    public function actionDeliveryUserMoney()
    {
        $machine_id = yii::$app->request->post('machine_id');
        $delivery_time = yii::$app->request->post('delivery_time');
        //查询条件预处理
        $where['del_flag'] = 0;
        $andWhere = [];
        empty($machine_id) || $where['machine_id'] = $machine_id;
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($delivery_time)) {
            list($start_time, $end_time) = explode(' - ', $delivery_time);
            $andWhere = ['between', 'delivery_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        $income_amount = SrUserDeliveryHistoryParent::find()
            ->select('SUM(income_amount) AS total')
            ->andWhere($where)
            ->andWhere($andWhere)
            ->asArray()
            ->one()['total'];

        if ($income_amount != null){
            return $this->AjaxResult(SUCCESS,'获取成功',$income_amount);
        }
        return $this->AjaxResult(FAILD,'获取失败');
    }

    /**
     * 导出excel报表
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function actionDeliveryUserExcel()
    {
        $delivery_type = yii::$app->request->get('delivery_type');
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        //查询条件预处理
        $where['del_flag'] = 0;
        $andWhere = [];
        empty($machine_id) || $where['machine_id'] = $machine_id;
        empty($delivery_type) || $where['delivery_type'] = $delivery_type;
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($delivery_time)) {
            list($start_time, $end_time) = explode(' - ', $delivery_time);
            $andWhere = ['between', 'delivery_time', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }

        $child = SrUserDeliveryHistoryChild::find()
            ->select('machine_id,delivery_type,can_name,SUM(delivery_count) as count')
            ->where($where)
            ->andWhere($andWhere)
            ->orderBy('delivery_type')
            ->groupBy('can_name,machine_id')//双重排序，种类&小区
            ->asArray()
            ->all();
        $machine_id = array_column($child,'machine_id');
        $machine = Machine::getClass()->getMachineList(['id'=>$machine_id,'del_flag'=>0]);
        $machine = array_column($machine,NULL,'id');

        foreach ($child as &$value){
            $value['street_name'] = !empty($machine[$value['machine_id']]['street_name']) ? $machine[$value['machine_id']]['street_name'] : '未知';
            $value['community_name'] = !empty($machine[$value['machine_id']]['community_name']) ? $machine[$value['machine_id']]['community_name'] : '未知';
        }
        $name = '投递明细表' . $delivery_time;
        $key = ['street_name', 'community_name', 'can_name', 'count'];
        $head = ['街道     ', '小区        ', '类型', '总重量'];

        Excel::Export($name, $head, $child, $key);

    }

    /**
     *用户环保金明细页面
     */
    public function actionUserStatistics()
    {
        return $this->render('user-statistics', []);
    }

    /**
     * 用户环保金明细列表
     * @return string
     */
    public function actionAjaxUserStatistics()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        //分类明细筛选
        $andWhere = [];
        $phone_num = yii::$app->request->get('phone_num');
        $date_time = yii::$app->request->get('date_time');
        $getOrder = yii::$app->request->get('order');
        $where['del_flag'] = 0;
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $andWhere = ['between', 'create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }
        //排序
        switch ($getOrder){
            case 1 :
                $order = 'cumulative_income desc';
                break;
            case 2 :
                $order = 'cumulative_income asc';
                break;
            case 3 :
                $order = 'current_env_amount desc';
                break;
            case 4 :
                $order = 'current_env_amount asc';
                break;
            default :
                $order = ' cumulative_income desc';
                break;
        }

        if (!empty($phone_num)) {
            $user_id = Users::getClass()->getUserList(['phone_num'=>$phone_num]);
            $user_id = array_column($user_id, 'id');
            $where['user_id'] = $user_id;
        }

        $statistic = SrUserUsageStatistics::find()
            ->select('id,user_id,cumulative_income,current_env_amount,create_date')
            ->andWhere($where)
            ->andWhere($andWhere)
            ->asArray()
            ->offset($start)
            ->limit($length)
            ->orderBy($order)
            ->all();

        $total = SrUserUsageStatistics::find()
            ->andWhere($where)
            ->andWhere($andWhere)
            ->count();

        $uid = array_column($statistic,'user_id');
        $user = Users::getClass()->getUserList(['id'=>$uid]);
        $user = array_column($user,NULL,'id');
        //待审金额
        $c_where = ['user_id'=>$uid,'del_flag' => 0,'declarable_status'=>0];
        $child_info = SrUserDeliveryHistoryChild::find()
            ->select('sum(delivery_income) as delivery_income,user_id')
            ->where($c_where)
            ->andWhere(['>','delivery_income',0])
            ->groupBy('user_id')
            ->asArray()
            ->all();
        $child_info = array_column($child_info,NULL,'user_id');
        //生成dataTable格式数据
        $data = [];
        foreach ($statistic as $val){
            $temp = [];
            $temp['id'] =  $val['user_id'];
            $temp['phone_num'] =  !empty($user[$val['user_id']]['phone_num']) ? $user[$val['user_id']]['phone_num'] : "该用户不存在";
            $temp['nick_name'] = !empty($user[$val['user_id']]['nick_name']) ? $user[$val['user_id']]['nick_name'] : "该用户不存在";
            $temp['cumulative_income'] =  $val['cumulative_income'];
            $temp['current_env_amount'] =  $val['current_env_amount'];
            $temp['abnormal_income'] = !empty($child_info[$val['user_id']]['delivery_income']) ? $child_info[$val['user_id']]['delivery_income'] : '0.00';
            $temp['create_date'] =  $val['create_date'];
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
     * 导出报表
     */
    public function actionUserStatisticsExcel()
    {
        //分类明细筛选
        $andWhere = [];
        $phone_num = yii::$app->request->get('phone_num');
        $date_time = yii::$app->request->get('date_time');
        $getOrder = yii::$app->request->get('order');
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $andWhere = ['between', 'create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }
        //默认导出一个月
//        if (empty($andWhere)){
//            $defaultDate = date('Y-m-d',strtotime(date('Y-m')));
//            $andWhere = ['between','create_date',$defaultDate.' 00:00:00', date('Y-m-d').' 23:59:59'];
//        }
        if (!empty($phone_num)) {
            $user_id = Users::getClass()->getUserList(['phone_num'=>$phone_num]);
            $user_id = array_column($user_id, 'id');
            $where['user_id'] = $user_id;
        }
        //排序
        switch ($getOrder){
            case 1 :
                $order = 'cumulative_income desc';
                break;
            case 2 :
                $order = 'cumulative_income asc';
                break;
            case 3 :
                $order = 'current_env_amount desc';
                break;
            case 4 :
                $order = 'current_env_amount asc';
                break;
            default :
                $order = ' cumulative_income desc';
                break;
        }

        $statistic = SrUserUsageStatistics::find()
            ->select('id,user_id,cumulative_income,current_env_amount,create_date')
            ->andWhere($where)
            ->andWhere($andWhere)
            ->andWhere(['>','cumulative_income',0])
            ->asArray()
            ->orderBy($order)
            ->all();
        $uid = array_column($statistic,'user_id');
        $user = Users::getClass()->getUserList(['id'=>$uid]);
        $user = array_column($user,NULL,'id');

        //待审
        $c_where = ['user_id'=>$uid,'del_flag' => 0,'declarable_status'=>0];
        $child_info = SrUserDeliveryHistoryChild::find()
            ->select('sum(delivery_income) as delivery_income,user_id')
            ->where($c_where)
            ->andWhere(['>','delivery_income',0])
            ->groupBy('user_id')
            ->asArray()
            ->all();

        $child_info = array_column($child_info,NULL,'user_id');
        //生成dataTable格式数据
        $getList = function ($statistic) use($user,$child_info){
            foreach ($statistic as $key=>$val){
                $temp = [];
                $temp['id'] =  $val['user_id'];
                $temp['phone_num'] =  !empty($user[$val['user_id']]['phone_num']) ? $user[$val['user_id']]['phone_num'] : "该用户不存在";
                $nick_name = !empty($user[$val['user_id']]['nick_name']) ? $user[$val['user_id']]['nick_name'] : "该用户不存在";
                $temp['nick_name'] =  $this->removeEmoji($nick_name);//特殊字符导出过滤
                $temp['cumulative_income'] =  $val['cumulative_income'];
                $temp['current_env_amount'] =  $val['current_env_amount'];
                $temp['abnormal_income'] = empty($child_info[$val['user_id']]['delivery_income']) ? '0.00' : $child_info[$val['user_id']]['delivery_income'];
                $temp['create_date'] =  $val['create_date'];
                yield $temp;
            }
        };


        $name = '环保金统计明细' . time();
        $key = ['id','phone_num', 'nick_name', 'cumulative_income',
            'current_env_amount', 'abnormal_income','create_date'];
        $head = ['用户ID', '用户手机号', '昵称', '环保金累计金额', '当前可提现金额', '当前待审核金额','注册时间'];
        Excel::Export($name, $head, $getList($statistic), $key);
    }

    /**
     * 用户提现列表页面
     */
    public function actionUserOrderWithdraw()
    {
        return $this->render('user-order-withdraw', []);
    }

    /**
     * 用户提现列表
     * @return string
     */
    public function actionAjaxUserOrderWithdraw()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        $where['del_flag'] = 0;
        $phone_num = yii::$app->request->get('phone_num');
        $status = yii::$app->request->get('status');
        empty($status) || $where['order_status'] = $status;
        if (!empty($phone_num)){
            $user_id = Users::getClass()->getUserList(['phone_num'=>$phone_num]);
            $user_id = array_column($user_id, 'id');
            $where['user_id'] = $user_id;
        }

        $order = SrUserWithdrawOrder::find()
            ->select('id,user_id,withdraw_amount,order_num,order_status,order_reason,order_create_date,order_update_date')
            ->where($where)
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        $total = SrUserWithdrawOrder::find()
            ->where($where)
            ->count();

        $uid = array_column($order,'user_id');
        $user = Users::getClass()->getUserList(['id'=>$uid]);
        $user = array_column($user,NULL,'id');

        $data = [];
        $status = Yii::$app->params['OrderStatus'];
        foreach ($order as $val){
            $temp = [];
            $temp['nick_name'] = !empty($user[$val['user_id']]['nick_name']) ? $user[$val['user_id']]['nick_name'] : "该用户不存在";
            $temp['phone_num'] = !empty($user[$val['user_id']]['phone_num']) ? $user[$val['user_id']]['phone_num'] : "该用户不存在";
            $temp['order_num'] = $val['order_num'];
            $temp['withdraw_amount'] = $val['withdraw_amount'];
            $temp['order_status'] = $status[$val['order_status']];
            $temp['order_reason'] = $val['order_reason'];
            $temp['order_create_date'] = $val['order_create_date'];
            $temp['order_update_date'] = $val['order_update_date'];
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
     * 导出提现报表
     */
    public function actionUserWithdrawExcel()
    {
        //分类明细筛选
        $andWhere = [];
        $date_time = yii::$app->request->get('date_time');
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $andWhere = ['between', 'order_create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }
        $withdraw = SrUserWithdrawOrder::find()
            ->select('user_id,order_num,withdraw_amount,order_update_date')
            ->where(['del_flag'=>0,'order_status'=>1])
            ->andWhere($andWhere)
            //->orderBy('id desc')
            ->asArray()
            ->all();
        $userids = array_unique(array_column($withdraw,'user_id'));
        $srUser = SrUser::find()
            ->select('id, nick_name, phone_num')
            ->where(['in','id',$userids])
            ->asArray()
            ->all();

        $user_temp = array_column($srUser,null,'id');
        //生成dataTable格式数据
        $data = [];

        foreach ($withdraw as $key=>$val){
            /*if($val['user_id']==1439){
                print_r($user_temp[$val['user_id']]);exit;
            }*/
            $data[$key]['user_id'] =  $val['user_id'];
            $data[$key]['nick_name'] =  empty($user_temp[$val['user_id']]['nick_name'])?'':$this->removeEmoji($user_temp[$val['user_id']]['nick_name']);
            $data[$key]['phone_num'] =  empty($user_temp[$val['user_id']]['phone_num'])?'':$user_temp[$val['user_id']]['phone_num'];
            $data[$key]['order_num'] =  $val['order_num'];
            $data[$key]['withdraw_amount'] =  $val['withdraw_amount'];
            $data[$key]['order_update_date'] =  $val['order_update_date'];
        }

        $name = '环保金发放统计报表' . $start_time.'-'.$end_time;
        $key = ['user_id','nick_name','phone_num','order_num','withdraw_amount','order_update_date'];
        $head = ['用户ID','用户昵称','用户手机号', '提现订单号', '提现金额', '提现时间'];
        Excel::Export($name, $head, $data, $key);
    }

    /**
     * 每月小区排名列表页面
     */
    public function actionVillageRank()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;

        $street = Machine::getClass()->getMachineColumn('street_name',$where);

        return $this->render('village-rank', ['street'=>$street]);
    }

    /**
     * 每月小区排名列表数据
     */
    public function actionAjaxVillageRank()
    {
        $where['del_flag'] = 0;
        $date_time = yii::$app->request->get('date_time');
        $street_name = yii::$app->request->get('street_name');
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (empty($date_time) || empty($street_name)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            die(json_encode($data_source));
        }
        $where['month'] = $date_time;
        $machine = Machine::getClass()->getMachineList(['street_name'=>$street_name,'del_flag'=>0]);
        $where['village_id'] = array_column($machine,'position_village_id');
        $limit = 5;
        $order = SrUserVillageRankMonth::find()
            ->select('SUM(delivery_income) AS income,SUM(delivery_count) AS count,
                              SUM(delivery_weight) AS weight,village_id,')
            ->where($where)
            ->asArray()
            ->groupBy('village_id')
            ->limit($limit)
            ->orderBy('income desc')
            ->all();

        $village_id = array_column($order,'village_id');
        $village = Village::getClass()->getVillageList(['p_id'=>$village_id]);
        $village = array_column($village,'village_name','p_id');

        $data = [];
        foreach ($order as $key => $val){
            $temp = [];
            $temp['rank'] = $key + 1;
            $temp['village_name'] = $village[$val['village_id']];
            $temp['street_name'] = $street_name;
            $temp['income'] = $val['income'];
            $temp['weight'] = $val['weight'];
            $temp['count'] = $val['count'];
            array_push($data, array_values($temp));
        }
        $total = count($data);
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
     * 投递数据统计列表
     */
    public function actionDeliveryCount()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $street = Machine::getClass()->getMachineColumn('street_name',$where);

        return $this->render('delivery-count', ['street'=>$street]);
    }

    /**
     * 投递数据统计数据
     */
    public function actionAjaxDeliveryCount()
    {
        //获取页码与数据长度
        list($start, $length) = $this->getOffset();
        $date_time = yii::$app->request->get('date_time');
        $street_name = yii::$app->request->get('street_name');
        $community_name = yii::$app->request->get('community_name');

        if (empty($date_time) || empty($street_name)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            die(json_encode($data_source));
        }
        $andWhere = [];
        $where = [];
        $this->user_admin && $where['agent'] = $this->agent_id;
        if (!empty($date_time)) {
            list($start_time, $end_time) = explode(' - ', $date_time);
            $andWhere = ['between', 'create_date', $start_time . ' 00:00:00', $end_time . ' 23:59:59'];
        }
        empty($street_name) || $where['street_name'] = $street_name;
        empty($community_name) || $where['community_name'] = $community_name;

        $statistic = SrUserDeliveryDayStatistic::find()
            ->select('county_name, street_name, community_name,sum(category_1) as category_1,
               sum(category_2) as category_2, sum(category_3) as category_3,sum(category_4) as category_4 
               ,sum(category_5) as category_5, sum(category_6) as category_6, sum(category_7) as category_7')
            ->where($where)
            ->andWhere($andWhere)
            ->offset($start)
            ->limit($length)
            ->groupBy('community_name')
            ->asArray()
            ->all();

        $total = SrUserDeliveryDayStatistic::find()
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy('community_name')
            ->count();
        $data = [];
        $sum_data = [//总重
            'column_0' => '<b>各 项 总 计</b>',
            'column_1' => '<b>— —</b>',
            'column_2' => '<b>— —</b>',
            'column_3' => '<b>— —</b>',
            'category_1' => 0,
            'category_2' => 0,
            'category_3' => 0,
            'category_5' => 0,
            'category_4' => 0,
            'category_6' => 0,
            'category_7' => 0,
            'plus' => 0,
            'category_11' => 0,
        ];
        foreach ($statistic as $val) {
            //格式化dataTable数据
            $category_11 = $val['category_1'];
            $val['category_1'] = bcdiv($val['category_1'],45,2);
            $val['plus'] = round(array_sum($val),2);
            $val['category_11'] = $category_11;
            $data[] = [
                $date_time,
                $val['county_name'],
                $val['street_name'],
                $val['community_name'],
                $val['category_1'],
                $val['category_2'],
                $val['category_3'],
                $val['category_5'],
                $val['category_4'],
                $val['category_6'],
                $val['category_7'],
                $val['plus'],
                $val['category_11'],
            ];
            foreach ($val as $k => $v) {
                isset($sum_data[$k]) && $sum_data[$k] = bcadd($sum_data[$k], $v, 2);
            }
        }
        //合并分类总计到结果集
        array_push($data, array_values($sum_data));
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
     * 根据街道/镇获取小区名称
     * @return mixed
     */
    public function actionGetDeliveryCommunity()
    {
        $street = Yii::$app->request->post('street');
        $comunity = Machine::getClass()->getMachineColumn('community_name',['del_flag'=>0,'street_name'=>$street]);

        return $this->AjaxResult(SUCCESS, '获取成功', $comunity);
    }

    /**
     * 过滤emoji
     * @param $text
     * @return string
     */
    public function removeEmoji($text){
        $len = mb_strlen($text);
        $newText = '';
        for($i=0;$i<$len;$i++){
            $str = mb_substr($text, $i, 1, 'utf-8');
            if(strlen($str) >= 4) continue;//emoji表情为4个字节
            $newText .= $str;
        }
        return $newText;
    }


    /**
     * 首页企业账户信息
     */
    public function actionAjax_account()
    {
        //获取账户余额
        $balance = $this->user['balance'];
        $where = ['a.agent' => $this->agent_id, 'b.order_status' => 1];

        if ($this->user['admin'] === 0) {
            $balance = Agent::find()
                ->select(["IFNULL(SUM(balance), 0) AS balance"])
                ->asArray()
                ->one()['balance'];
            $where = ['b.order_status' => 1];
        }

        //获取累计发放环保金
        $andWhere = ['>', 'a.withdraw_id', 1];
        $res = SrUserDeliveryHistoryChild::find()
            ->select(["IFNULL(SUM(delivery_income), 0) AS income"])
            ->from(SrUserDeliveryHistoryChild::tableName() . ' AS a')
            ->leftJoin(SrUserWithdrawOrder::tableName() . ' AS b', 'a.withdraw_id = b.id')
            ->where($where)
            ->andWhere($andWhere)
            ->asArray()
            ->one();

        //组装数据
        $data = [
            'balance' => $balance,
            'income' => $res['income']
        ];
        return json_encode($data);
    }

    /**
     * 首页获取用户投递数据
     */
    public function actionAjax_delivery()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取条件参数并组装条件值
        $phone_num = yii::$app->request->get('phone_num');
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $where = $andWhere = [];
        $this->user_admin && $where = ['a.agent' => $this->agent_id];
        //短路运算,第一个为真就不会执行第二个
        empty($machine_id) || $where['a.machine_id'] = $machine_id;
        empty($phone_num) || $where['c.phone_num'] = $phone_num;
        if (!empty($delivery_time)) {
            $start = $delivery_time . ' 00:00:00';
            $end = $delivery_time . ' 23:59:59';
            $andWhere = ['between', 'a.delivery_time', $start, $end];
        }

        //查询投递数据列表
        $userDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->select('d.phone_num, b.province_name, b.county_name, a.can_name, a.delivery_income, c.number_of_delivery, c.cumulative_income')
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrRecyclingMachine::tableName() . ' b', 'a.machine_id = b.id')
            ->leftJoin(SrUserUsageStatistics::tableName() . ' c', 'a.user_id = c.user_id')
            ->leftJoin(SrUser::tableName() . ' d', 'a.user_id = d.id')
            ->where($where)
            ->andWhere($andWhere)
            ->orderBy(['a.id' => SORT_DESC])
            ->offset($offset)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrUserDeliveryHistoryChild::find()
            ->select('a.id, c.id')
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrUser::tableName() . ' c', 'a.user_id = c.id')
            ->where(['!=', 'a.machine_id', 0])
            ->andWhere($where)->andWhere($andWhere)->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($userDeliveryHistoryChild as $item) {
            $v['phone_num'] = $item['phone_num'];
            $v['province_name'] = $item['province_name'];
            $v['county_name'] = $item['county_name'];
            $v['can_name'] = $item['can_name'];
            $v['delivery_income'] = $item['delivery_income'];
            $v['number_of_delivery'] = $item['number_of_delivery'];
            $v['cumulative_income'] = $item['cumulative_income'];
            array_push($data, array_values($v));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_delivery', ['data' => json_encode($data_source)]);
    }


    /**
     * 首页获取各品类回收量
     */
    public function actionAjax_category()
    {
        //查询可计算品类
        $srRubbishCategory = SrRubbishCategory::find()
            ->select('id, category_name')
            ->where(['category_type' => 0])->asArray()->all();

        //初始化品类数据结构
        foreach ($srRubbishCategory as & $c) {
            $c['day'] = 0;
            $c['yesterday'] = 0;
            $c['week'] = 0;
            $c['month'] = 0;
            $c['year'] = 0;
            $c['total'] = 0;
        }

        //初始化各个时间维度区间
        $date = [
            'day' => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
            'yesterday' => [date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day'))],
            'week' => [date('Y-m-d 00:00:00', strtotime((date('N') - 1) * -1 . ' day')), date('Y-m-d 23:59:59')],
            'month' => [date('Y-m-01 00:00:00'), date('Y-m-d 23:59:59')],
            'year' => [date('Y-01-01 00:00:00'), date('Y-m-d 23:59:59')],
            'total' => []
        ];

        //查询各时间维度区间的品类数量
        foreach ($date as $k => $v) {
            $this->agent_id ? $where = ['agent' => $this->agent_id] : $where = [];
            $andWhere = [];
            if (!empty($v)) {
                $andWhere = ['BETWEEN', 'delivery_time', $v[0], $v[1]];
            }

            $result = SrUserDeliveryHistoryChild::find()
                ->select('delivery_type, SUM(delivery_count) as delivery_count')
                ->where($where)->andWhere($andWhere)
                ->groupBy('delivery_type')
                ->asArray()->all();

            //将获取的结果组合到品类数据结构
            foreach ($srRubbishCategory as $k1 => & $v1) {
                foreach ($result as $v2) {
                    if ($v1['id'] == $v2['delivery_type']) {
                        $v1[$k] = $v2['delivery_count'];
                    }
                }
            }
        }

        //生成dataTable格式数据
        $data = [];
        foreach ($srRubbishCategory as $item) {
            unset($item['id']);
            array_push($data, array_values($item));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];
        return $this->renderAjax('ajax_delivery', ['data' => json_encode($data_source)]);

    }

    /**
     * 首页获取回收清运数据
     */
    public function actionAjax_recycle()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取条件参数并组装条件值
        $where = [];
        $andWhere = ['>', 'a.machine_id', 0];
        $this->user_admin && $where = ['a.agent' => $this->agent_id];

        //查询清运数据列表
        $srRecyclingHistoryChild = SrRecyclingHistoryChild::find()
            ->select('a.create_date, d.nick_name, b.province_name, b.community_name, c.category_name, a.recycling_amount')
            ->from(SrRecyclingHistoryChild::tableName() . ' a')
            ->leftJoin(SrRecyclingMachine::tableName() . ' b', 'a.machine_id = b.id')
            ->leftJoin(SrRubbishCategory::tableName() . ' c', 'a.category = c.id')
            ->leftJoin(SrRecycler::tableName() . ' d', 'a.recycler_id = d.id')
            ->where($where)
            ->andWhere($andWhere)
            ->orderBy(['a.id' => SORT_DESC])
            ->offset($offset)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrRecyclingHistoryChild::find()
            ->select('a.id')
            ->from(SrRecyclingHistoryChild::tableName() . ' a')
            ->where($where)
            ->andWhere($andWhere)->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srRecyclingHistoryChild as $v) {
            array_push($data, array_values($v));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_recycle', ['data' => json_encode($data_source)]);
    }

    /**
     * 首页获取设备满箱状态
     */
    public function actionAjax_full_can()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //设置条件参数列表
        $where = [];
        $this->user_admin && $where = ['b.agent' => $this->agent_id];
        $having = ['>=', 'w_rate', 80];
        $orHaving = ['>=', 'q_rate', 80];
        $orderBy = ['w_rate' => SORT_DESC, 'q_rate' => SORT_DESC];

        $SrTrashCan = SrTrashCan::find()
            ->select('b.device_id, b.province_name, b.community_name, a.can_name, ROUND(a.weight * 100 / a.max_weight) AS w_rate, ROUND(a.quantity * 100 / a.max_quantity) AS q_rate, d.nick_name, d.phone_num')
            ->from(SrTrashCan::tableName() . ' a')
            ->leftJoin(SrRecyclingMachine::tableName() . ' b', 'a.machine_id = b.id')
            ->leftJoin(SrRecyclerMachineRel::tableName() . ' c', 'a.machine_id = c.machine_id')
            ->leftJoin(SrRecycler::tableName() . ' d', 'c.recycler_id = d.id')
            ->where($where)
            ->having($having)
            ->orHaving($orHaving)
            ->orderBy($orderBy)->asArray()->all();

        //生成dataTable格式数据
        $data = [];
        for ($i = $offset; $i < $offset + $length; $i++) {
            if (empty($SrTrashCan[$i])) {
                break;
            }
            $SrTrashCan[$i]['w_rate'] > 100 && $SrTrashCan[$i]['w_rate'] = 100;
            $SrTrashCan[$i]['q_rate'] > 100 && $SrTrashCan[$i]['q_rate'] = 100;
            $SrTrashCan[$i]['w_rate'] .= '%';
            $SrTrashCan[$i]['q_rate'] .= '%';
            array_push($data, array_values($SrTrashCan[$i]));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => count($SrTrashCan),
            'recordsFiltered' => count($SrTrashCan),
            'data' => $data
        ];
        return $this->renderAjax('ajax_recycle', ['data' => json_encode($data_source)]);
    }

    /**
     * 抓取清运人员数据
     */
    public function actionAjax_recycler()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        $srRecycler = SrRecycler::find()
            ->select('id, nick_name, phone_num, recycler_status, cooperation_start_time, cooperation_end_time')
            ->where(['del_flag' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->offset($start)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrRecycler::find()
            ->where(['del_flag' => 0])->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srRecycler as $v) {
            array_push($data, array_values($v));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_recycler', ['data' => json_encode($data_source)]);
    }

    /**
     * 首页图表与图表总计
     */
    public function actionAjax_chart()
    {
        //获取图表请求参数
        $period = yii::$app->request->get('period');
        $charts_type = yii::$app->request->get('charts_type');
        empty($period) && $period = 'day';
        empty($charts_type) && $charts_type = 1;
        $data = [];
        $start = $end = '';

        //初始化近7天或当月日期的数据结构
        if (empty($period) || $period == 'day') {
            for ($i = 7; $i > 0; $i--) {
                $data[date('Y-m-d', strtotime('-' . $i . ' day'))] = 0;
            }
        } else {
            $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            for ($i = 0; $i < $days; $i++) {
                $data[date('Y-m-d', strtotime(date('Y-m-01')) + $i * 86400)] = 0;
            }
        }

        //计算查询的起始时间和结束时间
        $days = array_keys($data);
        $start = reset($days) . ' 00:00:00';
        $end = end($days) . ' 23:59:59';

        //查询对应类别的数据
        $where = ['BETWEEN', 'create_date', $start, $end];
        $this->user_admin ? $andWhere = ['agent' => $this->agent_id] : $andWhere = [];

        //投递次数
        $delivery = SrUserDeliveryHistoryChild::find()
            ->select(["COUNT(*) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)->andWhere($andWhere)
            ->groupBy('date')->asArray()->all();

        //投递人数
        $person = SrUserDeliveryHistoryChild::find()
            ->select(["COUNT(DISTINCT user_id) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)->andWhere($andWhere)
            ->groupBy('date')->asArray()->all();

        //投递重量
        $weight = SrUserDeliveryHistoryChild::find()
            ->select(["SUM(delivery_count) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)->andWhere($andWhere)->andWhere(['!=', 'delivery_type', 1])
            ->groupBy('date')->asArray()->all();

        //清运重量
        $recycle = SrRecyclingHistoryChild::find()
            ->select(["COUNT(*) AS count, DATE_FORMAT(create_date, '%Y-%m-%d') AS date"])
            ->where($where)->andWhere($andWhere)->andWhere(['!=', 'category', 1])
            ->groupBy('date')->asArray()->all();

        //得到请求参数对应的折线图数据并组装到数据结构
        $res = [];
        $charts_type == 1 && $res = $delivery;
        $charts_type == 2 && $res = $person;
        $charts_type == 3 && $res = $weight;
        $charts_type == 4 && $res = $recycle;
        foreach ($data as $k => & $d) {
            foreach ($res as $v) {
                if ($v['date'] == $k) {
                    $d += $v['count'];
                }
            }
        }

        //组装参数对应的总计数据
        $delivery_sum = $person_sum = $weight_sum = $recycle_sum = 0;
        $delivery_sum = array_sum(array_column($delivery, 'count'));
        $person_sum = array_sum(array_column($person, 'count'));
        $weight_sum = round(array_sum(array_column($weight, 'count')), 2);
        $recycle_sum = array_sum(array_column($recycle, 'count'));

        $result = [
            'data' => $data,
            'delivery_sum' => $delivery_sum,
            'person_sum' => $person_sum,
            'weight_sum' => $weight_sum,
            'recycle_sum' => $recycle_sum,
        ];
        return json_encode($result);
    }

    /**
     * 首页历史总计
     */
    public function actionAjax_sum()
    {
        //获取铺设设备的总数据
        $this->user_admin ? $where = ['agent' => $this->agent_id] : $where = [];
        $machine = SrRecyclingMachine::find()
            ->select(["COUNT(*) * 5 AS `count`, DATE_FORMAT(create_date, '%Y-%m-%d') AS `date`"])
            ->where($where)
            ->groupBy('date')->asArray()->all();
        $machine = array_column($machine, 'count');
        $machine_sum = array_sum($machine);

        //获取总投递次数
        $delivery = SrUserDeliveryHistoryChild::find()
            ->select(["COUNT(*) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)
            ->groupBy('date')->asArray()->all();
        $delivery = array_column($delivery, 'count');
        $delivery_sum = array_sum($delivery);

        //获取总投递人数
        $person = SrUserDeliveryHistoryChild::find()
            ->select(["COUNT(DISTINCT user_id) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)
            ->groupBy('date')->asArray()->all();
        $person = array_column($person, 'count');
        $person_sum = array_sum($person);

        //获取总投递重量
        $weight = SrUserDeliveryHistoryChild::find()
            ->select(["SUM(delivery_count) AS count, DATE_FORMAT(delivery_time, '%Y-%m-%d') AS date"])
            ->where($where)->andWhere(['!=', 'delivery_type', 1])
            ->groupBy('date')->asArray()->all();
        $weight = array_column($weight, 'count');
        $weight_sum = round(array_sum($weight), 2);

        //废料与无害化处理数据
        $coal = $burn = $harmless = $bury = $redd = $reduce = 0;

        //组装数据
        $data = [
            'charts' => [$machine, $delivery, $person, $weight],
            'sum' => [$machine_sum, $delivery_sum, $person_sum, $weight_sum],
            'ep' => [$coal, $burn, $harmless, $bury, $redd, $reduce]
        ];

        return json_encode($data);
    }

    /**
     * 企业中心获取代理商交易明细
     */
    public function actionAjax_agent_trade()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取参数
        $type = yii::$app->request->get('type');
        $date = yii::$app->request->get('date');

        //初始化查询条件
        $where = $andWhere = [];
        $this->agent_id && $where['agent'] = $this->agent_id;
        $type && $where['type'] = $type;
        $date && $andWhere = ['BETWEEN', 'create_time', $date . ' 00:00:00', $date . ' 23:59:59'];


        //查询账户明细
        $res = SrAgentTradeHistory::find()
            ->where($where)
            ->andWhere($andWhere)
            ->orderBy(['id' => SORT_DESC])
            ->offset($offset)->limit($length)->asArray()->all();

        //获取结果集数量
        $total = SrAgentTradeHistory::find()
            ->where($where)
            ->andWhere($andWhere)->count();

        $data = [];
        $status = Yii::$app->params['AgentTradeHistory'];
        foreach ($res as $v) {
            //判断type类型初始化
            $opt = $content = '— —';
            switch ($v['type']) {
                case 1:
                    $opt = '收入';
                    $content = '账户充值';
                    break;
                case 2:
                    $opt = '支出';
                    $content = '环保金支出';
                    break;
            }
            $tmp = [
                'opt' => $opt,
                'amount' => $v['amount'],
                'content' => $content,
                'status' => $status[$v['status']],
                'date' => $v['create_time']
            ];
            array_push($data, array_values($tmp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_delivery', ['data' => json_encode($data_source)]);
    }

    /**
     * 汇款通知记录
     */
    public function actionAjax_remit()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //初始化查询条件
        $where['type'] = 1;
        $this->agent_id && $where['agent'] = $this->agent_id;

        //查询账户明细
        $res = SrAgentTradeHistory::find()
            ->where($where)
            ->orderBy(['id' => SORT_DESC])
            ->offset($offset)->limit($length)->asArray()->all();

        //获取结果集数量
        $total = SrAgentTradeHistory::find()
            ->where($where)->count();

        //格式化datatable数据
        $data = [];
        $status = Yii::$app->params['AgentTradeHistory'];
        foreach ($res as $v) {
            $tmp = [
                'date' => $v['create_time'],
                'bank_name' => $v['bank_name'],
                'amount' => $v['amount'],
                'status' => $status[$v['status']]
            ];
            array_push($data, array_values($tmp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_delivery', ['data' => json_encode($data_source)]);
    }

}