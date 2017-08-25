<?php

namespace backend\models;

use frontend\models\Cart;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id','logo','market_price','shop_price','brand_id', 'stock', 'is_on_sale', 'sort'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '商品货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '在售/下架',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }
    //获取品牌方法
    public static function getBrand()
    {
        return Brand::find()->where('status !=-1')->all();
    }

    public function getGoodscategory()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function getBrandcategory()
    {
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    //获取商品信息
    public function getGoodsgallery()
    {
        //建立1dui多关系
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }

    public function getIntro()
    {
        //建立1对1关系
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }


    public function getCart()
    {
        return $this->hasOne(Cart::className(),['goods_id'=>'id']);
    }
}
