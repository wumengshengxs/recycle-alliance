<?php

/**
 * 维修人员
 */
namespace gm\controllers;


use common\models\Agent;
use gm\models\SysAd;
use gm\models\PositionVillage;
use gm\models\SysAdImages;
use gm\models\SysAdImagesStock;
use gm\models\SysAdSpace;
use gm\models\SrRecyclingMachine;
use gm\models\SysMachineAdSpace;
use yii\filters\AccessControl;
use Yii;
use common\aliyunoss\OSS\OssClient;
use common\aliyunoss\OSS\Core\OssException;

class AdController extends GController
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
    public function actionAd_space()
    {
        return $this->render('ad_space');


    }

    public function actionAjax_ad_space()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = 2;//empty($_GET['length']) ? 10 : $_GET['length'];
        $where['del_flag'] = 0;
        $this->user_admin && $where['agent'] = $this->agent_id;
        $list = SysAdSpace::find()
            ->select('*')
            ->where($where)
            ->offset(($start)/5*4)
            ->limit($length * 4)->asArray()->all();
        $data = [];
        $row = [];
        //print_r(json_encode($list));exit;
        foreach ($list as $item) {
            $temp = '<div class="product">';
            $temp .= '<div class="product-top">';
            $temp .= '<a onclick="ad_detail(' . $item['id'].', \'' . $item['name'] . '\')"> <img src="' . $item['tiny_img'] . '" alt=""></a>';
            $temp .= '</div>';
            $temp .= '<div class="product-bottom">';
            $temp .= '<div>' . $item['name'] . '</div>';
            $temp .= '<div>' . $item['version'] . '</div>';
            $temp .= '</div>';
            $temp .= '<button class="btn  btn-primary col-sm-offset-8 product-button" onclick="add_space('.$item['id'].')">设置定向</button>';
            $temp .= '<button class="btn  btn-primary col-sm-offset-8 del-button" onclick="del_space('.$item['id'].')">删除广告位</button>';
            $temp .= '</div>';
            $row[] = $temp;
            if (count($row) == 4) {
                array_push($data, array_values($row));
                $row = [];
            }
        }
        if (!empty($row)) {
            $num = count($row);
            for ($i = 0; $i <= 4 - $num; $i ++) {
                $row[] = "";
            }
        }

        empty($row) || array_push($data, array_values($row));

        //获取记录总数
        $total = SysAdSpace::find()
            ->select('*')
            ->where(['del_flag' => 0])
            ->count();
        $total = $total/4*5;
        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];

        die(json_encode($data_source));
    }

    //创建广告位页面
    public function actionAdd_ad_space()
    {
      /*  //生成随机小写字母数字14位组合
        function getRandomString($len, $chars = null)
        {
            if (is_null($chars)) {
                $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            }
            mt_srand(10000000 * (double)microtime());
            for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
                $str .= $chars[mt_rand(0, $lc)];
            }
            return $str;
        }*/

        $code = 111;
        $sys_info = SysAdSpace::find()
            ->select('code')
            ->groupBy('code')
            ->asArray()
            ->all();
        $type_list = array_column($sys_info,'code');  //Array ( [0] => 1 ,[0] => 2,[0] => 3 )
        $types = [ ];

        foreach ($type_list as $item) {
            $d = [
                'id' => $item,
                'name' => ''
            ];

            if(strcasecmp($item,"xcx")== 0){
                $d['name'] = '用户端小程序';
                $types[] = $d;
            }else if(strcasecmp($item,"dj")== 0){
                $d['name'] = '回收机待机轮播';
                $types[] = $d;
            }else if(strcasecmp($item,"db")== 0){
                $d['name'] = '回收机底部轮播';
                $types[] = $d;
            }

        }
        $aid = $this->user['id'];
        $agent = Agent::find()
            ->select('id,admin')
            ->where(['id' => $aid])
            ->asArray()->one();
        //`admin` tinyint(1) DEFAULT '0' COMMENT '管理员类型 0:全局管理员 1:代理商管理员',
        if($agent['admin'] == 0){
            $agent_list = Agent::find()
                ->select('id,username')
                ->asArray()->all();
        }else{
            $agent_list = Agent::find()
                ->select('id,username')
                ->where(['id' => $aid])
                ->asArray()->all();
        }


        return $this->renderPartial('add_ad_space', ['code' => $code,'type_list' => $types,'agent_list' => $agent_list]);
    }

    //广告位创建保存
    public function actionSpace_create()
    {
        $code = Yii::$app->request->get('code');
        $ad_name = Yii::$app->request->get('ad_name');
        $agent_id = Yii::$app->request->get('agent_id');
        $ad_resolution_ratio = Yii::$app->request->get('ad_resolution_ratio');
        $img_urls = Yii::$app->request->get('img_urls');
        $time = date('Y-m-d H:i:s');
        $temp = new SysAdSpace();
        $temp->name = $ad_name;
        $temp->agent = $agent_id;
        $temp->type = 1;
        $temp->code = $code;
        $temp->version = '1';
        $temp->tiny_img = $img_urls;
        $temp->resolution_ratio = $ad_resolution_ratio;
        $temp->create_time = $time;
        $temp->update_time = $time;
        $temp->del_flag = 0;
        $temp->save();
        if ($temp->save()) {
            return json_encode(['res' => true, 'msg' => '新增成功']);
        }
        return json_encode(['res' => false, 'msg' => '新增失败']);
    }

    /**
     * 广告列表
     */
    public function actionAd_detail()
    {
        $id = Yii::$app->request->get('id');
        $name = Yii::$app->request->get('name');
        return $this->render('ad_detail', ['id' => $id,'name'=>$name]);
    }

    /**
     * 广告列表
     */
    public function actionAjax_ad_detail()
    {
        $id = Yii::$app->request->get('id');

        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = 2;//empty($_GET['length']) ? 10 : $_GET['length'];
        $list = SysAd::find()
            ->select('*')
            ->where(['del_flag' => 0,'parent_id' =>$id])
            ->offset(($start)/5*4)
            ->limit($length * 4)->asArray()->all();

        $data = [];
        $row = [];
        foreach ($list as $item) {
            $temp = '<div class="product">';
            $temp .= '<div class="product-top">';
            $temp .= '<a onclick="ad_detail(' . $item['id'] . ')"> <img src="' . $item['img'] . '" alt=""></a>';
            $temp .= '</div>';
            $temp .= '<div class="product-bottom">';
            $temp .= '<div>' . $item['name'] . '</div>';
            $temp .= '<div>' . $item['jump_link'] . '</div>';
//            $temp .= '<input type="checkbox" name="ids" class="checklist" value='.$item['id'].'>';
            $temp .= '</div>';
            $temp .= '<button class="btn  btn-info col-sm-offset-8 del-button" onclick="del_space('.$item['id'].')">删除广告</button>';
            $temp .= '<button class="btn  btn-info col-sm-offset-8 update-button" onclick="update_space('.$item['id'].')">编辑广告</button>';
            $temp .= '</div>';
            $row[] = $temp;
            if (count($row) == 4) {
                array_push($data, array_values($row));
                $row = [];
            }
        }
        if (!empty($row) && count($row) != 4) {
            for ($i = count($row); $i < 4; $i ++) {
                $row[] = '';
            }
        }
        empty($row) || array_push($data, array_values($row));
        //获取记录总数
        $total = SysAd::find()
            ->select('*')
            ->where(['del_flag' => 0,'parent_id' =>$id])
            ->count();
        $total = $total/5*4;
        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ];
        die(json_encode($data_source));


        return $this->renderPartial('ad_detail', ['id' => $id]);
    }

    /**
     * 新增广告
     */
    public function actionAdd_ad()
    {
        $id = Yii::$app->request->get('type');

        return $this->renderPartial('add_ad', ['id' => $id]);
    }
    /**
     * 编辑广告
     */
    public function actionUpdate_ad()
    {
        $id = Yii::$app->request->get('id');
        $temp = SysAd::find()
            ->select('*')
            ->where(['id' =>$id])
            ->asArray()
            ->one();
        return $this->renderPartial('update_ad', ['id' => $id,'temp'=>$temp]);
    }

    public function actionAd_coverage()
    {

        return $this->renderAjax('ad_coverage');
    }

    /**
     * 图片仓库
     */
    public function actionAd_img_stock()
    {
        return $this->renderAjax('ad_img_stock');
    }

    /**
     * 素材库列表
     */
    public function actionAjax_ad_img_stock()
    {
        //获取页码与数据长度
        $offset = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = 2;//empty($_GET['length']) ? 10 : $_GET['length'];
        $redis6 = yii::$app->redis;
        $redis6->select(8);
        $key = "ossimage";

        $link = "http://xiaosongshu-images.oss-cn-shanghai.aliyuncs.com/";
        //查询出这个用户所投递的小区
        $list = $redis6->zrevrange($key, ($offset / 5) * 6, ($offset / 5) * 6 + $length * 6 - 1);
//        $list = $redis6->zrevrange($key, -(($offset / 5) * 6 + $length * 6 - 1),  -($offset / 5) * 6);
//        $list = $redis6->zrevrange($key, 0, -1);
        $total = $redis6->zcard($key);
        if ($offset == 0 && empty($list)) {
            $accessKeyId = "LTAI5OVoMFrYKUTh";
            $accessKeySecret = "Z5QI8SoEsV1OWKtNErji8Min4qGlb5";
            $accessKeyId = "LTAI4G3axJ37rNexBWoBJWXy";
            $accessKeySecret = "UBDOhoJEevUUycSQ6bTrXZQisiQyrL";
// Endpoint以杭州为例，其它Region请按实际情况填写。
            $endpoint = "http://oss-cn-shanghai.aliyuncs.com";
            $bucket= "xiaosongshu-images";
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $nextMarker = '';
            $list = [];
            $num = 0;
            while (true) {
                try {
                    $options = array(
                        'delimiter' => '',
                        'marker' => $nextMarker,
                    );
                    $listObjectInfo = $ossClient->listObjects($bucket, $options);
                } catch (OssException $e) {
//                    printf(__FUNCTION__ . ": FAILED\n");
//                    printf($e->getMessage() . "\n");
//                    return;
                    break;
                }
                // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表。
                $nextMarker = $listObjectInfo->getNextMarker();
                $listObject = $listObjectInfo->getObjectList();
                if (!empty($listObject)) {
                    foreach ($listObject as $objectInfo) {
                        $num < $length*6 && $list[] = $objectInfo->getKey();
                        $num++;
                        $redis6->zincrby($key, $num, $objectInfo->getKey());
                    }
                }
                if ($listObjectInfo->getIsTruncated() !== "true") {
                    break;
                }
            }
            $total = $num;
        }
        $list = $redis6->zrevrange($key, ($offset / 5) * 6, ($offset / 5) * 6 + $length * 6 - 1);
        $total = $total % 6 > 0 ? ($total/6)*5 + 1 : ($total/6)*5;
        $data = [];
        $hang = [];
        $num = 0;
        empty($list) && $list = [];
        foreach ($list as $item) {
            $temp = "<div class=\"product\" >";
            $temp .= "<div class=\"product-top\">";
            $temp .= "<img onclick='chooseImage(this);' src=\"" . $link . $item . "\"alt=\"\">";
            $temp .= "</div>";
            $temp .= "</div>";
            $hang[] = $temp;
            $num++;
            if ($num == 6) {
                $data[] = $hang;
                $hang = [];
                $num = 0;
                continue;
            }
        }
        if ($num != 6 && $num > 0) {
            for (; $num <= 6; $num++) {
                $hang[] = "";
            }
            $data[] = $hang;
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
     * 添加图片到素材库
     */
    public function actionAdd_picture_library()
    {
        $img_name = yii::$app->request->get('img_name');
        if (empty($img_name)) {
            return json_encode(['res' => false, 'msg' => '参数错误']);
        }
        $redis6 = yii::$app->redis;
        $redis6->select(8);
        $key = "ossimage";
        $total = $redis6->zcard($key);
        $redis6->zincrby($key, $total + 1, $img_name);
        return json_encode(['res' => true, 'msg' => '添加成功']);
    }

    /**
     * 添加广告
     */
    public function actionAjax_add_ad()
    {
        $ad_name = yii::$app->request->get('ad_name');//广告名称
        $id = yii::$app->request->get('id');//广告类型
        $jump_link = yii::$app->request->get('jump_link');//广告跳转url
        $img_urls = yii::$app->request->get('img_urls');//广告图片url
        $ad_resolution_ratio = yii::$app->request->get('ad_resolution_ratio');//广告图片建议分辨率
        $top_sort = yii::$app->request->get('top_sort');//广告图片排列顺序、
        $ad_time = yii::$app->request->get('ad_time');//轮播停留时间

        $space = SysAdSpace::find()
            ->select('*')
            ->where(['del_flag' => 0, 'id' => $id])
            ->asArray()->one();
        if (empty($space)) {
            return json_encode(['res' => false, 'msg' => '参数错误']);
        }
        $insert_ad = new SysAd();
        $insert_ad->parent_id = $id;//父id
        $insert_ad->name = $ad_name;//广告名称
        $insert_ad->img = $img_urls;//图片url
        $insert_ad->resolution_ratio = $ad_resolution_ratio;//图片分辨率
        $insert_ad->jump_link = $jump_link;//广告跳转url  (a > b) ? a : b
        $insert_ad->stay_time = (($ad_time<=0)?30:$ad_time);//停留时间
        $insert_ad->top_sort = intval($top_sort);//排列顺序
        $insert_ad->time_start = '00:00:00';//广告开始时间
        $insert_ad->time_end = '00:00:00';//广告结束时间

        if ($insert_ad->save()) {
            return json_encode(['res' => true, 'msg' => '添加成功']);
        }
        return json_encode(['res' => false, 'msg' => json_encode($insert_ad->errors)]);
    }

    /**
     * 编辑广告
     */
    public function actionAjax_update_ad()
    {
        $ad_name = yii::$app->request->get('ad_name');//广告名称
        $id = yii::$app->request->get('id');//广告id
        //$jump_link = yii::$app->request->get('jump_link');//广告跳转url
        $img_urls = yii::$app->request->get('img_urls');//广告图片url
        $top_sort = yii::$app->request->get('top_sort');//广告图片排列顺序、
        $ad_time = yii::$app->request->get('ad_time');//轮播停留时间
        $del = SysAd::updateAll(
            ['name'=>$ad_name, 'img'=>$img_urls,'stay_time'=>$ad_time,'top_sort'=>$top_sort,'update_time'=>date('Y-m-d H:i:s')],
            ['id'=>$id]);

        if ($del){
            die(json_encode(['res' => true, 'msg' => '修改成功']));
        }
        die(json_encode(['res' => false, 'msg' => '修改失败']));
    }


    public function actionAd_list()
    {

        return $this->render('ad_list');
    }
    public function actionAjax_ad_list()
    {
        //获取页码与数据长度
        $start = empty($_GET['start']) ? 0 : $_GET['start'];
        $length = empty($_GET['length']) ? 10 : $_GET['length'];

        $ad_list = SysAd::find()
            ->select('*')
            ->where(['del_flag' => 0])
            ->orderBy(['id' => SORT_ASC])
            ->offset($start)
            ->limit($length)
            ->asArray()
            ->all();
        //  `img` '图片url',
        //  `name`  '广告位名称',
        //  `title`  '广告标题',
        //  `resolution_ratio`  '图片分辨率',
        //  `jump_type`   '跳转类型默认0不跳转，1跳转',
        //  `jump_link`  '跳转链接',
        //  `stay_time`   '停留时间',
        //  `top`   '是否置顶',
        //  `hide`   '显示或隐藏',
        //  `offline_time`  '下线时间',
        $space_list = SysAdSpace::find()
            ->select('*')
            ->where(['del_flag' => 0])
            ->asArray()->all();
        $spaces = array_column($space_list,NULL,'id');
        foreach ($ad_list as $item) {
            $parent_id = $item['parent_id'];
            $type = $spaces[$parent_id]['type'];//广告类型
            $id = $item['id'];                  //广告编号
            $img = $item['img'];                //配图
            $agent_id = $item['agent_id'];      //广告覆盖范围


            //                                <th>广告覆盖范围</th>
            //                                <th>覆盖机器数量（实时）</th>
            //                                <th>标题</th>
            //                                <th>跳转类型</th>
            //                                <th>排序</th>
            //                                <th>显示状态</th>
            //                                <th>自动下线时间</th>
            //                                <th>修改时间</th>
            //                                <th>修改账号</th>
            //                                <th>操作</th>
        }
        //组装数据
        $data_source = [
            'draw' => $_GET['draw'],
            //'recordsTotal' => $total,
           // 'recordsFiltered' => $total,
           // 'data' => $data
        ];
        die(json_encode($data_source));
    }


    /**
     * 添加图片到素材库
     */
    public function actionSet_ad()
    {
        $id = yii::$app->request->get('id');
        $list = SysMachineAdSpace::find()
            ->select('position_village_id')
            ->where(['del_flag' => 0,'ad_space_id'=>$id])
            ->asArray()->all();
        $p_ids = array_column($list,'position_village_id');
        $lists = PositionVillage::find()
               ->select('village_name')
               ->andWhere(['in', 'p_id', $p_ids])
               ->asArray()->all();
        //$names = array_column($lists,'village_name');
        //$ids = explode(",",$ids);
        //$id = Yii::$app->request->get('id');
        $mechine_info_list = SrRecyclingMachine::find()
            ->select('community_name,street_name,divece_code')
            ->where(['del_flag' => 0])
            ->groupBy(['street_name', 'divece_code', 'community_name'])
            ->asArray()->all();
        $code_list = array_unique(array_column($mechine_info_list, 'divece_code'));
        return $this->renderPartial('apk_to_machine',['id' => $id,'info' => $mechine_info_list,'lists' => $lists, 'code' => $code_list]);
    }

    public function actionApk_to_machine()
    {
        $id = Yii::$app->request->post('id');
        $community = Yii::$app->request->post('community');

        if (empty($id) || !is_numeric($id)) {
            die(json_encode(['res' => false, 'msg' => 'id错误']));
        }
        if (empty($community) || !is_array($community)) {
            die(json_encode(['res' => false, 'msg' => '小区错误']));
        }
        //通过id查询类型 设置到指定广告位置  '广告类型：0：用户端小程序，1:回收机待机轮播，2:回收机底部轮播',
        $ad_code = SysAdSpace::find()
            ->select('code')
            ->where(['id' => $id])
            ->asArray()->one()['code'];

        $where = '';
        $where .= " `community_name` IN ('" . implode("','", $community) . "')";

        //小区id
        $cid = SrRecyclingMachine::find()
            ->select('id,position_village_id')
            ->asArray()
            ->where($where)
            ->all();
        $cid = array_unique(array_column($cid,'position_village_id'));
        $time = date('Y-m-d H:i:s');
        $data = [];
        foreach ($cid as $key=>$value){
            $data[$key]['ad_space_id'] = $id;
            $data[$key]['position_village_id'] = $value;
            $data[$key]['code'] = $ad_code;
            $data[$key]['create_time'] = $time;
            $data[$key]['del_flag'] = 0;
        }
        SysMachineAdSpace::deleteAll(['ad_space_id'=>$id]);

        $key = ['ad_space_id','position_village_id', 'code', 'create_time', 'del_flag'];
        $res = Yii::$app->db->createCommand()->batchInsert(SysMachineAdSpace::tableName(), $key, $data)->execute();

        die(json_encode(['res' => true, 'msg' => '变更机器台数' . $res.'台']));
    }

    /**
 * 删除
 */
    public function actionDel_space()
    {
        $id = Yii::$app->request->post('id');
        $del = SysAdSpace::updateAll(['del_flag'=>1,'update_time'=>date('Y-m-d H:i:s')],['id'=>$id]);
        $del = SysMachineAdSpace::updateAll(['del_flag'=>1],['ad_space_id'=>$id]);


        if ($del){
            die(json_encode(['res' => true, 'msg' => '删除成功']));
        }
        die(json_encode(['res' => false, 'msg' => '删除失败']));
    }

    /**
     * 编辑广告
     */
    public function actionUpdate_space()
    {
        $id = Yii::$app->request->post('id');

        $del = SysAdSpace::updateAll(['del_flag'=>1,'update_time'=>date('Y-m-d H:i:s')],['id'=>$id]);

        if ($del){
            die(json_encode(['res' => true, 'msg' => '修改成功']));
        }
        die(json_encode(['res' => false, 'msg' => '修改失败']));
    }

    /**
     * 删除子表
     */
    public function actionDel_space_ad()
    {
        $id = Yii::$app->request->post('id');

        $del = SysAd::updateAll(['del_flag'=>1,'update_time'=>date('Y-m-d H:i:s')],['id'=>$id]);

        if ($del){
            die(json_encode(['res' => true, 'msg' => '删除成功']));
        }
        die(json_encode(['res' => false, 'msg' => '删除失败']));
    }

}