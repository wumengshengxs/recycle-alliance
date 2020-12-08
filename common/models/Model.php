<?php
namespace common\models;

/**
 * 业务模型工厂
 * @author Administrator
 */
class Model{
    
    private static $mine;
    
    protected function __construct(){}
    
    public static function getInstance($class){
        $class = 'common\models\\' . $class;
        self::$mine = new $class();
        return self::$mine;
    }
    
    public static function getClass($class){
        return 'common\models\\' . $class;
    }
}

