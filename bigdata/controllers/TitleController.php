<?php

namespace bigdata\controllers;

use yii\rest\Controller;

class TitleController extends Controller {

    /*
     * */

    public function actionAbc() {
        die(json_encode([
            [
                "COL1" => "纸类",
                "COL2" => 100,
                "COL3" => "宝山"
            ],[
                "COL1" => "纸类",
                "COL2" => 200,
                "COL3" =>"徐汇"
            ],[
                "COL1" => "瓶子",
                "COL2" => 100,
                "COL3" =>"徐汇"
            ]]));
    }

    public function actionAaa() {
        die(json_encode([
                [
                    "COLNAME" => "分类1",
                    "COLTYPE" => "STRING",
                    "COL" => "COL1"
                ], [
                    "COLNAME" => "重量",
                    "COLTYPE" => "NUMBER",
                    "COL" => "COL2"
                ], [
                    "COLNAME" => "区域",
                    "COLTYPE" => "STRING",
                    "COL" => "COL3"
                ]
            ]));
    }
}