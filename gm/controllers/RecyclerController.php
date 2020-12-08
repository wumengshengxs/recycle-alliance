<?php

namespace gm\controllers;

use common\models\AdminLog;
use common\models\Machine;
use common\models\Recycler;
use common\models\Village;
use gm\models\SrRecycler;
use gm\models\SrRecyclerMachineRel;
use gm\models\SrRecyclingMachine;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class RecyclerController extends GController
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
     * 回收员管理页面
     * @return string
     */
    public function actionRecyclerList()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $like_Where = [];
        $recycler = yii::$app->request->get('recycler');
        empty($recycler) || $like_Where  = ['like', 'nick_name', $recycler];;

        //回收员
        $recycler = SrRecycler::find()
            ->select('id,nick_name,phone_num,agent,type')
            ->where(['recycler_status'=>1])
            ->andWhere($where)
            ->andWhere($like_Where)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //小区
        $village = Village::getClass()->getVillage($where);

        return $this->render('recycler-list', ['recycler' => $recycler,'village'=>$village]);
    }

    /**
     * 获取页面
     */
    public function actionAjax_recycler_list()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $recycler_id = yii::$app->request->get('recycler_id');
        $village_id = yii::$app->request->get('village_id');
        $device_id = yii::$app->request->get('device_id');
        $where['m.del_flag'] = 0;
        $where['r.del_flag'] = 0;
        empty($recycler_id) || $where['r.recycler_id']   = $recycler_id;
        empty($village_id) || $where['m.position_village_id'] = $village_id;
        empty($device_id) || $where['m.device_id'] = $device_id;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //机器
        $machine = SrRecyclingMachine::find()
            ->alias('m')
            ->select('m.device_id,m.community_name,m.location,r.recycler_id')
            ->join('LEFT JOIN', 'sr_recycler_machine_rel as r', 'm.id = r.machine_id')
            ->where($where)
            ->offset($start)
            ->limit($length)
            ->orderBy('m.id desc')
            ->asArray()
            ->all();
        //获取记录总数
        $total = SrRecyclingMachine::find()
            ->alias('m')
            ->join('LEFT JOIN', 'sr_recycler_machine_rel as r', 'm.id = r.machine_id')
            ->where($where)
            ->asArray()
            ->count();
        //回收员
        $rid = array_column($machine,'recycler_id');
        $recycler = Recycler::getClass()->getRecyclerList(['del_flag'=>0,'recycler_status'=>1,'id'=>$rid]);
        $recycler = array_column($recycler,NULL,'id');
        $type = Yii::$app->params['Recycler'];
        $data = [];
        foreach ($machine as $val){
            $temp = [];
            $temp['device_id'] = $val['device_id'];
            $temp['community_name'] = $val['community_name'];
            $temp['location'] = $val['location'];
            $temp['recycler'] = $recycler[$val['recycler_id']]['nick_name'];//nickname
            $temp['type'] = $type[$recycler[$val['recycler_id']]['type']];//type
            array_push($data, array_values($temp));
        }

        /**
         * 判断超级管理员和机动队员
         */
        if (!empty($recycler_id) && empty($machine)){
            $recycler = Recycler::getClass()->getRecycler(['del_flag'=>0,'recycler_status'=>1,'id'=>$recycler_id]);
            if (!empty($recycler) && ($recycler['type'] == 1 || $recycler['type'] == 2)){
                $temp = [];
                $temp['device_id'] = '所有回收机';
                $temp['community_name'] = '所有小区';
                $temp['location'] = '所有小区';
                $temp['maintain'] = $recycler['nick_name'];//nickname
                $temp['type'] = $type[$recycler['type']];//type
                array_push($data, array_values($temp));
            }

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
     */
    public function actionRecycler_add()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $mid = $param['id'];
            $time = date('Y-m-d H:i:s');

            //判断
            $phone = Recycler::getClass()->getRecycler(['phone_num'=>$param['phone_num']]);
            if (!empty($phone)){

                return $this->AjaxResult(FAILD,'存在相同的手机号');
            }
            $data['SrRecycler'] = [
                'nick_name' => $param['nick_name'],
                'phone_num' => $param['phone_num'],
                'agent' => $this->user['id'],
                'avatar_img' => '',
                'type' => $param['type'],
                'recycler_status' => $param['recycler_status'],
                'create_date' => $time,
                'update_date' => $time,
                'password' => 'e4aed93ec8e0084edaef0cb945aa5acb885792dea7c115f6de9a96c77df0ca617761738c76e965f18aafc30eccbe0dacc9ec1788a7a1bbc0d6ef59b98047c099',
                'del_flag' => '0',
                'balance' => 500,
                'cooperation_start_time' => $time,
                'cooperation_end_time' => date('Y-m-d H:i:s',(time()+(60*60*24*365)))
            ];
            $Recycler = new SrRecycler();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //添加记录到主表
                if (!$Recycler->load($data) || !$Recycler->save()){
                    return $this->AjaxResult(FAILD,'添加失败',$Recycler->getErrors());
                }

                if ($param['type'] == 0 && !empty($mid)){
                    $machine = [];
                    //添加关系表
                    for ($i = 0,$j = count($mid); $i < $j; $i++){
                        $machine[$i] = [
                            'recycler_id' => $Recycler->id,
                            'machine_id' => $mid[$i],
                            'create_date' => $time,
                            'update_date' => $time,
                            'del_flag' => 0
                        ];
                    }
                    //删除符合的关系
                    SrRecyclerMachineRel::deleteAll(['machine_id'=>$mid]);
                    //执行批量添加
                    $key = ['recycler_id','machine_id', 'create_date', 'update_date', 'del_flag'];
                    Yii::$app->db->createCommand()->batchInsert(SrRecyclerMachineRel::tableName(), $key, $machine)->execute();
                }
                $info = '运营商: '.$this->user['company_name'].'添加回收员'.$param['nick_name'];
                AdminLog::getClass()->addLog('添加回收员',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS,'添加成功');
            }catch (\Exception $e){
                $transaction->rollBack();

                return $this->AjaxResult(FAILD,'网络错误',$e->getMessage());
            }

        }else{//get
            $where['m.del_flag'] = 0;
            $this->user_admin && $where['agent'] = $this->agent_id;
            $machine = Machine::getClass()->getRecyclingMachineRel($where);

            //回收员
            $rid = array_filter(array_unique(array_column($machine,'recycler_id')));
            $recycler = Recycler::getClass()->getRecyclerList(['del_flag'=>0,'recycler_status'=>1,'id'=>$rid]);
            $recycler = array_column($recycler,NULL,'id');

            $data = [];
            foreach ($machine as $key=>$val){
                $data[$key]['id'] = $val['id'];
                $data[$key]['community_name'] = $val['community_name'];
                $data[$key]['location'] = $val['location'];
                $nick_name =  !empty($recycler[$val['recycler_id']]['nick_name']) ? $recycler[$val['recycler_id']]['nick_name'] : "";//nickname
                $data[$key]['recycler'] = $nick_name;
            }

            return $this->renderPartial('recycler_add', [
                'machine' => $data,
            ]);

        }

    }
}
