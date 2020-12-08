<?php


namespace common\models;


use gm\models\SrRecycler;

class Recycler
{
    private static $_instance = null;

    private function __construct(){}


    public static function getClass()
    {

        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取回收员列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRecyclerList($where)
    {
        $data = SrRecycler::find()
                ->select('id,nick_name,phone_num,agent,type')
                ->where($where)
                ->asArray()
                ->all();

        return $data;
    }

    /**
     * 获取回收员信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getRecycler($where)
    {
        $data = SrRecycler::find()
                ->select('id,nick_name,phone_num,agent,type')
                ->where($where)
                ->asArray()
                ->one();

        return $data;
    }
}