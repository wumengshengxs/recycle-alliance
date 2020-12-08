<?php
namespace gm\controllers;

use gm\models\PositionVillage;
use gm\models\SrRecycler;
use gm\models\SrUserBookOrder;
use gm\models\SrUserBookRecycler;
use gm\models\SysArea;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AreaController extends GController
{
    /**
     * {@inheritdoc}
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
            ]
        ];
    }

    public function actionList()
    {
        return $this->render('index');
    }

    public function actionDetail()
    {
        $order_id = $this->get('order_id');
        $order = SrUserBookOrder::find()
            ->select("*,TIMESTAMPDIFF(MINUTE, `book_limit_time`, if(`status` >= 2, `arrive_time`, now())) as 'now_time_out'")
            ->where(['order_id' => $order_id])
            ->asArray()->one();
        $recycler = SrRecycler::find()
            ->select('id, nick_name, phone_num')
            ->where(['id' => $order['recycl_id']])
            ->asArray()->one();
        $status = ['待分配', '待上门', '已取消', '已完成'];
        $book_list = SrUserBookRecycler::find()
            ->where(['book_order_id' => $order_id])
            ->orderBy(['id' => 'desc'])
            ->asArray()->all();
        $order['time_out_status'] = 0;
        if ($order['now_time_out'] > 0) {
            $order['time_out_status'] = 1;
        }
        //0待分配 1待上门  2取消 3完成
        $data = [];
        foreach ($book_list as $book) {
            $temp = [];
            $temp['create_time'] = $book['create_date'];
            $temp['type'] = $book['type'] ? "手动" : "自动";
            $temp['admin_name'] = $book['admin_name'];
            $temp['recycler_name'] = $book['recycler_name'];
            $data[] = $temp;
        }
        return $this->render('detail', [
            'order' => $order,
            'recycler' => $recycler,
            'order_status' => $status,
            'book_recycle' => $data
        ]);
    }

    public function actionChange()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order = SrUserBookOrder::find()
            ->where(['order_id' => $order_id])
            ->asArray()->one();
        $recycls = SrRecycler::find()
            ->select('id, nick_name, phone_num')
            ->asArray()->all();
        $order['recycler_name'] = '暂无';
        foreach ($recycls as $rec) {
            if ($rec['id'] == $order['recycl_id']) {
                $order['recycler_name'] = $rec['nick_name'];
            }
        }
        return $this->renderAjax('change', ['recycls' => $recycls, 'order_id' => $order_id, 'order' => $order]);
    }

    public function actionCancel()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order = SrUserBookOrder::find()
            ->where(['order_id' => $order_id])
            ->asArray()->one();
        return $this->renderAjax('cancel', ['order_id' => $order_id]);
    }

    /**
     * 抓取小区列表数据
     */
    public function actionAjax_list()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];
//        $order_by = [];
//        $order = yii::$app->request->get('order');
//        empty($order) && $order = [];
//        foreach ($order as $o) {
//            if ($o['column'] == 9) {
//                $order_by['now_time_out'] = $o['dir'] == 'asc' ? SORT_ASC : SORT_DESC;
//            }
//        }

        $order_by = yii::$app->request->get('order_by');
        empty($order_by) && $order_by = 'book_id';
        $order_by = [$order_by => SORT_DESC];
        $name = yii::$app->request->get('name');
        $user_name = yii::$app->request->get('user_name');
        $user_phone = yii::$app->request->get('user_phone');
        $order_status = yii::$app->request->get('order_status');
        $timeout_status = yii::$app->request->get('timeout_status');
        $like_where = "";
        empty($name) || $like_where = "(`community_name` like '%{$name}%')";
        empty($user_name) || $like_where .= (empty($like_where) ? " " : " AND ") . "(`user_name` like '%{$user_name}%')";
        empty($user_phone) || $like_where .= (empty($like_where) ? " " : " AND ") . "(`user_phone` like '%{$user_phone}%')";
        empty($order_status) || $like_where .= (empty($like_where) ? " " : " AND ") . "(`status` = '" . (intval($order_status) - 1) . "')";
        empty($timeout_status) || $like_where .= (empty($like_where) ? " " : " AND ") . "(TIMESTAMPDIFF(MINUTE, `book_limit_time`, if(`status` >= 2, `arrive_time`, now())) " . ($timeout_status == 1 ? ">" : "<=") . " 0)";
        empty($like_where) && $like_where = [];

        $book_list = SrUserBookOrder::find()
            ->select("order_id, status, create_time, community_name, user_door_number, user_name, user_phone, book_time, recycl_id, dispatch_time, mark,
            TIMESTAMPDIFF(MINUTE, `book_limit_time`, if(`status` >= 2, `arrive_time`, now())) as 'now_time_out'")
//            ->where($where)
            ->where($like_where)
            ->orderBy($order_by)
            ->offset($start)
            ->limit($length)->asArray()->all();
        $recycl_ids = $recycl_temp_ar = [];
        empty($book_list) || $recycl_ids = array_filter(array_unique(array_column($book_list, 'recycl_id')));
        if ($recycl_ids) {
            $recycls = SrRecycler::find()
                ->select('id, nick_name')
                ->where(['in', 'id', $recycl_ids])
                ->asArray()->all();
            foreach ($recycls as $r) {
                $recycl_temp_ar[$r['id']] = $r['nick_name'];
            }
        }
        //0待分配 1待上门  2取消 3完成
        $status = ['待分配', '待上门', '已取消', '已完成'];
        $data = [];
        foreach ($book_list as $book) {
            $temp = [];
//            $temp['order_id'] = "<div style='width: 100px;'>" . $book['order_id'] . "</div>";
//            $temp['order_id'] = intval($book['order_id']/10000000000) . "<br>" . $book['order_id']%10000000000;
            $temp['order_id'] = '';
            $temp['status_info'] = '未知';
            if (array_key_exists($book['status'], $status)) {
                $temp['status_info'] = $status[$book['status']];
            }
            $temp['create_time'] = implode("<br>", explode(' ', $book['create_time']));
            $temp['community_name'] = $book['community_name'];
//            $temp['user_door_number'] = implode("<br>",str_split(implode('',preg_split('/(?<!^)(?!$)/u',$book['user_door_number'])), 16));
//            var_dump($temp['user_door_number']);
            $temp['user_door_number'] = "<div style='width: 100px;'>" . $book['user_door_number'] . "</div>";
            $temp['user_name'] = $book['user_name'] . "<br>" . $book['user_phone'];
            $temp['book_time'] = implode("<br>", explode(' ', $book['book_time']));
            $temp['temp_name'] = '未知';
            if (array_key_exists($book['recycl_id'], $recycl_temp_ar)) {
                $temp['temp_name'] = $recycl_temp_ar[$book['recycl_id']];
            }
            $temp['dispatch_time'] = implode("<br>", explode(' ', $book['dispatch_time']));
            $temp['time_mark'] = '超时1小时';
            $time = $book['now_time_out'];
            $str_time = time_to_dhs(($time > 0 ? $time : $time*-1));
            if ($time > 0) {
                $temp['time_mark'] = '<span style="color: red;">超时' . $str_time . '</span>';
            } else {
                $temp['time_mark'] = $book['status'] < 2 ? $str_time . '后超时' : $temp['status_info'];
            }

            $temp['mark'] = $book['mark'];
            $temp['order_id'] = '<div style=\'width: 100px;\'>';
            if ($book['status'] >= 2) {
                $temp['order_id'] .= '<a href="javascript:(0)" onclick="order_detail(\''.$book['order_id'].'\')">详情</a>';
            } else {
                $temp['order_id'] .= '<a href="javascript:(0)" onclick="order_detail(\''.$book['order_id'].'\')">详情</a>';
                $temp['order_id'] .= ' | <a href="javascript:(0)" onclick="order_change(\''.$book['order_id'].'\')">派单</a>';
                $temp['order_id'] .= ' | <a href="javascript:(0)" onclick="order_cancel(\''.$book['order_id'].'\')">取消</a>';
            }
            $temp['order_id'] .= '</div>';
            array_push($data, array_values($temp));
        }

        //获取记录总数
        $total = SrUserBookOrder::find()
            ->where($like_where)
            ->count();

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));
    }

    public function actionVillage_add()
    {
        $area_list = SysArea::find()
            ->select('name, code')
            ->where(['type' => 3])
            ->asArray()->all();
        return $this->renderAjax('village_add', ['area_list' => $area_list]);
    }

    public function actionVillage_edit()
    {
        $id = Yii::$app->request->get('id');
        $village = PositionVillage::find()
            ->select('*')
            ->where(['p_id' => $id])
            ->asArray()->one();
        $area_list = SysArea::find()
            ->select('name, code')
            ->where(['type' => 3])
            ->asArray()->all();
        return $this->renderAjax('village_edit', [
            'area_list' => $area_list,
            'village' => $village
        ]);
    }

    public function actionAjax_detail()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

//        $recycl_id = yii::$app->request->get('recycler');
        $order_id = yii::$app->request->get('order_id');
        $order_by = ['id' => SORT_DESC];
        var_dump($order_id);
        $book_list = SrUserBookRecycler::find()
            ->where(['book_order_id' => $order_id])
            ->orderBy($order_by)
            ->offset($start)
            ->limit($length)->asArray()->all();
        //0待分配 1待上门  2取消 3完成
        $data = [];
        foreach ($book_list as $book) {
            $temp = [];
            $temp['create_time'] = $book['create_time'];
            $temp['type'] = $book['type'] ? "手动" : "自动";
            $temp['admin_name'] = $book['admin_name'];
            $temp['recycler_name'] = $book['recycler_name'];
            array_push($data, array_values($temp));
        }

        //获取记录总数
        $total = SrUserBookRecycler::find()
            ->where(['book_order_id' => $order_id])
            ->count();

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));
    }

    public function actionAbc() {
        die(json_encode([
            [
                "COL1" => "1",
                "COL2" => 333,
                "COL3" => "上海"
            ],[
                "COL1" => "1",
                "COL2" => 333,
                "COL3" =>"上海"
            ]]));
    }
}