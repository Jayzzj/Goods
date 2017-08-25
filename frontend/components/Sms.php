<?php


namespace frontend\components;


use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use yii\base\Component;

class Sms extends Component
{
    //定义属性
    public $ak;
    public $sk;
    public $sign;//短信签名
    public $template;//短信模板id

    //要发送的手机号
    public $number;
    //替换变量
    public $params = [];//${code}    [code=>1234,name=>'222']

    private $acsClient;

    public function init()
    {
        // 加载区域结点配置
        Config::load();
        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        //初始化用户profile实例
        $profile = DefaultProfile::getProfile($region, $this->ak,$this->sk);

        //增加服务节点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        //初始化acsClient发起请求
        $this->acsClient = new DefaultAcsClient($profile);

        parent::init();
    }

    public function send()
    {
        //初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //必填,短信接收号码
        $request->setPhoneNumbers($this->number);

        //必填,设置签名名称
        $request->setSignName($this->sign);

        //必填,设置模板code
        $request->setTemplateCode($this->template);

        // 可选，设置模板参数
        if($this->params) {
            $request->setTemplateParam(json_encode($this->params));
        }

        //发送请求访问
        $acsResponse = $this->acsClient->getAcsResponse($request);

        //打印输出结果
        var_dump($acsResponse);

//        return $acsResponse;


    }

    public function setNumber($value){
        $this->number = $value;
        return $this;
    }

    public function setParams(Array $data){
        $this->params = $data;
        return $this;
    }

}