<?php


namespace common\models;


use gm\models\SrUserVillageRankMonth;

class UserRank
{

    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取月排行
     * @param $where
     * @param null $order
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRankMonth($where,$order = null)
    {
         $qurey = SrUserVillageRankMonth::find()
             ->select('id,user_id,village_id,rank,delivery_count,delivery_weight,delivery_income,month')
             ->where($where);
         if($order){
             $qurey->orderBy($order);
         }
         $data = $qurey->asArray()
             ->all();

         return $data;
    }


}