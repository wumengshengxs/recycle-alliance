<?php

namespace gm\controllers;


use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrUserDeliveryDayStatistic;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserDeliveryHistoryChildBrush;
use gm\models\SrUserDeliveryHistoryParent;
use gm\models\SrUserDeliveryHistoryParentBrush;
use Yii;

class ToolController extends GController{

    public function actionRun(){
        $date = $_GET['date'];

        while($date < date('Y-m-d')){
            $url = 'http://49.234.40.100/tool/brush/?date=' . $date;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);
            curl_close($ch);

            $date = date('Y-m-d', strtotime($date) + 86400);
            sleep(1);
        }
        die('好了');
    }

    /**
     * 数据刷量页面
     */
    public function actionDatabrush(){
        return $this->render('databrush');
    }

    /**
     * 数据刷量
     */
    public function actionBrush(){
        //获取需刷量的日期
        $date = $this->get('date');
        empty($date) && $date = date('Y-m-d');
        $valve = empty($this->get('valve')) ? 3 : $this->get('valve');
        $start = empty($date) ? '' : $date . ' 00:00:00';
        $end = empty($date) ? '' : $date . ' 23:59:59';


        /*-------------------------------------------业务模块分割线---------------------------------------------------*/

        //获取指定日期的所有投递明细
        $where = ['between', 'delivery_time', $start, $end];
        $srUserDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->where($where)
            ->andWhere(['>', 'delivery_income', 0])->asArray()->all();

        //没有数据则直接返回
        if(empty($srUserDeliveryHistoryChild)){
            die(json_encode('暂无投递数据'));
        }

        //计算需要克隆的数据
        $clone = $parent_id = [];
        foreach ($srUserDeliveryHistoryChild as $k => $v){
            $v['delivery_count'] *= $valve;
            $v['delivery_income'] *= $valve;
            array_push($clone, $v);
            array_push($parent_id, $v['parent_id']);
        }

        //先删除该天数据结果
        SrUserDeliveryHistoryChildBrush::deleteAll($where);

        //整理并重新写入投递明细
        $column = array_keys(array_shift($srUserDeliveryHistoryChild));
        $res = $this->getConnect()->createCommand()->batchInsert(
            SrUserDeliveryHistoryChildBrush::tableName(),
            $column,
            $clone
        )->execute();

        /*-------------------------------------------业务模块分割线---------------------------------------------------*/

        //获取指定parent_id的所有投递记录
        $where = ['in', 'id', $parent_id];
        $srUserDeliveryHistoryParent = SrUserDeliveryHistoryParent::find()
            ->where($where)->asArray()->all();

        //没有数据则报错
        if(empty($srUserDeliveryHistoryChild)){
            die(json_encode('暂无投递数据'));
        }

        //计算需要克隆的数据
        $clone = [];
        foreach ($srUserDeliveryHistoryParent as $k => $v){
            $v['income_amount'] *= $valve;
            array_push($clone, $v);
        }

        //先删除parent_id对应的数据结果
        SrUserDeliveryHistoryParentBrush::deleteAll($where);

        //整理并重新写入投递明细

        $column = array_keys(array_shift($srUserDeliveryHistoryParent));
        $res = $this->getConnect()->createCommand()->batchInsert(
            SrUserDeliveryHistoryParentBrush::tableName(),
            $column,
            $clone
        )->execute();

        if($res){
            die(json_encode('执行成功'));
        }
        die(json_encode('执行失败'));
    }

    /**
     * 汇总用户日投递数据
     * 按小区分段获取数据
     */
    public function actionDelivery_day(){
        //初始化开始日期和结束日期
        $start = $this->get('start');
        $end = $this->get('end');
        $start || $start = $end = date('Y-m-d');
        //正则过滤日期格式是否合法
        $pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        preg_match($pattern, $start) || die('开始日期格式错误');
        preg_match($pattern, $end) || die('结束日期格式错误');
        //判断开始日期和结束日期大小值
        if($start > $end){
            die('开始日期不能大于结束日期');
        }
        //结束日期为设置日期+1天
        if(Yii::$app->request->get('start'))
            $end = date('Y-m-d', strtotime($end) + 86400);
        else
            $start = date('Y-m-d', strtotime($start) - 86400);

        //查找分类汇总数据
        $res = SrUserDeliveryHistoryChild::find()
            ->select('d.machine_id, a.agent, SUM(a.delivery_count) count, SUM(a.delivery_income) income, DATE(a.delivery_time) date, b.province_name, b.county_name, b.street_name, b.community_name, c.category_name, a.delivery_type')
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrUserDeliveryHistoryParent::tableName() . ' d', 'a.parent_id = d.id')
            ->leftJoin(SrRecyclingMachine::tableName() . ' b', 'd.machine_id = b.id')
            ->leftJoin(SrRubbishCategory::tableName() . ' c', 'a.delivery_type = c.id')
            ->where(['BETWEEN', 'a.delivery_time', $start, $end])
            ->andWhere(['>', 'a.delivery_count', 0])
            ->groupBy(['c.category_name','d.machine_id', 'date'])
            ->orderBy('date, machine_id')
            ->asArray()->all();

        $list = [];
        foreach ($res as $v){
            //初始化每日机器汇总数据
            if(empty($list[$v['date'] . '|' . $v['machine_id']])){
                $list[$v['date'] . '|' . $v['machine_id']] = [
                    'agent' => $v['agent'],
                    'machine_id' => $v['machine_id'],
                    'province_name' => $v['province_name'],
                    'county_name' => $v['county_name'],
                    'street_name' => $v['street_name'],
                    'community_name' => $v['community_name'],
                    'category_1' => 0,
                    'category_2' => 0,
                    'category_3' => 0,
                    'category_4' => 0,
                    'category_5' => 0,
                    'category_6' => 0,
                    'category_7' => 0,
                    'category_8' => 0,
                    'create_date' => $v['date'],
                ];
            }
            //汇总每天每台设备产生的每个类别的数据总计
            $list[$v['date'] . '|' . $v['machine_id']]['category_' . $v['delivery_type']] += $v['count'];
        }

        //先删除日期对应的数据结果集
        $where = ['between', 'create_date', $start, $end];
        SrUserDeliveryDayStatistic::deleteAll($where);

        //组装写入数据
        $clone = [];
        foreach ($list as $v){
            array_push($clone, $v);
        }
        $column = array_keys(array_shift($list));

        //写入数据库
        $res = $this->getConnect()->createCommand()->batchInsert(
            SrUserDeliveryDayStatistic::tableName(),
            $column,
            $clone
        )->execute();

        if($res){
            die(json_encode('执行成功'));
        }
        die(json_encode('执行失败'));
    }
}