<?php


namespace common\models;


use gm\models\PositionVillage;

class Village
{

    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {

        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取小区地址信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVillage($where)
    {
        $village = PositionVillage::find()
            ->select('p_id,village_name')
            ->andWhere($where)
            ->asArray()
            ->all();

        return $village;
    }

    /**
     * 获取小区地址列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVillageList($where)
    {
        $data = PositionVillage::find()
            ->select('village_name,p_id')
            ->where($where)
            ->asArray()->all();

        return $data;

    }


}