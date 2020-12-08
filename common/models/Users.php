<?php


namespace common\models;


use gm\models\SrUser;
use gm\models\SrUserUsageStatistics;

class Users
{

    private static $_instance = null;

    private function __construct(){}

    public static function getClass()
    {
        self::$_instance = self::$_instance ? self::$_instance : new self();

        return self::$_instance;
    }

    /**
     * 用户信息列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUserList($where)
    {
        $data = SrUser::find()
            ->select('id,nick_name, phone_num')
            ->where($where)
            ->andWhere(['del_flag'=>0])
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取运营商用户列表
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAgentUserList($where)
    {
        $data = SrUser::find()
            ->alias('u')
            ->select('u.id,u.nick_name, u.phone_num')
            ->join('LEFT JOIN','sr_agent_user_rel as r','r.user_id = u.id')
            ->where($where)
            ->andWhere(['u.del_flag'=>0])
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * 获取用户信息
     * @param $where
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getUser($where)
    {
        $data = SrUser::find()
            ->select('id,nick_name, phone_num,user_status,remarks')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

    /**
     * 获取用户使用情况信息
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUserStatistics($where)
    {
        $data =SrUserUsageStatistics::find()
            ->select('id,cumulative_income,current_env_amount,user_id')
            ->where($where)
            ->asArray()
            ->one();

        return $data;
    }

    /**
     * 获取用户金额信息修改
     * @param $category
     * @param $delivery_count
     * @param int $num
     * @return array
     */
    public function getEditStatistics($category,$delivery_count,$num = 0)
    {
        //用户详情
        $Statistics = [
            'number_of_delivery' => $num,//投递次数
        ];
        //用户详情数量累加
        if($category != 1){
            $Statistics['cumulative_weight'] = $delivery_count;
        }else{
            $Statistics['cumulative_count']  = $delivery_count;
        }

        return $Statistics;
    }

}