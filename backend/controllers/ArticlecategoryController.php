<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class ArticlecategoryController extends Controller
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
    public function actionIndex()
    {
        //获取所有数据
        $rows = ArticleCategory::find()->where('status>=0');
        //分页
        $page = new Pagination([
            'totalCount' => $rows->count(),
            'defaultPageSize' => 8,
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
                //保存数据
                $acticleModel->save();
                //显示提示信息
                \Yii::$app->session->setFlash('success',"添加成功");
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
                //上传数据
                $acticleModel->save();
                //提示信息
                \Yii::$app->session->setFlash('success',"修改成功");
                //跳转主页
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
        echo $acticlemodel->save();
        //跳转主页
       // $this->redirect(Url::to(['articlecategory/index']));
    }


}