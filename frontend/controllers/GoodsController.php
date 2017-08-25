<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/23
 * Time: 0:50
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\web\Controller;

class GoodsController extends Controller
{
    public function actionGoods($id)
    {
        //获取商品信息
        $model = Goods::findOne($id);
        //获取所有一级商品分类信息
        $goodsCategorys = GoodsCategory::findAll(['parent_id'=>0]);
        return $this->render('goods',['model'=>$model,'goodsCategorys'=>$goodsCategorys]);
    }


}