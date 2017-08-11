<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/10
 * Time: 16:00
 */

namespace backend\controllers;


use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    public function actionAdd()
    {
        $model = new Brand();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());

            //验证数据
            if ($model->validate()) {
                //获取文件对象
                $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
//            var_dump($model->imgFile);exit;
                  //判定是否上传文件
                if (!$model->imgFile == null) {
                    //拼接路径
                    $fileName = "/upload/" . uniqid() . '.' . $model->imgFile->extension;
//                    echo 111;exit;
                    //保存上传文件
                   if ( $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false)){
                       $model->logo = $fileName;
                   }

                }
                //保存数据
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success', "添加成功 ");
                //添加成功跳转
               return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //1.显示添加页面
        return $this->render('add', ['model' => $model]);
    }


    public function actionEdit($id)
    {
        $model = Brand::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());
            //获取文件对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            //验证数据
            if ($model->validate()) {
                //判定是否上传文件
                if (!$model->imgFile == null) {
                    //拼接路径
                    $fileName = "/upload/" . uniqid() . '.' . $model->imgFile->extension;
                    //保存上传文件
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }
                //保存数据
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success', "修改成功 ");
                //添加成功跳转
              return  $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //1.显示添加页面
        return $this->render('add', ['model' => $model]);
    }

    public function actionDel($id)
    {
        //实例品牌对象
        $model = Brand::findOne($id);
        //跟新状态值
        $model->status = -1;
        //保存修改后的状态值
        $model->save();
        //跳转主页
        $this->redirect(Url::to(['brand/index']));
    }

    public function actionIndex()
    {
        //获取所有数据
        $rows = Brand::find()->where("status>=0");
        //实例化分页对象
        $page = new Pagination(
            [
                'totalCount' => $rows->count(),
                'defaultPageSize' => 5,
            ]
        );
        //查询数据
        $rst = $rows->offset($page->offset)
            ->limit($page->PageSize)
            ->all();
        //显示页面
        return $this->render('index', ['rows' => $rst, 'page' => $page]);
    }


}