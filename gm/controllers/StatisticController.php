<?php

namespace gm\controllers;

use gm\models\PositionVillage;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrUser;
use gm\models\SrUserDeliveryDayStatistic;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserDeliveryHistoryParent;
use gm\models\SrUserWithdrawOrder;
use Yii;
use yii\filters\AccessControl;

class StatisticController extends GController
{
    /**
     * AccessControl
     * 访问权限控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 视图
     * 日投递明细
     */
    public function actionDelivery()
    {
        $srRecyclingMachine = SrRecyclingMachine::find()
            ->select('id, community_name')->asArray()->all();
        $srRubbishCategory = SrRubbishCategory::find()
            ->select('id, category_name')->asArray()->all();
        $data = [
            'srRecyclingMachine' => $srRecyclingMachine,
            'srRubbishCategory' => $srRubbishCategory,
        ];
        return $this->render($this->action->id, $data);
    }

    /**
     * 数据
     * 日投递明细
     */
    public function actionAjax_delivery()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取条件参数并组装条件值
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $datepicker_end = yii::$app->request->get('datepicker_end');//结束时间
        $delivery_type = yii::$app->request->get('delivery_type');

        //如果没有参数则不做处理
        if (empty($machine_id) || empty($delivery_time)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            return $this->renderAjax($this->action->id, ['data' => json_encode($data_source)]);
        }

        //查询条件预处理
        $where = $andWhere = [];
        empty($machine_id) || $where['b.machine_id'] = $machine_id;
        empty($delivery_type) || $where['a.delivery_type'] = $delivery_type;
        if (!empty($delivery_time) || !empty($datepicker_end)) {
            $start = $delivery_time . ' 00:00:00';
            $end = $datepicker_end . ' 23:59:59';
            $andWhere = ['between', 'a.delivery_time', $start, $end];
        }

        //查询记录
        $srUserDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->select('a.id, a.user_id, c.phone_num, d.category_name, a.delivery_count, a.delivery_income, a.delivery_time')
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrUserDeliveryHistoryParent::tableName() . ' b', 'a.parent_id = b.id')
            ->leftJoin(SrUser::tableName() . ' c', 'a.user_id = c.id')
            ->leftJoin(SrRubbishCategory::tableName() . ' d', 'a.delivery_type = d.id')
            ->where($where)
            ->andWhere($andWhere)
            ->orderBy(['a.id' => SORT_DESC])
            ->offset($offset)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = SrUserDeliveryHistoryChild::find()
            ->select('a.id')
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrUserDeliveryHistoryParent::tableName() . ' b', 'a.parent_id = b.id')
            ->where($where)->andWhere($andWhere)->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srUserDeliveryHistoryChild as $v) {
            array_push($data, array_values($v));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax($this->action->id, ['data' => json_encode($data_source)]);
    }

    /**
     * 投递总人数
     */
    public function actionGet_num()
    {
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $datepicker_end = yii::$app->request->get('datepicker_end');


        //查询条件预处理
        $andWhere = [];
        if (!empty($delivery_time) || !empty($datepicker_end)) {
            $start = $delivery_time . ' 00:00:00';
            $end = $datepicker_end . ' 23:59:59';
            $andWhere = ['between', 'delivery_time', $start, $end];
        }
        $number = SrUserDeliveryHistoryParent::find()
            ->select('machine_id,user_id')
            ->orderBy(['user_id' => SORT_DESC])
            ->where(['machine_id' => $machine_id])
            ->andWhere(['del_flag' => 0])
            ->andWhere($andWhere)
            ->asArray()->all();
        $number_ = count(array_unique(array_column($number, 'user_id')));

        die(json_encode($number_));
    }

    /**
     * 投递总环保金
     */
    public function actionGet_money()
    {
        $machine_id = yii::$app->request->get('machine_id');
        $delivery_time = yii::$app->request->get('delivery_time');
        $datepicker_end = yii::$app->request->get('datepicker_end');

        //查询条件预处理
        $andWhere = [];
        if (!empty($delivery_time) || !empty($datepicker_end)) {
            $start = $delivery_time . ' 00:00:00';
            $end = $datepicker_end . ' 23:59:59';
            $andWhere = ['between', 'delivery_time', $start, $end];
        }
        $income_amount = SrUserDeliveryHistoryParent::find()
            ->select('SUM(income_amount) AS total')
            ->groupBy('machine_id')
            ->orderBy(['delivery_time' => SORT_DESC])
            ->where(['machine_id' => $machine_id])
            ->andWhere($andWhere)
            ->asArray()->all();
        $total = array_column($income_amount, 'total');

        die(json_encode($total));
    }

    /**
     * 视图
     * 品类统计
     */
    public function actionCategory()
    {
        $srRubbishCategory = SrRubbishCategory::find()
            ->select('id, category_name')->asArray()->all();
        $data = [
            'srRubbishCategory' => $srRubbishCategory,
        ];
        return $this->render($this->action->id, $data);
    }

    /**
     * 数据
     * 品类统计
     */
    public function actionAjax_category()
    {

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ];
        return $this->renderAjax($this->action->id, ['data' => json_encode($data_source)]);
    }

    /**
     * 视图
     * 每日品类统计图
     */
    public function actionCategory_chart()
    {
        $street = SrUserDeliveryDayStatistic::find()
            ->select('DISTINCT `street_name`')
            ->asArray()->all();
        return $this->render($this->action->id, ['street' => $street]);
    }

    /**
     * 表格数据
     * 每日品类统计图
     */
    public function actionAjax_category_chart()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //获取条件参数并组装条件值
        $delivery_start = yii::$app->request->get('delivery_start');
        $delivery_end = yii::$app->request->get('delivery_end');
        $street = yii::$app->request->get('street');

        //如果没有参数则不做处理
        if (empty($delivery_start) || empty($delivery_end) || empty($street)) {
            $data_source = [
                'draw' => $_GET['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
            return $this->renderAjax($this->action->id, ['data' => json_encode($data_source)]);
        }

        //组装查询条件
        $where = ['BETWEEN', 'create_date', $delivery_start, $delivery_end];
        $andWhere = ['street_name' => $street];

        //查询数据
        $statistic = SrUserDeliveryDayStatistic::find()
            ->select('street_name, create_date, SUM(`category_2` + `category_3` + `category_4` + `category_5` + `category_6`) plus, SUM(category_1) category_1')
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy('street_name, create_date')
            ->offset($offset)
            ->limit($length)->asArray()->all();
        $data = [];
        foreach ($statistic as $v) {
            array_push($data, array_values($v));
        }

        //计算记录总长度与分类合计
        $total = SrUserDeliveryDayStatistic::find()
            ->select('street_name, SUM(`category_2` + `category_3` + `category_4` + `category_5` + `category_6`) plus, SUM(category_1) category_1')
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy('street_name, create_date')->asArray()->all();
        $other = $bottle = 0;
        foreach ($total as $v) {
            $other = bcadd($other, $v['plus'], 2);
            $bottle = bcadd($bottle, $v['category_1']);
        }
        $sum = ['总计', '', $other, $bottle];
        array_push($data, $sum);

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => count($total),
            'recordsFiltered' => count($total),
            'data' => $data
        ];
        return $this->renderAjax($this->action->id, ['data' => json_encode($data_source)]);
    }

    /**
     * 柱状图数据
     * 每日品类统计图
     */
    public function actionAjax_category_chart2()
    {

    }

    /**
     * 柱状图数据
     * 每日品类统计图
     */
    public function actionAjax_category_chart3()
    {

    }
}