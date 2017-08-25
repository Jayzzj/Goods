<?php


namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\LoginFrom;
use backend\models\ModifyFrom;
use backend\models\RoleFrom;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\web\User;

class AdminController extends Controller
{
//    public function behaviors()
//    {
//        return [
//            //基于存取的权限控制器主要是要配置此过滤器
//            'access' => [
//                'class' => 'yii\filters\AccessControl',
//                'rules' => [
//                    [
//                        'allow' => 'true',     //允许请求
//                        'actions' => ['logout','index','add','edit','del','modify'],        //允许请求的方法
//                        'roles' => ['@']        //允许以登陆状态请求
//                    ],
//                    //bu登录后允许操作的方法
//                    [
//                        'allow' => 'true',     //允许请求
//                        'actions' => [ 'login','code'],        //允许请求的方法
//                        'roles' => ['?']        //允许以非登陆状态请求
//                    ]
//                ]
//            ]
//        ]; // TODO: Change the autogenerated stub
//    }



     public function behaviors()
     {
         return [
             'rbac'=>[
                 'class' => RbacFilter::className(),
                 'except' => ['login','logout','code','upload']//排除不需要权限的方法
             ]
         ];
     }

    public function actions()
    {
        return [
            //验证码配置
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',//加载验证码
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //'backColor'=>0x000000,//背景颜色
                'foreColor'=>0x000000,     //字体颜色
                'minLength' => 2,//验证码最小位数
                'maxLength' => 3, //验证码最多位数
                'padding' => 0//间距
            ]

        ];
    }

    public function actionIndex()
    {
        $model = Admin::find();
        $pages = new Pagination(['totalCount' => $model->count(), 'defaultPageSize' => '8']);
        //获取根据条件查询的数据
        $rst = $model->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', ['model' => $rst, 'pages' => $pages]);
    }

    public function actionAdd()
    {
        $model = new  Admin();
        //实例角色对象
        $roleFrom = new RoleFrom();
        $roleFrom->scenario = $roleFrom::SCENARIO_ADD;
        //设置场景
        $model->scenario = Admin::SCENARIO_ADD;
        //判定
        if (\Yii::$app->request->isPost) {

            //绑定数据
            $model->load(\Yii::$app->request->post());
            //绑定角色属性
            //验证绑定
            if ($model->validate()) {
                //保存数据
                $model->save();
                //获取添加后的用户id
                $adminid = $model->id;
                //调用RoleFrom给管理员分配角色
                $roleFrom->Adminroleadd(\Yii::$app->request->post()['RoleFrom']['parmission'],$adminid);
                //提醒信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                return $this->redirect(['index']);
            }
        }
        //获取所有角色对象
        $Roles = \Yii::$app->authManager->getRoles();


        return $this->render('add', ['model' => $model,'roleFrom'=>$roleFrom,'Roles'=>$Roles]);
    }


    public function actionEdit($id)
    {
        $model = Admin::findOne($id);
        $roleFrom = new RoleFrom();
        //实例authManager组件对象
        $authManager = \Yii::$app->authManager;
        //获取所有角色对象
        $Roles = $authManager->getRoles();
        //根据管理员id获取对应的所有角色
        $roleFrom->parmission = array_keys($authManager->getRolesByUser($id));
        //设定场景
        $roleFrom->scenario = RoleFrom::SCENARIO_EDIT;
        //判定是否post提交数据
        if (\Yii::$app->request->isPost) {
            //绑定数据
            $model->load(\Yii::$app->request->post());
            //验证绑定
            if ($model->validate()) {
                //保存数据
                $model->save();
                //调用模型方法给用户分配权限
                $roleFrom->Adminroleedit(\Yii::$app->request->post()['RoleFrom']['parmission'],$model->id);
                //提醒信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转页面
                return $this->redirect(['index']);
            }
        }

        return $this->render('add', ['model' => $model,'roleFrom'=>$roleFrom,'Roles'=>$Roles]);
    }

    public function actionDel($id)
    {

        //实例管理员对象
        echo Admin::findOne($id)->delete();
        //跳转主页
       // $this->redirect(['admin/index']);

    }

    public function actionLogin()
    {
        //实例表单模型对象
        $model = new LoginFrom();
        //判定是否是post方式并绑定
        if ($model->load(\Yii::$app->request->post())) {
            //并验证绑定是否成功
            if ($model->validate()) {
                //根据用户名及状态值查询该用户对应的数据
                $userInfo = Admin::findOne(['username' => $model->username, 'status' => 1]);
                //判定是否有该用户信息
                if ($userInfo){
                    //验证密码
                    if (\Yii::$app->security->validatePassword($model->password, $userInfo->password_hash)) {
                        //判定是否勾选记住密码及设置记住密码时间
                        $time = $model->rememberMe ? 7 * 24 * 3600 : 0;
                        //记住登录信息
                        if (\Yii::$app->user->login($userInfo,$time)) {
                            //记录用户最后登录时间和IP
                            $ip = \Yii::$app->request->userIP;
                            $userInfo->last_login_time = time();
                            $userInfo->last_login_ip = ip2long($ip);
                            $userInfo->save();
                            //提示信息
                            \Yii::$app->session->setFlash('success', '欢迎你~'.$userInfo['username']);
                            return $this->redirect(['index']);
                        }
                    } else {
                        //密码错误
                        \Yii::$app->session->setFlash('danger', '密码错误');
                    }
                } else {
                    //添加错误信息
                    $model->addError('username','用户名不存在');
                }
            }
        }
        //显示登录页面
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        //退出登录
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    public function actionModify()
    {
            //获取登录用户id
            $id = \Yii::$app->user->identity->id;
            //实例修改模型
            $model = Admin::findOne($id);
            $modify = new ModifyFrom();
            //获取
            //判定是否是post提交方式
            if (\Yii::$app->request->isPost){
                //获取post数据
                $data = \Yii::$app->request->post();
                 //验证输入密码是否和数据库一致
                if (\Yii::$app->security->validatePassword($data['ModifyFrom']['Oldpassword'], $model->password_hash)) {
                    //旧密码一致
                    //保存新密码
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($data['ModifyFrom']['Newpassword']);
                    //验证绑定
                    if ($model->validate()){
                        //保存数据
                        $model->save();
                        //退出登录
                        \Yii::$app->user->logout();
                        //提醒信息
                        \Yii::$app->session->setFlash('success','密码修改成功-请重新登录');
                        //跳转主页
                        return $this->redirect(['login']);
                    }
                }else{
                    \Yii::$app->session->setFlash('success','旧密码错误');
                }
            }
            //显示修改密码页面
            return $this->render('modify',['model'=>$modify]);
    }

}