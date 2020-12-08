<?php
namespace gm\controllers;

use gm\models\SrRecycler;
use gm\models\SrUserBookOrder;
use gm\models\SrUserBookRecycler;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class BookoptionController extends GController
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
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionCancel()
    {
        $cancel_info = Yii::$app->request->post('cancel_info');
        $order_id = Yii::$app->request->post('order_id');
        $book = SrUserBookOrder::find()
            ->where(['order_id' => $order_id])
            ->one();
        if (empty($book)) {
            die(json_encode(['res' => false, 'msg' => '订单号错误']));
        }
        if (!in_array($book['status'], [0, 1])) {
            die(json_encode(['res' => false, 'msg' => '订单状态错误错误']));
        }
        $book->status = 2;
        $book->cancellation_type = 1;
        $book->cancel_info = $cancel_info;
        if (!$book->save()) {
            die(json_encode(['res' => false, 'msg' => '取消失败']));
        }
        die(json_encode(['res' => true, 'msg' => '取消成功']));
    }

    public function actionChange()
    {
        $order_id = Yii::$app->request->post('order_id');
        $recycle = Yii::$app->request->post('recycle');
//        var_dump($recycle);
//        var_dump($this->post('recycle'));
//        var_dump($_POST['recycle']);
//        var_dump($_REQUEST['recycle']);die();
//die($recycle);
        $recycle_info = SrRecycler::find()
            ->where(['id' => $recycle])
            ->one();
        $book = SrUserBookOrder::find()
            ->where(['order_id' => $order_id])
            ->one();
        if (empty($book)) {
            die(json_encode(['res' => false, 'msg' => '订单号错误']));
        }
        if (!in_array($book['status'], [0, 1])) {
            die(json_encode(['res' => false, 'msg' => '订单状态错误错误']));
        }
        $book->recycl_id = $recycle;
        $book->status = 1;
        $transaction = Yii::$app->db->beginTransaction();
        $bookRecycle = new SrUserBookRecycler();
        $bookRecycle->admin_name = Yii::$app->user->identity->username;
        $bookRecycle->book_order_id = $order_id;
        $bookRecycle->recycler_id = $recycle;
        $bookRecycle->recycler_name = $recycle_info->nick_name;
        $bookRecycle->type = 1;
        if ($book->save() && $bookRecycle->save()) {
            $transaction->commit();
            die(json_encode(['res' => true, 'msg' => '派单成功']));
        }
        $transaction->rollBack();
        die(json_encode(['res' => false, 'msg' => '派单失败']));

    }

}