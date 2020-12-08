<?php
namespace common\logic;

use common\models\Model;

class CartLogic extends BaseLogic{
    
    public function getCart(){
        $cart = Model::getClass('Cart');
        return $cart::findOne(1);
    }
    
    public function newCart(){
        $cart = Model::getInstance('Cart');
        $cart->setAttributes([
            'user_id' => 23,
            'goods_price' => 21,
            'goods_unit' => '件'
        ]);
        return $cart->save();
    }
}

