<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'index/index',//默认访问主页
    'layout'=>false,
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => \frontend\models\Member::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        //美化路由
       'urlManager'=>[
            'class' => 'yii\web\UrlManager',	//指定实现类
            'enablePrettyUrl' => true,	// 开启URL美化
            'showScriptName' => false, // 是否显示index.php
//            'suffix' => '.html',	// 伪静态后缀
            'rules'=>[
                  //自定义路由规则
            ],
        ],

        'sms'=>[//配置阿里大于
            'class'=>\frontend\components\Sms::className(),
            'ak'=>'LTAIsvBW8jYNnIq1',//Ak
            'sk'=>'4NpObxLikf7vS0KUWdYYmnV2jgMHWx',//SK
            'sign'=>'周先生的店铺',//短信签名
            'template'=>'SMS_87150024'//模板id
        ]

],
    'params' => $params,
];
