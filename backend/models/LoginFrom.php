<?php

namespace backend\models;




use yii\base\Model;

class LoginFrom extends Model
{
    public $username;
    public $password;
    public $code;
    public $rememberMe;

    public function rules()
    {
        return [
            [['username','password','code'],'required'],
            ['code','captcha','captchaAction'=>'admin/code',],
            ['rememberMe','safe']//不需要验证的字段
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code' => '验证码',
        ];
    }

}