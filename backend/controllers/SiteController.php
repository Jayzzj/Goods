<?php
namespace backend\controllers;

use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',

            ],
//            'captcha'=>[
//                'class'=>CaptchaAction::className(),
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//                //'backColor'=>0x000000,//背景颜色
//                //foreColor'=>,     //字体颜色
//                'minLength' => 2,//验证码最小位数
//                'maxLength' => 3, //验证码最多位数
//                'padding' => 0//间距
//
//            ]
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',//加载验证码
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//                //'backColor'=>0x000000,//背景颜色
//                //foreColor'=>,     //字体颜色
//                'minLength' => 2,//验证码最小位数
//                'maxLength' => 3, //验证码最多位数
//                'padding' => 0//间距
//            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        //var_dump(Yii::$app->request->post());exit;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
