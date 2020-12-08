<?php


namespace common\models;


use gm\models\SrTrashCan;

class TrashCan
{
    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 获取垃圾桶信息列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getTrashCanList($where)
    {
        $data = SrTrashCan::find()
            ->select('id,can_name,category')
            ->where($where)
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取垃圾桶信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getTrashCan($where)
    {
        $data = SrTrashCan::find()
            ->select('id,machine_id,category,recycle_price,can_num,can_name,activity_status,activity_price,count,max_count,weight,max_weight')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

    /**
     * 编辑Trash数据处理
     * @param $trashCan
     * @param $delivery_count
     * @param $time
     * @return array
     */
    public function getEditTrash($trashCan,$delivery_count,$time)
    {
        //判断是重量还是数量(饮料瓶)
        $can_full = 0;

        if($trashCan['category'] != 1){
            $weight = $delivery_count + $trashCan['weight'];
            $max_weight = $trashCan['max_weight'];
            if ($weight > $max_weight){
                $can_full = 2;
            }
            //垃圾桶
            $trashCanData = [
                'weight' => $weight,
                'can_full' => $can_full,
                'update_date' => $time
            ];
        }else{
            $count = $delivery_count + $trashCan['count'];
            $max_count = $trashCan['max_count'];
            if ($count > $max_count){
                $can_full = 2;
            }
            $trashCanData = [
                'count' => intval($count),
                'can_full' => $can_full,
                'update_date' => $time
            ];
        }

        return $trashCanData;
    }
}