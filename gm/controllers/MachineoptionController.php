<?php
namespace gm\controllers;

use gm\models\PositionVillage;
use gm\models\SrApkRepository;
use gm\models\SrDeviceCode;
use gm\models\SrRecyclingMachine;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class MachineoptionController extends GController
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


    public function actionAdd()
    {
        $cang = Yii::$app->request->post('cang');
        $version = Yii::$app->request->post('version');
        $village = Yii::$app->request->post('village');
        $detail = Yii::$app->request->post('detail');
        $id_code = Yii::$app->request->post('id_code');
        $longitude = Yii::$app->request->post('longitude');
        $latitude = Yii::$app->request->post('latitude');
        $village_info = PositionVillage::find()
            ->where(['p_id' => $village])
            ->asArray()->one();
        if (empty($village_info)) {
            die(json_encode(['res' => false, 'msg' => '小区信息错误']));
        }
        $max_code = SrDeviceCode::find()
            ->select('machine_code')
            ->where(['zip_code' => $village_info['village_code']])
            ->orderBy(['machine_code' => SORT_DESC])
            ->asArray()->one();
        $max_code = empty($max_code) ? 1 : $max_code['machine_code'] + 1;
        $insert_code = $village_info['village_code'] * 10000 + $max_code;
        $insert = new SrRecyclingMachine();
        $time = date('Y-m-d H:i:s');
        $insert->setAttributes([
            'device_id' => $insert_code . '',
            'icc_id' => $id_code,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'version' => '',
            'total' => 0,
            'create_date' => $time,
            'update_date' => $time,
            'location' => $detail,
            'province_name' => $village_info['province_name'],
            'county_name' => $village_info['county_name'],
            'street_name' => $village_info['street_name'],
            'position_village_id' => $village_info['p_id'],
            'time_start' => '00:00',
            'time_end' => '00:00',
            'community_name' => $village_info['village_name'],
            'handshake_cycle' => '5',
            'del_flag' => '0',
            'sign_key' => time() . '',
            'status' => '0',
            'current_apk_id' => '0',
            'update_apk_id' => '0',
            'divece_code' => $cang,
            'divece_version' => $version,
            'is_ams_apk' => '1',
        ]);

        $insert_code = new SrDeviceCode();
        $insert_code->setAttributes([
            'zip_code' => $village_info['village_code'],
            'machine_code' => $max_code
        ]);
        $transaction = yii::$app->db->beginTransaction();
        if ($insert->save() && $insert_code->save()) {
            $transaction->commit();
            die(json_encode(['res' => true, 'msg' => '添加成功']));
        }
        $transaction->rollBack();
        die(json_encode(['res' => false, 'msg' => '添加失败']));
    }


    public function actionApk_add()
    {
        $name = Yii::$app->request->post('name');
        $version = Yii::$app->request->post('version');
        $link = Yii::$app->request->post('link');
        $apk_md5 = md5_file($link);
        $apk = new SrApkRepository();
        $apk->setAttributes([
            'apk_name' => $name,
            'apk_version' => $version,
            'apk_download' => $link,
            'apk_md5' => $apk_md5
        ]);
        if ($apk->save()) {
            die(json_encode(['res' => true, 'msg' => '添加成功']));
        }
        die(json_encode(['res' => false, 'msg' => '添加失败']));
    }

    public function actionApk_to_machine()
    {
        $id = Yii::$app->request->post('id');
        $version = Yii::$app->request->post('version');
        $area = Yii::$app->request->post('area');
        $community = Yii::$app->request->post('community');
        if (empty($id) || !is_numeric($id)) {
            die(json_encode(['res' => false, 'msg' => 'id错误']));
        }
        if (empty($version) || !is_array($version)) {
            die(json_encode(['res' => false, 'msg' => '版本号错误']));
        }
        if (empty($area) || !is_array($area)) {
            die(json_encode(['res' => false, 'msg' => '区域错误']));
        }
        if (empty($community) || !is_array($community)) {
            die(json_encode(['res' => false, 'msg' => '小区错误']));
        }
        $version_info = SrApkRepository::find()
            ->where(['id' => $id])
            ->asArray()->one();
        if (empty($version_info)) {
            die(json_encode(['res' => false, 'msg' => 'id数据错误']));
        }
        $where = '';
        foreach ($version as $v) {
            $temp = explode('-', $v);
            $where .= (empty($where) ? '' : ' OR ' ) . "(divece_code='" . $temp[0] . "' and divece_version='" . $temp[1] . "')";
        }
        $where = "(" . $where . ")";
        $where .= " AND `street_name` IN ('" . implode("','", $area) . "')";
        $where .= " AND `community_name` IN ('" . implode("','", $community) . "')";
        $where .= " AND `update_apk_id` <> " . $id;
        $where .= " AND `is_ams_apk` = 1";
        $count = SrRecyclingMachine::find()
            ->where($where)
            ->count();
        $update_count = SrRecyclingMachine::updateAll(['update_apk_id' => $id], $where);
        die(json_encode(['res' => true, 'msg' => '筛选数量' . $count . "更新数量" . $update_count]));
    }

}