<?php

namespace bigdata\controllers;

use bigdata\models\SrUserDeliveryHistoryChildBrush;

class EpdataController extends BController
{
    /**
     * 节约标准煤
     */
    public function actionJy_bzm() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval(($all['num']*0.6)/0.68)
        ]];
        die(json_encode($data));
    }


    /**
     * 减少垃圾焚烧(纸、书)
     */
    public function actionJs_ljfs() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->where(['in', 'delivery_type', [2,3]])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num']*0.3)
        ]];
        die(json_encode($data));
    }


    /**
     * 减少垃圾填埋(瓶、纺织物、塑料)
     */
    public function actionJs_ljtm() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->where(['in', 'delivery_type', [1,5,4]])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num'])
        ]];
        die(json_encode($data));
    }


    /**
     * 减少碳排放
     */
    public function actionJs_tpf() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval(($all['num']*0.6)/0.272)
        ]];
        die(json_encode($data));
    }


    /**
     * 垃圾减量化总量
     */
    public function actionLj_jsh() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num'])
        ]];
        die(json_encode($data));
    }


    /**
     * 垃圾无害化总量
     */
    public function actionLj_whh() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num']*0.3)
        ]];
        die(json_encode($data));
    }


    /**
     * 垃圾资源化总量
     */
    public function actionLj_zyh() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num']*0.7)
        ]];
        die(json_encode($data));
    }


    /**
     * 助销农产品
     */
    public function actionZx_ncp() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num')
            ->where(['delivery_type' => 4])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num']*0.1)
        ]];
        die(json_encode($data));
    }


    /**
     * 捐赠衣物
     */
    public function actionJz_yw() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num')
            ->where(['delivery_type' => 5])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval(($all['num']*0.1)/0.17)
        ]];
        die(json_encode($data));
    }


    /**
     * 捐赠图书
     */
    public function actionJz_ts() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num')
            ->where(['delivery_type' => 3])
            ->asArray()->one();
        $data = [[
            'SHOW' => empty($all['num']) ? 0 : intval($all['num']*0.1*2)
        ]];
        die(json_encode($data));
    }


    /**
     * 惠及人数
     */
    public function actionHj_rs() {
        $big = [
            [
                'id' => 4,
                'name' => '助销农产品',
                'rate' => 0.1
            ], [
                'id' => 5,
                'name' => '捐赠衣物',
                'rate' => 0.1/0.17
            ], [
                'id' => 3,
                'name' => '捐赠图书',
                'rate' => 0.2
            ]
        ];

        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num, delivery_type')
            ->where(['in', 'delivery_type', [3, 4, 5]])
            ->groupBy('delivery_type')
            ->asArray()->all();
        $all_temp = [];
        foreach ($all as $a) {
            $all_temp[$a['delivery_type']] = $a['num'];
        }
        $all_num = 0;
        foreach ($big as $b) {
            if (array_key_exists($b['id'], $all_temp)) {
                $all_num += intval($all_temp[$b['id']]* $b['rate']);
            }
        }
        $data = [[
            'SHOW' => $all_num
        ]];
        die(json_encode($data));
    }


    /**
     * 公益数据
     */
    public function actionGy_data() {
        $big = [
            [
                'id' => 4,
                'name' => '助销农产品',
                'rate' => 0.1
            ], [
                'id' => 5,
                'name' => '捐赠衣物',
                'rate' => 0.1/0.17
            ], [
                'id' => 3,
                'name' => '捐赠图书',
                'rate' => 0.2
            ], [
                'id' => 0,
                'name' => '惠及人数',
                'rate' => 1
            ]
        ];

        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(`delivery_count`) as num, delivery_type')
            ->where(['in', 'delivery_type', [3, 4, 5]])
            ->groupBy('delivery_type')
            ->asArray()->all();
        $all_temp = [];
        foreach ($all as $a) {
            $all_temp[$a['delivery_type']] = $a['num'];
        }
        $data = [];
        $all_num = 0;
        foreach ($big as $b) {
            $temp = [
                'TYPE' => $b['name'],
                'NUM' => 0
            ];
            if (array_key_exists($b['id'], $all_temp)) {
                $temp['NUM'] = intval($all_temp[$b['id']]* $b['rate']);
                $all_num += $temp['NUM'];
            }
            if ($b['id'] == 0) {
                $temp['NUM'] = intval($all_num);
            }
            $data[] = $temp;
        }
        die(json_encode($data));
    }


    /**
     * 公益方式数据
     */
    public function actionGy_type() {
//1青海省：西宁市大通县：大通社会公益事业工作发展中心，公益事业
//2四川省：凉山州：凉山关爱弱势群体救助会，弱势群体救助
//3浙江省：金华市：扶贫公益联盟：扶贫助贫
//4安徽省：安庆市：盛开公益发展中心：公益救助
//5新疆：石河子市：馨美土特产发展组织：助农扶贫
//6 湖北省：丹江口市：大名青少年发展协会：环保教育
//7上海市：宝山区：上海零废弃公益基金会：节能减排

        $data = [
            [
                'CITY' => '青海省',
                'AREA' => '西宁市大通县',
                'DANWEI' => '大通社会公益事业工作发展中心',
                'JIGOU' => '公益事业'
            ],[
                'CITY' => '四川省',
                'AREA' => '凉山州',
                'DANWEI' => '凉山关爱弱势群体救助会',
                'JIGOU' => '弱势群体救助'
            ],[
                'CITY' => '浙江省',
                'AREA' => '金华市',
                'DANWEI' => '扶贫公益联盟',
                'JIGOU' => '扶贫助贫'
            ],[
                'CITY' => '安徽省',
                'AREA' => '安庆市',
                'DANWEI' => '盛开公益发展中心',
                'JIGOU' => '公益救助'
            ],[
                'CITY' => '新疆',
                'AREA' => '石河子市',
                'DANWEI' => '馨美土特产发展组织',
                'JIGOU' => '助农扶贫'
            ],[
                'CITY' => '湖北省',
                'AREA' => '丹江口市',
                'DANWEI' => '大名青少年发展协会',
                'JIGOU' => '环保教育'
            ],[
                'CITY' => '上海市',
                'AREA' => '宝山区',
                'DANWEI' => '上海零废弃公益基金会',
                'JIGOU' => '节能减排'
            ]
        ];
        die(json_encode($data));
    }


    /**
     * 环保数据集合
     */
    public function actionEp_data() {
        $all = SrUserDeliveryHistoryChildBrush::find()
            ->select('SUM(IF(`delivery_type` = 1,`delivery_count`/50,`delivery_count`)) as num')
            ->asArray()->one();
        $num = empty($all['num']) ? 0 : $all['num'];

        $data = [
            [
                'TYPE' => '节约标准煤',
                'NUM' => $num * 0.7
            ],[
                'TYPE' => '减少垃圾焚烧',
                'NUM' => $num * 0.3
            ],[
                'TYPE' => '减少垃圾填埋',
                'NUM' => $num * 0.2
            ],[
                'TYPE' => '减少碳排放',
                'NUM' => $num * 0.5
            ],[
                'TYPE' => '垃圾减量化总量',
                'NUM' => $num
            ],[
                'TYPE' => '垃圾无害化总量',
                'NUM' => $num * 0.3
            ],[
                'TYPE' => '垃圾资源化总量',
                'NUM' => $num * 0.7
            ]
        ];
        die(json_encode($data));
    }
}