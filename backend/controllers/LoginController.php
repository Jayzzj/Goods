<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/16
 * Time: 14:04
 */

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Admin;
use yii\web\Controller;

class LoginController extends Controller
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

    public function aaa()
    {
        //实例USER对象
        $userObj = \Yii::$app->user;
        $data = ['username'=>'zhangsan','password_hash'=>'zhan1gsan'];
//根据用户名查询相应的数据
        $userinfo = User::findOne(['username'=>$data['username']]);
//判定是否有该用户信息
        if(empty($userinfo)){
            echo "未注册";
            exit;
        }
//validatePassword是专门用来验证密码是否一致的第一个参数为明文密码第二个参数为加密后的密码
        if (!\Yii::$app->security->validatePassword($data['password_hash'],$userinfo->password_hash)){
            echo  '用户密码错误';
            exit;
        }
//绑定用户数据到session中
        if ($userObj->login($userinfo)){
            echo '登录成功';
        }else{
            echo '登录失败';
        }

    }
    public function actionIndex()
    {
        $model = new Admin();
        if (\Yii::$app->request->isPost){
            $users = \Yii::$app->request->post();
//            var_dump($users['Admin']['password_hash']);exit;
            //实例User对象
            $UserObj = \Yii::$app->user;
//            var_dump($UserObj);exit;

            //查询该条数据
            $userinfo = Admin::findOne(['username'=>$users['Admin']['username']]);
//            var_dump($userinfo->password_hash);exit;
            if (empty($userinfo)){
                \Yii::$app->session->setFlash('success','你还未注册');
                return $this->refresh();
            }

            if (!\Yii::$app->security->validatePassword($users['Admin']['password_hash'],$userinfo->password_hash)){
                \Yii::$app->session->setFlash('danger','用户密码错误');
                return $this->refresh();
            }

            if ($UserObj->login($userinfo)){
                echo '登录成';exit;
            }else{
                echo '失败';exit;
            }


        }


        return $this->render('index',['model'=>$model]);
    }

}