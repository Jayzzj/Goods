<?php


namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;

class GoodsController extends Controller
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
                $action->output['fileUrl'] = $action->getWebUrl();//输出图片
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



//                    $qiniu = new Qiniu($config);
//                    $key = $action->getWebUrl();//文件名
//                    $file = $action->getSavePath();//
//                    $qiniu->uploadFile($file,$key);//上传文件到七牛云
//                    $url = $qiniu->getLink($key);//根据文件名获取文件绝对路径
//                    $action->output['fileUrl'] = $url;//输出文件路径
                },
            ],
               //Uedir的配置
                'upload' => [
                    'class' => 'kucha\ueditor\UEditorAction',
                ]

        ];

    }



    public function actionIndex()
    {
        //判定keyword是否存在
        $price_min = \Yii::$app->request->get("price_min")?\Yii::$app->request->get("price_min"):-1;
        $name = \Yii::$app->request->get("name")?\Yii::$app->request->get("name"):"";
        $sn = \Yii::$app->request->get("sn")?\Yii::$app->request->get("sn"):'';
        $price_max = \Yii::$app->request->get("price_max")?\Yii::$app->request->get("price_max"):99999999999;
       //获取所有未删除的商品
        $model = Goods::find()->andWhere(['=','status',1])->andWhere(['like','name',$name])->andWhere(['like','sn',$sn])->andWhere(['>=','shop_price',$price_min])->andWhere(['<=','shop_price',$price_max]);
        //调用分页工具
        $page = new Pagination(['totalCount' =>$model->count(), 'defaultPageSize' => '8']);
        //获取根据条件查询的数据
        $rst = $model->offset($page->offset)->limit($page->limit)->all();
        //分配数据
        return $this->render('index',['model'=>$rst,'page'=>$page]);
    }

    public function actionAdd()
    {
        //商品对象
        $model = new  Goods();
        //获取商品详情对象
        $goodsIntro =new GoodsIntro();
        //goods_day_count 商品每日添加数
        $goodsCount = new GoodsDayCount();

        //判定判定和验证是否成功
        if ($model->load(\Yii::$app->request->post() )&& $model->validate()&&$goodsIntro->load(\Yii::$app->request->post()) && $goodsIntro->validate()){
            $Category = GoodsCategory::findOne(['id'=>\Yii::$app->request->post()['Goods']['goods_category_id']]);
//            var_dump($Category['depth']);exit;
            if($Category['depth']<2){
               //$model->addError('goods_category_id','只能添加到3级分类下');
                \Yii::$app->session->setFlash('success','只能添加到3级分类下');
               return $this->refresh();
            }
//            var_dump(\Yii::$app->request->post());exit;
            $model->create_time = time();

            //根据当天时间获取当天GoodsDayCount的一个对象
            $GoodsCountOne = GoodsDayCount::find()->where(['day'=>date('Ymd')])->one();
            //判定该对象是否存在
            if ($GoodsCountOne !=null){
                //存在添加货号利用str_pad用零补充
                    //让当天消费记录+1
                $count1 = ($GoodsCountOne->count+1);
                //生成以0开始的5位货号
$sn=str_pad($count1,5,"0",STR_PAD_LEFT);
                //用date()函数拼接货号并给商品货号赋值
                $model->sn = date('Ymd').$sn;
                //让记录表的当天记录加1
                $GoodsCountOne->count = $GoodsCountOne->count+1;
                //保存数据
                $GoodsCountOne->save();
                //$model->save();

            }else{
                //否则获取记录表对象
                //生成当天的第一个货号
                $num =1;
                //添加货号用0补充
$sn=str_pad($num,5,"0",STR_PAD_LEFT);
                $model->sn =  date('Ymd').$sn;
                //给GoodsDayCount对象属性赋值
                $goodsCount->day = date('Ymd');
                $goodsCount->count =1;
                //保存数据
                $goodsCount->save();
            }
            $model->save();

                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();

            \Yii::$app->session->setFlash('success','添加成功');
//            var_dump($model->id);exit;
            return $this->redirect( \yii\helpers\Url::to(['goodsgallery/imgadd','id'=>$model->id]));
        }
        return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro]);
    }

    public function actionEdit($id)
    {
        $model =  Goods::findOne($id);
        $goodsIntro =GoodsIntro::findOne($id);
//        var_dump($goodsIntro);exit;
        //goods_day_count 商品每日添加数
        //判定判定和验证是否成功
        if ($model->load(\Yii::$app->request->post())&& $model->validate()){

            $model->save();
            if ($goodsIntro->load(\Yii::$app->request->post()) && $goodsIntro->validate()) {
                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();
            }
            \Yii::$app->session->setFlash('success','修改成功');

            return $this->redirect(['goods/index']);
        }

        return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro]);

    }

    public function actionDel($id)
    {
        //根据传过来的id查出对应的数据
        $model =  Goods::findOne($id);
        //更改状态值
        $model->status = 0;
        echo $model->save();
        //\Yii::$app->session->setFlash('success','删除成功');
        //return $this->redirect(['index']);
    }

    public function actionSee($id)
    {
        //获取商品详情
        $intro = GoodsIntro::find()->where(['goods_id'=>$id])->one();
        //获取所有图片信息
        $Imgs = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //分配数据到视图
        return $this->render('see',['intro'=>$intro,'imgs'=>$Imgs]);

    }







}