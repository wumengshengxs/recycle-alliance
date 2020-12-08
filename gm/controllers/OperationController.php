<?php


namespace gm\controllers;


use common\models\Agent;
use gm\models\SrRecycler;
use gm\models\SrRecyclingHistoryChild;
use gm\models\SrRecyclingMachine;
use gm\models\SrRubbishCategory;
use gm\models\SrUser;
use gm\models\SrUserDeliveryHistoryChild;
use gm\models\SrUserUsageStatistics;
use yii\filters\AccessControl;

class OperationController extends GController
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
     * 投递列表页面
     */
    public function actionDelivery(){
        return $this->render('delivery');
    }

    /**
     * 投递列表数据
     */
    public function actionAjax_delivery(){
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //设置查询条件
        $where = $whereCount = [];
        $this->user_admin && $where['a.agent'] = $whereCount['agent'] = $this->agent_id;

        $srUserDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->select(['e.phone_num', 'a.delivery_time', 'c.province_name', 'c.city_name', 'c.community_name', 'd.category_name', 'a.delivery_count', 'a.delivery_income', 'f.number_of_delivery', 'f.cumulative_income'])
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrRecyclingMachine::tableName() . ' c', 'a.machine_id = c.id')
            ->leftJoin(SrRubbishCategory::tableName() . ' d', 'a.delivery_type = d.id')
            ->leftJoin(SrUser::tableName() . ' e', 'a.user_id = e.id')
            ->leftJoin(SrUserUsageStatistics::tableName() . ' f', 'a.user_id = f.user_id')
            ->where($where)
            ->orderBy(['a.id' => SORT_DESC])
            ->offset($start)->limit($length)->asArray()->all();

        $total = SrUserDeliveryHistoryChild::find()->where($whereCount)->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srUserDeliveryHistoryChild as $v){
            $temp = [
                'phone_num' => $v['phone_num'],
                'delivery_time' => $v['delivery_time'],
                'area' => $v['province_name'] . $v['city_name'],
                'community_name' => $v['community_name'],
                'category_name' => $v['category_name'],
                'delivery_count' => $v['delivery_count'],
                'delivery_income' => $v['delivery_income'],
                'number_of_delivery' => $v['number_of_delivery'],
                'cumulative_income' => $v['cumulative_income'],
            ];
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_data', ['data' => json_encode($data_source)]);
    }

    /**
     * 清运列表
     */
    public function actionRecycler(){
        return $this->render('recycler');
    }

    /**
     * 清运列表数据
     */
    public function actionAjax_recycler(){
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        //设置查询条件
        $where = $whereCount = [];
        $this->user_admin && $where['a.agent'] = $whereCount['agent'] = $this->agent_id;
        $andWhere = ['>', 'a.recycle_child_id', 0];
        $andWhereCount = ['>', 'recycle_child_id', 0];

        $srUserDeliveryHistoryChild = SrUserDeliveryHistoryChild::find()
            ->select(['d.create_date', 'e.`nick_name`', 'SUM(a.delivery_count) AS delivery_count', 'b.province_name', 'b.city_name', 'b.community_name', 'c.`category_name`'])
            ->from(SrUserDeliveryHistoryChild::tableName() . ' a')
            ->leftJoin(SrRecyclingMachine::tableName() . ' b', 'a.machine_id = b.id')
            ->leftJoin(SrRubbishCategory::tableName() . ' c', 'a.delivery_type = c.id')
            ->leftJoin(SrRecyclingHistoryChild::tableName() . ' d', 'a.recycle_child_id = d.id')
            ->leftJoin(SrRecycler::tableName() . ' e', 'd.recycler_id = e.id')
            ->where($where)->andWhere($andWhere)
            ->andWhere(['<>','d.recycler_id',''])
            ->groupBy(['a.recycle_child_id'])
            ->orderBy(['a.recycle_child_id' => SORT_DESC])
            ->offset($start)->limit($length)->asArray()->all();

        $total = SrUserDeliveryHistoryChild::find()
            ->where($whereCount)->andWhere($andWhereCount)->groupBy(['recycle_child_id'])->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($srUserDeliveryHistoryChild as $v){
            $temp = [
                'create_date' => $v['create_date'],
                'nick_name' => $v['nick_name'],
                'province_name' => $v['province_name'] . $v['city_name'],
                'community_name' => $v['community_name'],
                'category_name' => $v['category_name'],
                'delivery_count' => $v['delivery_count'],
            ];
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        return $this->renderAjax('ajax_data', ['data' => json_encode($data_source)]);

    }
    
    /**
     * 品类统计
     */
    public function actionCategory(){
        $aid = $this->user['id'];
        $agent = Agent::findOne($this->user['id']);

        $query = SrRecyclingMachine::find()
            ->select('id');
        //判断是否admin admin可查看所有的
        if ($agent->admin){
            $query->andWhere(['agent'=>$aid]);
        }

        $machine = $query
            ->andWhere(['del_flag'=>0])
            ->asArray()
            ->all();
        $mid = array_column($machine,'id');

        $category = SrRubbishCategory::find()
            ->select('id,category_name,category_img_url')
            ->andWhere(['in','id',[1,2,3,4,5,6,7]])
            ->andWhere(['del_flag'=>0])
            ->asArray()
            ->all();
        //查询回收记录
        $query = SrRecyclingHistoryChild::find()
            ->select('category,sum(recycling_amount) as recycling_amount')
            ->andWhere(['in','machine_id',$mid])
            ->andWhere(['in','category',[1,2,3,4,5,6,7]])
            ->andWhere(['del_flag'=>0])
            ->groupBy('category')
            ->asArray();
        //今日
        $commandQuery = clone $query;
        $day =  $commandQuery->andWhere("to_days(create_date) = to_days(now())")->all();
        $day = array_column($day,NULL,'category');
        //昨日
        $commandQuery = clone $query;
        $to_day =  $commandQuery->andWhere("TO_DAYS( NOW( ) ) - TO_DAYS(create_date ) = 1  ")->all();
        $to_day = array_column($to_day,NULL,'category');
        //本周
        $commandQuery = clone $query;
        $week =  $commandQuery->andWhere("YEARWEEK(date_format(create_date,'%Y-%m-%d')) = YEARWEEK(NOW())")->all();
        $week = array_column($week,NULL,'category');
        //本月
        $commandQuery = clone $query;
        $month =  $commandQuery->andWhere("DATE_FORMAT(create_date, '%Y%m' ) = DATE_FORMAT(CURDATE( ) ,'%Y%m' )")->all();
        $month = array_column($month,NULL,'category');
        //本年
        $commandQuery = clone $query;
        $year =  $commandQuery->andWhere("YEAR(create_date) = YEAR(NOW())")->all();
        $year = array_column($year,NULL,'category');
        //总计
        $commandQuery = clone $query;
        $total =  $commandQuery->all();
        $total = array_column($total,NULL,'category');

        $path = 'https://image.squirrelf.com';
        for ($i = 0; $i < count($category); $i++){
            $category[$i]['day']    = empty($day[$category[$i]['id']]['recycling_amount']) ? 0 : $day[$category[$i]['id']]['recycling_amount'];
            $category[$i]['to_day'] = empty($to_day[$category[$i]['id']]['recycling_amount']) ? 0 : $to_day[$category[$i]['id']]['recycling_amount'];
            $category[$i]['week']   = empty($week[$category[$i]['id']]['recycling_amount']) ? 0 : $week[$category[$i]['id']]['recycling_amount'];
            $category[$i]['month']  = empty($month[$category[$i]['id']]['recycling_amount']) ? 0 : $month[$category[$i]['id']]['recycling_amount'];
            $category[$i]['year']   = empty($year[$category[$i]['id']]['recycling_amount']) ? 0 : $year[$category[$i]['id']]['recycling_amount'];
            $category[$i]['total']  = empty($total[$category[$i]['id']]['recycling_amount']) ? 0 : $total[$category[$i]['id']]['recycling_amount'];
            $category[$i]['category_img_url'] = empty($category[$i]['category_img_url']) ? '' : $path.$category[$i]['category_img_url'];
        }

        return $this->render('category',['category'=>$category]);
    }




}