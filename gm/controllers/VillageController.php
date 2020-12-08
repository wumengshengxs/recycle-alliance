<?php
namespace gm\controllers;

use gm\models\PositionVillage;
use gm\models\SrRecycler;
use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class VillageController extends GController
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


    /**
     * 小区列表
     * @return string
     */
    public function actionList()
    {
        $province = $this->actionRegion_tx();
        return $this->render('index', ['province' => json_decode($province, 1)]);
    }

    /**
     * 抓取小区列表数据
     */
    public function actionAjax_list(){
        //获取页码与数据长度
        list($start,$length) = $this->getOffset();
        $province = yii::$app->request->get('province');
        $city = yii::$app->request->get('city');
        $district = yii::$app->request->get('district');
        $village_name = yii::$app->request->get('village_name');
        $street = yii::$app->request->get('street');
        $like_where = [];
        $where = [];
        $andWhere = [];
        $this->user_admin && $andWhere['agent'] = $this->agent_id;
        empty($village_name) || $like_where = ['like', 'village_name', $village_name];
        empty($province) || $where = ['province_name' => $province];
        empty($city) || $where = [ 'city' => $city];
        empty($district) || $where = ['county_name'=> $district];
        empty($street) || $where = ['street_name'=> $street];
        //查询投递数据列表
        $positionVillage = PositionVillage::find()
            ->select('p_id,province_name, village_name, county_name,street_name, town_name, create_time, machine_num')
            ->andWhere($andWhere)
            ->andWhere($where)
            ->andWhere(['del_flag' => 0])
            ->andWhere($like_where)
            ->orderBy(['p_id' => SORT_DESC])
            ->offset($start)
            ->limit($length)->asArray()->all();

        //获取记录总数
        $total = PositionVillage::find()
            ->andWhere($where)
            ->andWhere($andWhere)
            ->andWhere(['del_flag' => 0])
            ->andWhere($like_where)
            ->count();

        //生成dataTable格式数据
        $data = [];
        foreach ($positionVillage as $v) {
            $temp = [];
            $temp['p_id'] = $v['p_id'];
            $temp['village_name'] = $v['village_name'];
            $temp['county_name'] = $v['province_name'].'-'.$v['county_name'];
            $temp['street_name'] = $v['street_name'];
            $temp['town_name'] = $v['town_name'];
            $temp['machine_num'] = $v['machine_num'];
            $temp['create_time'] = $v['create_time'];
            $temp['opt'] = '<a href="javascript:(0)" onclick="village_edit('.$v['p_id'].')">编辑</a>';
//            $temp['opt'] .= ' | <a href="javascript:(0)" onclick="village_del('.$v['p_id'].')">删除</a>';
            array_push($data, array_values($temp));
        }

        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));
    }

    /**
     * 小区编辑页面
     * @return string
     */
    public function actionVillage_edit()
    {
        $id = Yii::$app->request->get('id');
        $village = PositionVillage::find()
            ->select('p_id,province_name,city, village_name, county_name,street_name,town_name')
            ->where(['p_id' => $id])
            ->asArray()->one();
        $province = $this->actionRegion_tx();

        return $this->renderPartial('village_edit', [
            'village' => $village,
            'province' => json_decode($province, 1)
        ]);
    }

    /**
     * 小区编辑提交
     * @return false|string
     */
    public function actionVillage_save()
    {
        $param = Yii::$app->request->post();
        $model = new PositionVillage();
        $list = array_count_values($param);
        if (!empty($list[""])){
            return $this->AjaxResult(FAILD,'提交的信息不能为空');
        }
        $village = $model->findOne($param['id']);

        if (empty($village)){
            return $this->AjaxResult(FAILD,'数据异常');
        }
        $data['PositionVillage'] = [
            'province_name' => trim($param['province_name']),
            'city' => trim($param['city']),
            'county_name' => trim($param['county_name']),
            'street_name' => trim($param['street_name']),
            'town_name' => trim($param['town_name']),
            'update_time' => date('Y-m-d H:i:s'),
        ];

        //添加修改
        if (!$village->load($data) || !$village->save()) {
            return $this->AjaxResult(FAILD,'修改失败',$village->getErrors());
        }

        return $this->AjaxResult(SUCCESS,'修改成功');
    }



}
