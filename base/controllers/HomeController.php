<?php
namespace base\controllers;

use common\logic\Logic;
use Yii;
/**
 * 门户控制器
 * 应用场景主要面对于首页所需显示的内容
 * 内容以插件方法形式展现
 * 固定方法
 *  定位信息
 *  banner图展示
 *  一级分类
 *  二级分类
 * 活动方法
 *  热门产品推荐(含分页效果)
 */
class HomeController extends RestController{
    
    public function behaviors(){
        $behaviors = parent::behaviors();
        //不需要权限访问的action
        $behaviors['authenticator']['optional'] = [
            'test'
        ];
        return $behaviors;
    }
    
    public function actionTest(){
        $cart = Logic::getInstance('Cart')->newCart();
        return $cart;
    }
    
    /**
     * 首页
     */
    public function actionIndex(){
        //参数校验
        /*if($error = $this->setForm($this->getPost())){
            return $error;
        }*/
        
        //调用方法或logic
        $postion = $this->actionPosition(false);
        $banner = $this->actionBanner(false);
        $category1 = $this->actionCategory1(false);
        $category2 = $this->actionCategory2(false);
        $hot = Logic::getInstance('Goods')->getByHot();
        $goods = Logic::getInstance('Goods')->getByCategoryId(1);
        $user = $this->getUserInfo();
        
        //组装渲染数据
        $data = [
            'position' => $postion,
            'banner' => $banner,
            'category1' => $category1,
            'category2' => $category2,
            'hot' => $hot,
            'user' => $user,
        ];
        //输出数据
        return $this->response($data);
    }
    
    /**
     * 定位信息
     */
    public function actionPosition($http_reqeust = true){
        $data = Logic::getInstance('Home')->getPostion();
        if($http_reqeust){
            return $this->response(['position' => $data]);
        }
        return $data;
    }

    /**
     * banner信息
     */
    public function actionBanner($http_reqeust = true){
        $data = Logic::getInstance('Home')->getBanner();
        if($http_reqeust){
            return $this->response(['banner' => $data]);
        }
        return $data;
    }
    
    /**
     * 一级分类
     */
    public function actionCategory1($http_reqeust = true){
        $data = Logic::getInstance('Home')->getFirstCategory();
        if($http_reqeust){
            return $this->response(['category' => $data]);
        }
        return $data;
    }
    
    /**
     * 二级分类
     */
    public function actionCategory2($http_reqeust = true){
        $data = Logic::getInstance('Home')->getSecondCategory();
        if($http_reqeust){
            return $this->response(['category' => $data]);
        }
        return $data;
    }
    
    /**
     * 推荐产品
     */
    public function actionHot($http_reqeust = true){
        $data = Logic::getInstance('Home')->getHot();
        if($http_reqeust){
            return $this->response(['hot' => $data]);
        }
        return $data;
    }
}

