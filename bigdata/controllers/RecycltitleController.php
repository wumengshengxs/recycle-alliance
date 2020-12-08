<?php

namespace bigdata\controllers;

use yii\rest\Controller;

class RecycltitleController extends Controller {
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
            ["昨日", "STRING", "DAY1"],
            ["今日", "STRING", "DAY2"],
            ["本周", "STRING", "DAY3"],
            ["本月", "STRING", "DAY4"],
            ["年度", "STRING", "DAY5"],
            ["累计", "STRING", "TOTAL"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 用户实时投递
     */
    public function actionReal_time_delivery() {
        $data = [
            ["用户信息", "STRING", "USER"],
            ["地区信息", "STRING", "AREA"],
            ["投递品类", "STRING", "CATEGORY"],
            ["投递数量", "NUMBER", "NUM"],
            ["环保金", "NUMBER", "AMNT"],
            ["投递历史", "STRING", "FREQUENCY"],
            ["累计环保金", "STRING", "TOTALAMNT"],
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 干湿垃圾回收总量
     */
    public function actionTotal_dry_and_wet_garbage_recycling() {
        $data = [
            ["类型", "STRING", "TYPE"],
            ["本周", "NUMBER", "DAY1"],
            ["本月", "NUMBER", "DAY2"],
            ["年度", "NUMBER", "DAY3"]

        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 上门回收总数据
     */
    public function actionRecycling_total() {
        $data = [
            ["饮料瓶", "STRING", "BOTTLE"],
            ["纸类", "STRING", "PAPER"],
            ["书籍", "STRING", "BOOKS"],
            ["塑料", "STRING", "PLASTIC"],
            ["纺织物", "STRING", "SPINNING"],
            ["金属", "STRING", "METAL"],
            ["玻璃", "STRING", "GLASS"],
            ["有害垃圾", "STRING", "FATAL"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }

    /**
     * 上门回收实时数据  Home recycling real-time data
     */
    public function actionRecycling_data() {
        $data = [
            ["城市", "STRING", "CITY"],
            ["小区", "STRING", "COMMUNITY"],
            ["类型", "STRING", "TYPE"],
            ["单位", "STRING", "UNIT"],
            ["价格", "STRING", "PRICE"]

        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


}