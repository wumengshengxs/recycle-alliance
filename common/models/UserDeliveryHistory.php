<?php


namespace common\models;


use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserDeliveryHistoryParent;

class UserDeliveryHistory
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取投递记录父表信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getParent($where)
    {
        $Parent = SrUserDeliveryHistoryParent::find()
            ->select('id,machine_id,user_id,serial_num,create_date')
            ->where($where)
            ->asArray()
            ->one();

        return $Parent;
    }

    /**
     * 获取投递子表的信息列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChildList($where)
    {
        $child = SrUserDeliveryHistoryChild::find()
            ->select('id,can_name,delivery_type, delivery_count,declarable_status,delivery_income, user_id,machine_id')
            ->where($where)
            ->asArray()
            ->all();

        return $child;
    }

    /**
     * 返回子表信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChild($where)
    {
        $child = SrUserDeliveryHistoryChild::find()
            ->select('id, delivery_type,can_name,delivery_count,declarable_status,delivery_income, user_id,machine_id,parent_id')
            ->where($where)
            ->asArray()
            ->one();

        return $child;
    }

}