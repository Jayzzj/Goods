<?php


namespace frontend\controllers;


use frontend\models\Cart;
use frontend\models\LoginFrom;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;


class UserController extends Controller
{
    public function actionRegister()
    {
        //实例化模型
        $model = new Member();
        //判定
        if ($model->load(\Yii::$app->request->post(),'')){
           //调用模型添加数据
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $rediscode = $redis->get('sms_'.\Yii::$app->request->post('tel'));

            if ($rediscode == \Yii::$app->request->post('captcha')){
                $model->save(false);
                return $this->redirect(['login']);
            }else{
                echo '手机验证码出错';
                exit;
            }

        }

        return $this->render('register',['model'=>$model]);
    }

    public function actionLogin()
    {
        $model = new LoginFrom();

      if ($model->load(\Yii::$app->request->post(),'') && $model->validate()){         //实例user对象
          $user = \Yii::$app->user;
          //获取用户数据
            $userInfo = Member::findOne(['username'=>$model->username]);
          //判定是否有数据
            if ($userInfo){
                //判定密码是否正确
                if (\Yii::$app->security->validatePassword($model->password,$userInfo->password_hash)){
                    //判定是否勾选记住密码
                    $time = $model->rememberMe?7*24*3600:0;
                    //记住用户信息
                    if ($user->login($userInfo,$time)){
                        //记录最后登录信息及ip
                        $userInfo->last_login_time = time();
                        $userInfo->last_login_ip = ip2long(\Yii::$app->request->userIP);
                        //保存数据
                        $userInfo->save();
//                             var_dump($userInfo->id);exit;
                        //获取cookie中的购物车信息添加到数据库中
                        $cookies = \Yii::$app->request->cookies;
                        //判定cookie中是否有购物车商品数据
                        if ($cookies->getValue('carts')){
                            $cookies = \Yii::$app->request->cookies;
                            $carts = unserialize($cookies->getValue('carts'));
                            $cart1 = new Cart();
                            foreach($carts as $k=>$v){
                                //根据商品id判定当前商品是否存在
                                $cart = Cart::find()->where(['goods_id'=>$k])->one();                            //存在的情况只添加数量
                                if ($cart){
                                    $cart->amount +=$v;
                                    $cart->member_id = $userInfo->id;
                                    $cart->save();
                                }else{
                                    //否则添加一条新数据
                                    $cart1->goods_id = $k;
                                    $cart1->amount =$v;
                                    $cart1->member_id = $userInfo->id;
                                    $cart1->save();
                                }

                            }
                            //清除cookie信息
                            \Yii::$app->response->cookies->remove('carts');
                        }

                        //跳转主页
                        return $this->redirect(['index/index']);
                    }
                }else{
                    var_dump($model->getErrors());
                }
            }else{
                echo '没有此用户';
            }
        }else{
;
      }
        return $this->render('login');
    }

    public function actionLogout()
    {
        if(\Yii::$app->user->logout()){
            return $this->redirect(['index/index']);
        }

    }

    //验证唯一的用户名
    public function actionValidateusername($username)
    {
        //实例化模型
        $model = new Member();
        $model->username = $username;
        //需要验证的字段
        $model->validate('username');
        //判定是否验证有错
        if ($model->hasErrors('username')){
            //返回数据
            return Json::encode($model->getFirstError('username'));
        }
        return Json::encode(true);
    }
    public function actionValidatecode($code)
    {
        //实例化模型
        $model = new Member();
        $model->code = $code;
        //需要验证的字段
        $model->validate('code');
        //判定是否验证有错
        if ($model->hasErrors('code')){
            //返回数据
            return Json::encode($model->getFirstError('code'));
        }
        return Json::encode(true);
    }

    public function actionValidateemail($email)
    {
        //实例化模型
        $model = new Member();
        $model->email = $email;
        //需要验证的字段
        $model->validate('email');
        //判定是否验证有错
        if ($model->hasErrors('email')){
            //返回数据
            return Json::encode($model->getFirstError('email'));
        }
        return Json::encode(true);
    }
    public function actionValidatetel($tel)
    {
        //实例化模型
        $model = new Member();
        $model->tel = $tel;
        //需要验证的字段
        $model->validate('tel');
        //判定是否验证有错
        if ($model->hasErrors('tel')){
            //返回数据
            return Json::encode($model->getFirstError('tel'));
        }
        return Json::encode(true);
    }

    public function actionSms($tel)
    {
        //生成随机数
        $code = rand(1000,9999);
        //发送验证码
        \Yii::$app->sms->setParams(['smscode'=>$code])->setNumber($tel)->send();
        //实例化redis对象
        $redis = new \Redis();
        //连接redis
        $redis->connect('127.0.0.1');
        //保存数据到redis
        $redis->set('sms_'.$tel,$code);
        echo '成功';
    }

//    public function actionChecksms($tel,$code)
//    {
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1');
//        $rediscode = $redis->get('sms_'.$tel);
//
//        if ($rediscode == $code){
//            echo 1;
//        }else{
//            echo 0;
//        }
//
//
//    }






}