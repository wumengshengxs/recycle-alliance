<?php

namespace base\modules\v1\controllers;

use yii\web\Controller;
use base\controllers\RestController;


class HomeController extends RestController{
    
    public function actionIndex(){
        return 'helloworld';
    }
    
}
