<?php


namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends Controller
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
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //上传图片到七牛云
                    $config = [
                        'accessKey'=>'uY6bxXUiUS5_trFeA2fOw6HY4jCQfsoyaAtwrecL',
                        'secretKey'=>'_udkZ2s0_4B0igrT7fhejNONqNgOM3bKT-wYALCp',
                        'domain'=>'http://ouk9jr68o.bkt.clouddn.com/',//图片地址
                        'bucket'=>'yii2shop',//存储空间名称
                        'area'=>Qiniu::AREA_HUADONG//区域
                    ];



                    $qiniu = new Qiniu($config);
                    $key = $action->getWebUrl();//文件名
                    $file = $action->getSavePath();//
                    $qiniu->uploadFile($file,$key);//上传文件到七牛云
                    $url = $qiniu->getLink($key);//根据文件名获取文件绝对路径
                    $action->output['fileUrl'] = $url;//输出文件路径
                },
            ],
        ];
    }

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
                //$model->imgFile = UploadedFile::getInstance($model, 'imgFile');

                  //判定是否上传文件
                //if (!$model->imgFile == null) {
                    //拼接路径
                    //$fileName = "/upload/" . uniqid() . '.' . $model->imgFile->extension;

                    //保存上传文件
                   //if ( $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false)){
                      // $model->logo = $fileName;
                   //}//

                //}
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
        $model =Brand::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());

            //验证数据
            if ($model->validate()) {
                //获取文件对象
                //$model->imgFile = UploadedFile::getInstance($model, 'imgFile');

                //判定是否上传文件
                //if (!$model->imgFile == null) {
                //拼接路径
                //$fileName = "/upload/" . uniqid() . '.' . $model->imgFile->extension;

                //保存上传文件
                //if ( $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false)){
                // $model->logo = $fileName;
                //}//

                //}
                //保存数据
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success', "修改成功 ");
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
                'defaultPageSize' => 8,
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