<?php


namespace common\models;


use gm\models\SrExternalRecycler;

class ExternalRecycler
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 回收员信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getExternalRecycler($where)
    {
        $data = SrExternalRecycler::find()
            ->select('id,nick_name,phone_num,balance,type,village_id,status')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

}