<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[//RBAC权限配置
            'class' => \yii\rbac\DbManager::className(),
        ]
    ],
];
