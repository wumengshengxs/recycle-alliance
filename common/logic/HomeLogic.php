<?php
namespace common\logic;

/**
 * 首页业务层
 * @author Administrator
 */
class HomeLogic extends BaseLogic{
    
    /**
     * 获取定位信息
     */
    public function getPostion(){
        return [
            'city' => '上海市',
        ];
    }
    
    /**
     * 获取banner图片
     */
    public function getBanner(){
        return [
            '/banner1',
            '/banner2',
            '/banner3',
        ];
    }
    
    /**
     * 获取一级分类
     */
    public function getFirstCategory(){
        return [
            '一级1',
            '一级2',
            '一级3',
            '一级4',
        ];
    }
    
    /**
     * 获取二级分类
     */
    public function getSecondCategory(){
        return [
            '二级1',
            '二级2',
            '二级3',
            '二级4',
        ];
    }
    
    
}

