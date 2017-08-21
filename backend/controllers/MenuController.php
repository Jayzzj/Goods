<?php


namespace backend\controllers;


use backend\models\Admin;
use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;

class MenuController extends Controller
{
    public function actionAdd()
    {
        $model =new  Menu();
         if ($model->load(\Yii::$app->request->post())&& $model->validate()){
             $model->save();
             \Yii::$app->session->setFlash('success','添加成功');
             return $this->redirect(['menu/index']);

         }
        return $this->render('add',['model'=>$model]);

    }

    public function actionIndex()
    {
        //获取所有菜单信息
        $models =Menu::find();
        //调用分页工具
        $pages = new Pagination(
            [
                'totalCount' =>$models->count(),
                'defaultPageSize' => '8'
            ]
        );
        $rst = $models->offset($pages->offset)
            ->limit($pages->PageSize)
            ->all();
        //显示页面
        return $this->render('index',['models'=>$rst,'pages'=>$pages]);
    }

    public function actionEdit($id)
    {
        $model =Menu::findOne($id);
        if ($model->load(\Yii::$app->request->post())&& $model->validate()){

            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);

        }
        return $this->render('add',['model'=>$model]);

    }

    public function actionDel($id)
    {
        if (Menu::find()->where(['parent_id'=>$id])->count()){
            echo 0;
        }else{
           echo Menu::findOne($id)->delete();
        }



    }

    public function actionPa()
    {

        var_dump( Menu::findAll(['parent_id'=>0]));exit;
    }
}