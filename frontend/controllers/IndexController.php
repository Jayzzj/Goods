<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/22
 * Time: 16:15
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

class IndexController extends Controller
{
    public function actionIndex()
    {
        //获取所有顶级分类菜单
        $model = GoodsCategory::findAll(['parent_id'=>0]);

        return $this->render('index',['models'=>$model]);
    }

    public function actionList($id)
    {
        //获取当前id对应的商品分类信息
        $data = GoodsCategory::findOne($id);
           if ($data ==null){
               throw new HttpException('404','商品分类未找到');
           }
        if ($data->depth==2){
            //获取所有3级分类信息
            $goods = Goods::find()->where(['goods_category_id'=>$id]);
            $pages = new Pagination(['totalCount' => $goods->count(), 'defaultPageSize' => '8']);

            //获取根据条件查询的数据
            $goods = $goods->offset($pages->offset)->limit($pages->limit)->all();
        }
        //2级分类 和1级分类
        if ($data->depth ==1 or $data->depth ==0) {
            //获取当前2级分类的所有子id
            $goodsT = GoodsCategory::findOne($id);
            //根据左值右值和树id获取数据
            $goodsid = GoodsCategory::find()->select('id')->andWhere(['=','depth',2])->andWhere(['=','tree',$goodsT->tree])->andWhere(['>', 'lft', $goodsT->lft])->andWhere(['<', 'rgt', $goodsT->rgt])->column();
//var_dump($goodsC);exit;
//            foreach ($goodsC as $v) {
//                //将id数组转换为一个一维索引数据
//                $goodsid[] = $v->id;
//            }
            //利用in方法分配查询数据
            $goods = Goods::find()->where(['in','goods_category_id',$goodsid]);
            //调用分页工具
            $pages = new Pagination(['totalCount' => $goods->count(), 'defaultPageSize' => '8']);
            //获取根据条件查询的数据
            $goods = $goods->offset($pages->offset)->limit($pages->limit)->all();
        }

        //1级分类
//        if ($data->depth ==0){
//            //获取当前2级分类的所有子id
//            $goodsT = GoodsCategory::findOne($id);
//            $goodsC = GoodsCategory::find()->select('id')->andWhere(['>', 'lft', $goodsT->lft])->andWhere(['=','tree',$goodsT->tree])->andWhere(['<', 'rgt', $goodsT->rgt])->all();
//
//            foreach ($goodsC as $v) {
//                $goodsid[] = $v->id;
//                $goods = Goods::find()->where(['in','goods_category_id',$goodsid]);
//            }
//            $pages = new Pagination(['totalCount' => $goods->count(), 'defaultPageSize' => '2']);
////            var_dump($pages);exit;
//            //获取根据条件查询的数据
//            $goods = $goods->offset($pages->offset)->limit($pages->limit)->all();
//        }
        //获取所有顶级分类
        $goodsCategorys = GoodsCategory::findAll(['parent_id'=>0]);

        //显示页面
        return $this->render('list',['goods'=>$goods,'goodsCategorys'=>$goodsCategorys,'pages'=>$pages]);
    }



}