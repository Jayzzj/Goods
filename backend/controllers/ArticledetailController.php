<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/11
 * Time: 23:38
 */

namespace backend\controllers;


use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticledetailController extends Controller
{
    public function actionIndex($id)
    {
        $rows = ArticleDetail::findOne($id);
        return $this->render('index',['rows'=>$rows]);
    }

}