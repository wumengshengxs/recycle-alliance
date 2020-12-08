<?php

namespace bigdata\controllers;

use yii\rest\Controller;

class EptitleController extends Controller {
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
     * 公益数据
     */
    public function actionGy_data() {
        $data = [
            ["名称", "STRING", "TYPE"],
            ["数值", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 环保数据集合
     */
    public function actionEp_data() {
        $data = [
            ["名称", "STRING", "TYPE"],
            ["数值", "NUMBER", "NUM"]
        ];
        $data = $this->titleData($data);
        die(json_encode($data));
    }


    /**
     * 公益方式数据
     */
    public function actionGy_type() {
        $data = [
            ["城市信息", "STRING", "CITY"],
            ["扶贫区", "STRING", "AREA"],
            ["单位信息", "STRING", "DANWEI"],
            ["扶贫机构", "STRING", "JIGOU"]
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