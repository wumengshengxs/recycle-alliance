<?php
namespace common\logic;

class Logic{
    private static $mine;
    
    protected function __construct(){}
    
    public static function getInstance($class){
        $class = 'common\logic\\' . $class . 'Logic';
        self::$mine = new $class();
        return self::$mine;
    }
}

