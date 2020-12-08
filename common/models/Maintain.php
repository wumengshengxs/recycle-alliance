<?php


namespace common\models;


use gm\models\SrMachineMaintain;
use gm\models\SrMaintain;

class Maintain
{

    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {

        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }


    /**
     * 获取维修员列表信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMaintainList($where)
    {
         $data = SrMaintain::find()
                ->select('id,nick_name,phone_num,agent,type,maintain_status')
                ->where($where)
                ->asArray()
                ->all();

         return $data;
    }

    /**
     * 获取维修员信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getMaintain($where)
    {
        $data = SrMaintain::find()
                ->select('id,nick_name,phone_num,agent,type')
                ->where($where)
                ->asArray()
                ->one();

        return $data;
    }

    /**
     * 获取维修信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getMaintainMachine($where)
    {
        $data = SrMachineMaintain::find()
            ->select('id,machine_id,maintain_id,status,cause,admin_mark,maintain_mark,type')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }




}