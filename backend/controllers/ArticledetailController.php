<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/11
 * Time: 23:38
 */

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticledetailController extends Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class' => RbacFilter::className(),
                'except' => ['login','logout','code','upload','s-upload']//排除不需要权限的方法
            ]
        ];
    }
    public function actionIndex($id)
    {
        $rows = ArticleDetail::findOne($id);
        return $this->render('index',['rows'=>$rows]);
    }

}