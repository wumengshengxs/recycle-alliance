<?php
namespace base\controllers;

use yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use common\form\BaseForm;

/**
 * restful基础类
 * 所有api业务类都需继承该类
 * 访问权限由yii2的授权系统提供
 * @author 郑宇翔
 */
class RestController extends ActiveController{
    
    const FAILD = 100;      //请求失败
    const SUCCESS = 200;    //请求成功
    const NODATA = 201;     //响应空数据
    const PERMISSION = 401; //权限错误(此常量仅用作代码显示参考，实际权限过滤由yii2的QueryParamAuth处理)
    
    public $modelClass = 'common\models\User';
    
    public function init(){
        parent::init();
        Yii::$app->user->enableSession = false;
        if($data_safe = $this->dataSafe()){
            die($data_safe);
        }
    }
    
    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'token'
        ];
        //系统接口仅支持post和get两种方式的访问
        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['post', 'get'],
                'view' => ['post', 'get'],
                'create' => ['post', 'get'],
                'update' => ['post', 'get'],
                'delete' => ['post', 'get']
            ],
        ];
        return $behaviors;
    }
    
    public function actions(){
        $actions = parent::actions();
        // 禁用系统rest绑定操作
        unset(
            $actions['index'],
            $actions['delete'],
            $actions['create'],
            $actions['view'],
            $actions['update'],
            $actions['options']
        );
        return $actions;
    }
    
    /**
     * 表单校验
     * 规则添加在demoForm中
     */
    protected function setForm($data){
        $form = new BaseForm();
        //设置适用场景(规则为[controller/action])
        $form->setScenario($this->id . '/' . $this->action->id);
        $form->setAttributes($data);
        if(!$form->validate()){
            $error = reset($form->getErrors())[0];
            return $this->response($error, self::FAILD);
        }
        return false;
    }
    
    /**
     * api输出函数，返回接口结果
     * @param unknown $data
     * @param unknown $status
     */
    protected function response($data, $status = self::SUCCESS){
        //预定义输出模板
        $params = [
            'name' => '',
            'message' => '',
            'code' => 0,
            'status' => $status,
            'type' => '',
            'data' => '',
        ];
        if($status == self::FAILD){
            $params['name'] = 'faild';
            $params['message'] = $data;
        }
        if(empty($data)){
            $params['name'] = 'no data';
            $params['message'] = 'no data';
            $status = self::NODATA;
        }
        if($status == self::SUCCESS){
            $params['name'] = 'success';
            $params['data'] = $data;
        }
        return yii::createObject($this->serializer)->serialize($params);
    }
    
    /**
     * 获取userId
     */
    protected function getUserId(){
        $user_id = 0;
        empty(yii::$app->user->identity) || $user_id = yii::$app->user->identity->id;
        return $user_id;
    }
    
    /**
     * 获取微信openid
     */
    protected  function getWxOpenid(){
        $wx_openid = '';
        empty(yii::$app->user->identity) || $wx_openid = yii::$app->user->identity->wx_openid;
        return $wx_openid;
    }
    
    /**
     * 获取完整用户信息
     */
    protected function getUserInfo(){
        $user_info = [];
        empty(yii::$app->user->identity) || $user_info = yii::$app->user->identity->toArray();
        return $user_info;
    }
    
    /**
     * 获取post数据
     */
    protected function getPost($param = ''){
        if(empty($param)){
            return yii::$app->request->post();
        }
        return yii::$app->request->post($param);
    }
    
    /**
     * 数据安全
     * 监控数据库状态，如若数据库有异常则需停止业务处理
     */
    private function dataSafe(){
        $tableNames = \Yii::$app->db->schema->tableNames;
        $models_dir = realpath(\Yii::$app->basePath . '/../common/models/');
        $file = scandir($models_dir);
        if(count($file) - 4 != count($tableNames)){
            //return '数据故障1';
        }
    }
}