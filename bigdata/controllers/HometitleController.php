<?php

namespace bigdata\controllers;

use yii\rest\Controller;

class HometitleController extends Controller {
    /**
     * 封装统一格式
     */
    public function titleData($data) {
        $temp = [];
        foreach ($data as $item) {
            $temp[] = [
                "COLNAME" => $item[0],
                "COLTYPE" => $item[1],
                "COL" => $item[2]
            ];
        }
        return $temp;
    }

    /**
     * 各品类回收量
     */
    public function actionCategory_recycled() {
        $data = [
            ["类型", "STRING", "TYPE"],
            ["累计", "NUMBER", "TOTAL"],
            ["昨日", "STRING", "DAY1"],
            ["今日", "STRING", "DAY2"],
            ["本周", "STRING", "WEEK"],
            ["本月", "STRING", "MONTH"],
            ["年度", "STRING", "YEAR"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /** Recyclingdata
     * 实时投递
     */
    public function actionReal_time_delivery() {
        $data = [
            ["用户信息", "STRING", "USER"],
            ["地区信息", "STRING", "AREA"],
            ["投递品类", "STRING", "CATEGORY"],
            ["投递数量", "STRING", "NUMBER"],
            ["环保金", "STRING", "AMNT"],
            ["历史投递", "STRING", "ALL_COUNT"],
            ["累计环保金", "STRING", "ALL_AMNT"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 各数据统计
     */
    public function actionStatistic() {
        $data = [
            ["注册用户总数", "NUMBER", "USER_NUMBER"],
            ["历史回收总量", "NUMBER", "HISTORY_CATEGORY_NUMBER"],
            ["发放回收金", "NUMBER", "RECOVERY_MONEY"],
            ["已铺设回收机", "NUMBER", "RUNNING_MATCH"],
            ["覆盖省市", "NUMBER", "RUNNING_CITY"],
            ["覆盖小区", "NUMBER", "RUNNING_VILLAGE"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 区域统计排行
     */
    public function actionArea_num_list() {
        $data = [
            ["区域名称", "STRING", "AREA_NAME"],
            ["区域量", "STRING", "AREA_NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 7日投递次数排行榜
     */
    public function actionSeven_count_list() {
        $data = [
            ["日期", "STRING", "DAY"],
            ["数量", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 回收机满仓监控
     */
    public function actionMache_can_full() {
        $data = [
            ["街道", "STRING", "STREET"],
            ["小区", "STRING", "VOLLAGE"],
            ["箱体", "STRING", "CAN"],
            ["状态", "STRING", "STATUS"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 回收机满仓监控
     */
    public function actionMache_can() {
        $data = [
            ["机器编号", "STRING", "ID"],
            ["街道", "STRING", "STREET"],
            ["小区", "STRING", "VOLLAGE"],
            ["箱体", "STRING", "CAN"],
            ["重量", "STRING", "NUM"],
            ["金额", "STRING", "AMNT"],
            ["容量", "STRING", "RONG"],
            ["状态", "STRING", "STATUS"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 回收机满仓分类箱体数量列表
     */
    public function actionMache_can_category_list() {
        $data = [
            ["类型", "STRING", "TYPE"],
            ["数量", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 今日类别清运重量列表
     */
    public function actionCategory_recycled_list() {
        $data = [
            ["类别", "STRING", "CATEGORY"],
            ["重量", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 实时清运重量列表
     */
    public function actionRecycled_list() {
        $data = [
            ["街道", "STRING", "STREET"],
            ["小区", "STRING", "VOLLAGE"],
            ["箱体", "STRING", "CAN"],
            ["重量", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 单数据显示
     */
    public function actionCommon() {
        $data = [
            ["数据", "NUMBER", "SHOW"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }
}