<?php

namespace gm\controllers;

use common\form\AdminForm;
use common\models\Agent;
use gm\models\SrUserDeliveryHistoryAbnormal;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends GController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'restpassword'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $total = SrUserDeliveryHistoryAbnormal::find()
            ->count();
        return $this->render('index', ['abnormal' => $total]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new AdminForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';
            $this->layout = "login";
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * 重置密码
     */
    public function actionRestpassword(){
        //首先进入手机号验证码页面
        if(!yii::$app->request->post('next') && !yii::$app->request->post('step')) {
            return $this->renderPartial('restpassword1', ['mobile' => '', 'msg' => false]);
        }

        //然后校验手机号与验证码并进入设置密码页面
        if(yii::$app->request->post('next') && yii::$app->request->post('step') == 1) {
            $mobile = yii::$app->request->post('mobile');
            $vcode = yii::$app->request->post('vcode');
            $where = ['type' => 1, 'mobile' => $mobile, 'content' => $vcode];
            $sms = $this->findWhereOne('SrSms', $where);
            //如果手机号与验证码不匹配则返回到手机号验证码页面
            if (!$sms) {
                return $this->renderPartial('restpassword1', ['mobile' => $mobile, 'msg' => true]);
            }
            //对重置密码token进行标记确保修改密码的安全性
            $token = time();
            $agent = Agent::find()->where(['username' => $mobile])->one();
            $agent->setAttribute('password_reset_token', $token);
            $agent->save();
            return $this->renderPartial('restpassword2', ['token' => $token, 'id' => $agent->id]);
        }

        //最后保存密码
        if(yii::$app->request->post('next') && yii::$app->request->post('step') == 2) {
            $password = yii::$app->request->post('password');
            $repassword = yii::$app->request->post('repassword');
            $id = yii::$app->request->post('id');
            $token = yii::$app->request->post('token');
            //校验安全性
            $agent = Agent::find()->where(['id' => $id])->one();
            //校验失败则直接进入第一步
            if(!$agent){
                return $this->renderPartial('restpassword1', ['mobile' => '', 'msg' => false]);
            }
            //保存密码
            $agent->setAttributes([
                'password_hash' => Yii::$app->security->generatePasswordHash($password),
                'password_reset_token' => '',
                'security' => $password,
            ]);
            $res = $agent->save();
            return $this->renderPartial('restpassword3');
        }
    }
}
