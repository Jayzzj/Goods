<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\captcha\Captcha;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login_time
 * @property integer $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    //明文密码变量
    public $password;
    //定义验证码
    public $code;
    //记住密码
   public $rememberMe;
   //场景的定义
    const SCENARIO_ADD = 'add';
    //const SCENARIO_MODIFY='modify';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email','status'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_ip'], 'integer'],
            [['last_login_time'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password'], 'required','on'=>self::SCENARIO_ADD],//这个验证值对添加生效
            //只针对修改密码的验证
            [['password'], 'string'],//至少为有值
            [['username'], 'unique'],
            [['email'], 'unique'],//唯一性验证
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
            ['code','captcha','message'=>'验证码不对','on'=>'admin/login']//支持前段和后端验证码

        ];
    }
    //保存之前需要执行的方法
    public function beforeSave($insert)
    {
        //判定是否是添加$insert--->$this->isNewRecord
        if ($insert){
            //自动生成时间戳
            $this->created_at = time();
            //设置auth_key
            $this->auth_key = Yii::$app->security->generateRandomString();
        }else{//修改
            //跟新时间
            $this->updated_at = time();
        }
        //密码需要加密
        if ($this->password){
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        return parent::beforeSave($insert); // 必须调用父类的方法 //不然save()不会执行
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '记住密码',
            'password_hash' => '用户密码',
            'password_reset_token' => '重置密码的验证',
            'email' => '邮箱',
            'status' => '状态值',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'password'=>'密码',
            'code'=>'验证码'

        ];
    }

    /**
     * @param int|string $id
     * 根据主键id获取实例对象
     */


    public static function findIdentity($id)
    {
        return static::findOne($id);
// TODO: Implement findIdentity() method.
    }

    /**
     * @param mixed $token
     * @param null $type
     * 获取token登录时的用户实例
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
// TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @return mixed
     * 获取确定对象的id
     */
    public function getId()
    {
        return $this->id;
// TODO: Implement getId() method.
    }

    /**
     * @return mixed
     * 获取自动登录的authkey
     */
    public function getAuthKey()
    {
        return $this->auth_key;
// TODO: Implement getAuthKey() method.
    }

    /**
     * @param string $authKey
     * @return bool
     * 验证自动登录的authkey
     */
    public function validateAuthKey($authKey)
    {
        return $authKey === $this->getAuthKey();
// TODO: Implement validateAuthKey() method.
    }

}
