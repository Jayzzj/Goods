<?php


namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class GoodscategoryController extends Controller
{
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判定添加节点是父节点还子节点
            if ($model->parent_id){
                //获取该子节点的父节点对象
                $parent = GoodsCategory::findOne($model->parent_id);
                //判定当前父节点的深度
                if ($parent->depth >=2){

                    \Yii::$app->session->setFlash('error','只能添加3级分类');
                    return $this->render('add',['model'=>$model]);
                }else{
                    //添加子节点
                    $model->prependTo($parent);
                }

            }else{
                //添加顶级分类
                $model->makeRoot();

            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(Url::to(['index']));
        }

        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex()
    {
        //实例化模型
        $models = GoodsCategory::find();
        $page = new Pagination([
            'totalCount' => $models->count(),
            'defaultPageSize' => 8,
        ]);
        $rst = $models
            ->limit($page->pageSize)
            ->offset($page->offset)
            ->orderBy('tree,lft')//根据树id和左值显示子节点
            ->all();
        //显示页面
        return $this->render('index',['models'=>$rst,'page'=>$page]);

    }

    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne($id);
        //捕获异常
        try{
            if ($model->load(\Yii::$app->request->post()) && $model->validate()){
                if ($model->parent_id === $id){
                    //不能修改到自己下
                    throw new HttpException('403',"不能添加到当前节点或子节点下");
                }
                //判定添加节点是父节点还子节点
                if ($model->parent_id){
                    //获取该子节点的父节点对象
                    $parent = GoodsCategory::findOne($model->parent_id);
                    //判定当前父节点的深度
                    if ($parent->depth >=2){
                        //提示信息
                        \Yii::$app->session->setFlash('error','只能修改到2级分类下');
                        return $this->render('add',['model'=>$model]);
                    }
                    //添加子节点
                    $model->prependTo($parent);
                }else{
                    //添加顶级分类
                    //判定当前节点是否是根节点解决不修改也能添加成功
                    if ($model->getOldAttribute('parent_id')){

                        $model->makeRoot();
                    }else{

                        $model->save();
                    }


                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(Url::to(['index']));
            }
            //输出提示信息
        }catch (Exception $a){

            \Yii::$app->session->setFlash('error','不能添加到当前节点或子节点下');
            //刷新
            //return $this->refresh();
        }


        return $this->render('add',['model'=>$model]);
    }

    public function actionDel($id)
    {
        //判定该节点下是否有子节点
        if (GoodsCategory::find()->where(['parent_id'=>$id])->count()) {
            echo 0;
            \Yii::$app->session->setFlash('danger','该节点下有子节点不能删除');
            //exit;
        }else{
            //根据此id删除数据
            GoodsCategory::deleteAll(['id'=>$id]);
            //\Yii::$app->session->setFlash('success','删除成功');
            echo 1;
        }
//        $model = GoodsCategory::findOne($id);
//        //判定是否有子节点
//        $model->isLeaf();//是否是叶子 是否有子节点
//        $model->deleteWithChildren();//删除节点和子节点;
        return $this->redirect(Url::to(['index']));
    }

}