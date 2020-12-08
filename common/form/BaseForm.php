<?php
namespace common\form;

use yii\base\Model;

class BaseForm extends Model{
    
    public $param1;
    public $param2;
    public $param3;
    public $param4;
    public $rememberMe = true;
    
    
    /**
     * 定义参数规则
     */
    public function rules(){
        return [
            // 属性不能为空
            [['param1', 'param2'], 'required', 'on' => ['home/index']],
            // 属性需是4-8个字符的字符串
            ['param3', 'string', 'max' => [4, 8], 'on' => ['scenarios2']],
            // param1与param2属性必须相等
            ['param1', 'compare', 'compareAttribute' => 'param2', 'on' => ['scenarios3']],
            ['param4', 'email', 'on' => ['scenarios4']],
        ];
    }
    
}

