<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class ArticlecategoryController extends Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $rows = ArticleCategory::find()->where('status>=0');
        //分页
        $page = new Pagination([
            'totalCount' => $rows->count(),
            'defaultPageSize' => 5,
        ]);
        $rst = $rows
            ->limit($page->pageSize)
            ->offset($page->offset)
            ->all();
        return $this->render('index',['rows'=>$rst,'page'=>$page]);
    }

    public function actionAdd()
    {
        $acticleModel = new ArticleCategory();
        $request =  \Yii::$app->request;
        if ($request->isPost){
          //绑定数据
          $acticleModel->load($request->post());
          //验证数据
            if ($acticleModel->validate()){
                $acticleModel->save();
                \Yii::$app->session->setFlash('success',"文章添加成功");
               return $this->redirect(Url::to(['articlecategory/index']));
            }else{
                var_dump($acticleModel->getErrors());exit;
            }

        }
            return $this->render('add',['acticlemodel'=>$acticleModel]);

    }

    public function actionEdit($id)
    {
        $acticleModel = ArticleCategory::findone($id);
        $request =  \Yii::$app->request;
        if ($request->isPost){
            //绑定数据
            $acticleModel->load($request->post());
            //验证数据
            if ($acticleModel->validate()){
                $acticleModel->save();
                \Yii::$app->session->setFlash('success',"文章修改成功");
                return $this->redirect(Url::to(['articlecategory/index']));
            }else{
                var_dump($acticleModel->getErrors());exit;
            }

        }
        return $this->render('add',['acticlemodel'=>$acticleModel]);

    }

    public function actionDel($id)
    {
        //根据id获取对应的值
        $acticlemodel = ArticleCategory::findOne($id);
        //修改状态
        $acticlemodel->status = -1;
        //保存数据
        $acticlemodel->save();
        //跳转主页
        $this->redirect(Url::to(['articlecategory/index']));
    }


}