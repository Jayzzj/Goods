<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute' => 'admin/login',//默认访问主页
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
//        'user' => [
//            'identityClass' => 'backend\models\Login',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
//        ],
        //验证登录的主键
        'user' => [
            'identityClass' => 'backend\models\Admin', //实现接口的类路径
            'enableAutoLogin' => true,//默认记录登录信息
            'loginUrl' => ['admin/login'],//没登陆情况下的跳转页面
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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

//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => [
//            ],
//        ],
        //美化路由隐藏index.php
              'urlManager'=>[
            'class' => 'yii\web\UrlManager',	//指定实现类
            'enablePrettyUrl' => true,	// 开启URL美化
            'showScriptName' => false, // 是否显示index.php
//            'suffix' => '.html',	// 伪静态后缀
            'rules'=>[
                  //自定义路由规则
            ],
        ],


],
    'params' => $params,
];
