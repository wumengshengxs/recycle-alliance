<?php


namespace common\models;


use gm\models\SrExternalRecyclers;

class ExternalRecyclers
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 回收商信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getExternalRecyclers($where)
    {
        $data = SrExternalRecyclers::find()
            ->select('id,nick_name,phone_num,status')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

}