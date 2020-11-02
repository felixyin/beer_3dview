<?php
// +----------------------------------------------------------------------
// | Alidayu [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 Tangchao All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tangchao <79300975@qq.com>
// +----------------------------------------------------------------------
namespace plugins\mobile_code_aliyun\model;

require_once dirname(__DIR__) . '/Alidayu/vendor/autoload.php';
use think\Model;
use think\Db;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

Config::load();

class PluginMobileCodeAliyunModel extends Model
{
    public function __construct()
    {
        $config = Db::name("Plugin")->where('name',"MobileCodeAliyun")->value('config');
        $config = json_decode($config, true);
        $accessKeyId = $config['AppKey'];
        $accessKeySecret = $config['AppSecret'];
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        $region = "cn-hangzhou";
        $endPointName = "cn-hangzhou";
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
        $this->acsClient = new DefaultAcsClient($profile);
    }
    
    public function Sendsms($phoneNumbers, $config=[], $templateParam = null, $outId = null)
    {
        $templateCode = !empty($templateParam['Template']) ? $templateParam['Template'] : $config['Template'];

        $lastParam = [];
        foreach ($templateParam as $k=>$v){
            if(!in_array($k, ['mobile','Template','autograph'])){
                $lastParam[$k] = $v;
            }
        }

        $signName = !empty($templateParam['autograph']) ? $templateParam['autograph'] : $config['autograph'];
        $request = new SendSmsRequest();
        $request->setPhoneNumbers($phoneNumbers);
        $request->setSignName($signName);
        $request->setTemplateCode($templateCode);
        if($templateParam) $request->setTemplateParam(json_encode($lastParam));
        if($outId) $request->setOutId($outId);
        $acsResponse = $this->acsClient->getAcsResponse($request);

        return $acsResponse;
    }
}