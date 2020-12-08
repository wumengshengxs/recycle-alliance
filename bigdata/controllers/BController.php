<?php

namespace bigdata\controllers;

use bigdata\models\SrUserDeliveryHistoryChildBrush;
use bigdata\models\SrUserDeliveryHistoryParentBrush;
use yii\rest\Controller;

class BController extends Controller {
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        //是否刷量  true/false
        $is_brush = false;
        SrUserDeliveryHistoryChildBrush::is_brush($is_brush);
        SrUserDeliveryHistoryParentBrush::is_brush($is_brush);
    }
}