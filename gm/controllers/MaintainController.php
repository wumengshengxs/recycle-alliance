<?php

/**
 * 维修人员
 */
namespace gm\controllers;


use common\models\AdminLog;
use common\models\Machine;
use common\models\Maintain;
use common\models\Village;
use gm\models\SrMaintain;
use gm\models\SrMaintainMachineRel;
use gm\models\SrRecyclingMachine;
use Exception;
use Yii;
use yii\filters\AccessControl;

class MaintainController extends GController
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
     * 维修人员列表
     * @return string
     */
    public function actionList()
    {
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $like_Where = [];
        $maintain = Yii::$app->request->get('maintain');
        empty($maintain) || $like_Where  = ['like', 'nick_name', $maintain];;

        //维修员
        $maintain = SrMaintain::find()
            ->select('id,nick_name,phone_num,agent,type')
            ->where(['maintain_status'=>1])
            ->andWhere($where)
            ->andWhere($like_Where)
            ->asArray()
            ->orderBy('id desc')
            ->all();

        //小区
        $village = Village::getClass()->getVillage($where);

        return $this->render('list', ['maintain' => $maintain,'village'=>$village]);
    }

    /**
     * 获取维修人员页面
     */
    public function actionAjax_list()
    {
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $maintain_id = yii::$app->request->get('maintain_id');
        $village_id = yii::$app->request->get('village_id');
        $device_id = yii::$app->request->get('device_id');
        $where['m.del_flag'] = 0;
        $where['r.del_flag'] = 0;
        empty($maintain_id) || $where['r.maintain_id']   = $maintain_id;
        empty($village_id) || $where['m.position_village_id'] = $village_id;
        empty($device_id) || $where['m.device_id'] = $device_id;
        $this->user_admin && $where['agent'] = $this->agent_id;
        //机器
        $machine = SrRecyclingMachine::find()
            ->alias('m')
            ->select('m.device_id,m.community_name,m.location,r.maintain_id')
            ->join('LEFT JOIN', 'sr_maintain_machine_rel as r', 'm.id = r.machine_id')
            ->where($where)
            ->offset($start)
            ->limit($length)
            ->orderBy('m.id desc')
            ->asArray()
            ->all();
        //获取记录总数
        $total = SrRecyclingMachine::find()
            ->alias('m')
            ->join('LEFT JOIN', 'sr_maintain_machine_rel as r', 'm.id = r.machine_id')
            ->where($where)
            ->asArray()
            ->count();
        //维修员
        $rid = array_column($machine,'maintain_id');

        $maintain = Maintain::getClass()->getMaintainList(['del_flag'=>0,'maintain_status'=>1,'id'=>$rid]);
        $maintain = array_column($maintain,NULL,'id');
        $type = Yii::$app->params['Maintain'];
        $data = [];
        foreach ($machine as $val){
            //过滤失效
            if (empty($maintain[$val['maintain_id']]['nick_name'])){
                continue;
            }
            $temp = [];
            $temp['device_id'] = $val['device_id'];
            $temp['community_name'] = $val['community_name'];
            $temp['location'] = $val['location'];
            $temp['maintain'] = $maintain[$val['maintain_id']]['nick_name'];//nickname
            $temp['type'] = $type[$maintain[$val['maintain_id']]['type']];//type

            array_push($data, array_values($temp));
        }

        /**
         * 判断超级管理员和机动维修员
         */
        if (!empty($maintain_id) && empty($machine)){
            $maintain = Maintain::getClass()->getMaintain(['del_flag'=>0,'maintain_status'=>1,'id'=>$maintain_id]);
            if (!empty($maintain) && ($maintain['type'] == 1 || $maintain['type'] == 2)){
                $temp = [];
                $temp['device_id'] = '所有回收机';
                $temp['community_name'] = '所有小区';
                $temp['location'] = '所有小区';
                $temp['maintain'] = $maintain['nick_name'];//nickname
                $temp['type'] = $type[$maintain['type']];//type
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
     * 添加维护人员
     */
    public function actionMaintain_add()
    {
        if (Yii::$app->request->post()){
            $param = Yii::$app->request->post();
            $mid = $param['id'];
            $time = date('Y-m-d H:i:s');

            //判断
            $phone = Maintain::getClass()->getMaintain(['phone_num'=>$param['phone_num']]);
            if (!empty($phone)){
                return $this->AjaxResult(FAILD,'存在相同的手机号');
            }

            $data['SrMaintain'] = [
                'nick_name' => $param['nick_name'],
                'phone_num' => $param['phone_num'],
                'agent' =>$this->user['id'],
                'avatar_img' => '',
                'type' => $param['type'],
                'maintain_status' => $param['maintain_status'],
                'create_date' => $time,
                'update_date' => $time,
                'password' => 'e4aed93ec8e0084edaef0cb945aa5acb885792dea7c115f6de9a96c77df0ca617761738c76e965f18aafc30eccbe0dacc9ec1788a7a1bbc0d6ef59b98047c099',
                'del_flag' => '0',
            ];

            $Maintain = new SrMaintain();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //添加记录到主表
                if (!$Maintain->load($data) || !$Maintain->save()){
                    throw new Exception($Maintain->getFirstStrErrors());
                }

                if ($param['type'] == 0 && !empty($mid)){
                    $machine = [];
                    //添加关系表
                    for ($i = 0,$j = count($mid); $i < $j; $i++){
                        $machine[$i] = [
                            'maintain_id' => $Maintain->id,
                            'machine_id' => $mid[$i],
                            'create_date' => $time,
                            'update_date' => $time,
                            'del_flag' => 0
                        ];
                    }
                    //删除符合的关系
                    SrMaintainMachineRel::deleteAll(['machine_id'=>$mid]);
                    //执行批量添加
                    $key = ['maintain_id','machine_id', 'create_date', 'update_date', 'del_flag'];
                    Yii::$app->db->createCommand()->batchInsert(SrMaintainMachineRel::tableName(), $key, $machine)->execute();
                }

                $info = '运营商: '.$this->user['company_name'].'添加维修员'.$param['nick_name'];
                AdminLog::getClass()->addLog('添加维修员',$info);
                $transaction->commit();

                return $this->AjaxResult(SUCCESS,'添加成功');
            }catch (Exception $e){
                $transaction->rollBack();

                return $this->AjaxResult(FAILD,'添加失败',$e->getMessage());
            }

        }else{//get
            $where['m.del_flag'] = 0;
            $this->user_admin && $where['agent'] = $this->agent_id;
            $machine = Machine::getClass()->getMaintainMachineRel($where);

            //维修员
            $rid = array_filter(array_unique(array_column($machine,'maintain_id')));
            $maintain = Maintain::getClass()->getMaintainList(['del_flag'=>0,'maintain_status'=>1,'id'=>$rid]);
            $maintain = array_column($maintain,NULL,'id');

            $data = [];
            foreach ($machine as $key=>$val){
                $data[$key]['id'] = $val['id'];
                $data[$key]['community_name'] = $val['community_name'];
                $data[$key]['location'] = $val['location'];
                $nick_name =  !empty($maintain[$val['maintain_id']]['nick_name']) ? $maintain[$val['maintain_id']]['nick_name'] : "";//nickname
                $data[$key]['maintain'] = $nick_name;
            }

            return $this->renderPartial('maintain_add', [
                'machine' => $data,
            ]);

        }

    }




}