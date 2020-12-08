<?php
/**
 *  机器公共调用
 */

namespace common\models;


use gm\models\SrRecyclingMachine;

class Machine
{

    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取机器信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMachine($where)
    {
        $data = SrRecyclingMachine::find()
            ->select('id, community_name,device_id,street_name')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

    /**
     * 获取机器信息列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMachineList($where)
    {
        $data = SrRecyclingMachine::find()
            ->select('id, community_name,device_id,position_village_id,street_name')
            ->where($where)
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取机器指定的groupBy数据
     * @param $name
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMachineColumn($name,$where)
    {
        $data = SrRecyclingMachine::find()
            ->select('DISTINCT('.$name.')')
            ->andWhere($where)
            ->andWhere(['!=',$name,""])
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取维修员与机器链接
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMaintainMachineRel($where)
    {
        $data = SrRecyclingMachine::find()
            ->alias('m')
            ->select('m.id,m.community_name,m.location,r.maintain_id')
            ->join('LEFT JOIN', 'sr_maintain_machine_rel as r', 'm.id = r.machine_id')
            ->where($where)
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取回收员与机器链接
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRecyclingMachineRel($where){
        $data = SrRecyclingMachine::find()
                ->alias('m')
                ->select('m.id,m.community_name,m.location,r.recycler_id')
                ->join('LEFT JOIN', 'sr_recycler_machine_rel as r', 'm.id = r.machine_id')
                ->where($where)
                ->asArray()
                ->all();

        return $data;
    }



}