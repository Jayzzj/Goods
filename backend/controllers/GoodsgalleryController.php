<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/14
 * Time: 22:05
 */

namespace backend\controllers;


use backend\models\GoodsGallery;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
class GoodsgalleryController extends Controller
{

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
$action->output['fileUrl'] = $action->getWebUrl();//输出图片地址
//$action->getFilename(); // "image/yyyymmddtimerand.jpg"
//$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"

//模仿CDN实现内容分发
//上传图片到七牛云
//                    $config = [
//                        'accessKey'=>'uY6bxXUiUS5_trFeA2fOw6HY4jCQfsoyaAtwrecL',
//                        'secretKey'=>'_udkZ2s0_4B0igrT7fhejNONqNgOM3bKT-wYALCp',
//                        'domain'=>'http://ouk9jr68o.bkt.clouddn.com/',//图片地址
//                        'bucket'=>'yii2shop',//存储空间名称
//                        'area'=>Qiniu::AREA_HUADONG//区域
//                    ];
//
//
//
//                    $qiniu = new Qiniu($config);
//                    $key = $action->getWebUrl();//文件名
//                    $file = $action->getSavePath();//
//                    $qiniu->uploadFile($file,$key);//上传文件到七牛云
//                    $url = $qiniu->getLink($key);//根据文件名获取文件绝对路径
//                    $action->output['fileUrl'] = $url;//输出文件路径
                },
            ],


        ];

    }
    public function actionImgadd($id)
    {
        //实例化相册对象
        $goods_gallery = new GoodsGallery();
        //把传过来的id复制给商品id上传到数据库
        $goods_gallery->goods_id = $id;
        //获取该商品对应的所有图片
        $goodsGallery = GoodsGallery::find()->andWhere(['=','goods_id',$id])->all();
        //分配数据
        return  $this->render('imgadd',['model'=>$goods_gallery,'goodsGallery'=>$goodsGallery]);


    }

    public function actionAdd()
    {
        //实例化商品相册对象
        $goods_gallery = new GoodsGallery();
        //保存Ajax传过来的路径到商品相册表
        $goods_gallery->path = \Yii::$app->request->get('path');
        //保存Ajax传过来的商品id
        $goods_gallery->goods_id =\Yii::$app->request->get('goods_id');
        //验证是否绑定成功
        if ($goods_gallery->validate()){
            //上传到数据库
            $goods_gallery->save();
        }
        //获取刚上传的商品id
        $id = $goods_gallery->id ;
        //根据商品id获取单条数据(注只能获取单条数据)
        $goodsGgallery = GoodsGallery::findOne($id);
        //返回给视图Ajax
        echo Json::encode($goodsGgallery);
    }

    public function actionDel($id)
    {
        //根据传过来的id删除对应的数并返回数据给视图Ajax
        echo GoodsGallery::deleteAll(['id'=>$id]);
    }


}