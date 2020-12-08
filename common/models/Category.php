<?php


namespace common\models;


use gm\models\SrRubbishCategory;

class Category
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取垃圾信息列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCategoryList($where)
    {
        $data = SrRubbishCategory::find()
                ->select('id,category_name,rubbish_unit')
                ->asArray()
                ->where($where)
                ->all();

        return $data;
    }

    /**
     * 获取垃圾信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCategory($where)
    {
        $data = SrRubbishCategory::find()
            ->select('id,category_name,rubbish_unit')
            ->asArray()
            ->where($where)
            ->one();

        return $data;
    }

}