<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_poster".
 *
 * @property string $id 主键id
 * @property string $poster_title 海报标题
 * @property string $poster_content 海报内容
 * @property string $poster_url 海报地址
 * @property string $poster_img_url 海报图片地址
 * @property string $create_date 创建时间
 * @property string $update_date 修改时间
 * @property int $poster_type 广告宣传 类型1针对用户 2针对回收员
 * @property string $del_flag
 * @property int $poster_status 默认1 跳转html,2跳转小程序页面
 */
class SrPoster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_poster';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poster_title', 'poster_img_url', 'create_date', 'del_flag'], 'required'],
            [['poster_content'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['poster_type', 'poster_status'], 'integer'],
            [['poster_title', 'poster_url'], 'string', 'max' => 255],
            [['poster_img_url'], 'string', 'max' => 1000],
            [['del_flag'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poster_title' => 'Poster Title',
            'poster_content' => 'Poster Content',
            'poster_url' => 'Poster Url',
            'poster_img_url' => 'Poster Img Url',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'poster_type' => 'Poster Type',
            'del_flag' => 'Del Flag',
            'poster_status' => 'Poster Status',
        ];
    }
}
