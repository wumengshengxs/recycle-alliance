<?php
namespace common\logic;

class GoodsLogic extends BaseLogic{
    
    /**
     * 按类别获取产品
     */
    public function getByCategoryId($id, $sort = [], $page = []){
        return [];
    }
    
    /**
     * 按id获取产品
     */
    public function getById($id){
        return;
    }
    
    /**
     * 获取所有产品
     */
    public function getAll($sort = [], $page = []){
        return [];
    }
    
    /**
     * 获取热门产品
     */
    public function getByHot($sort = [], $page = []){
        return [];
    }
}

