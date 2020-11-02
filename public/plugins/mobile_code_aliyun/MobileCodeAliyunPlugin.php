<?php
// +----------------------------------------------------------------------
// | Alidayu [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 Tangchao All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tangchao <79300975@qq.com>
// +----------------------------------------------------------------------
namespace plugins\mobile_code_aliyun;
use cmf\lib\Plugin;
use plugins\mobile_code_aliyun\model\PluginMobileCodeAliyunModel;
use think\Db;

class MobileCodeAliyunPlugin extends Plugin
{

    public $info = [
        'name'        => 'MobileCodeAliyun',
        'title'       => '阿里云短信接口',
        'description' => '阿里云短信接口',
        'status'      => 1,
        'author'      => 'LSQ',
        'version'     => '1.0',
    ];

    public $has_admin = 0;

    public function install()
    {
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '手机验证码',
            'hook' => 'send_mobile_verification_code',
            'app' => 'cmf',
            'description' => '手机验证码'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }
        return true;
    }

    public function uninstall()
    {
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '手机验证码',
            'hook' => 'send_mobile_verification_code',
            'app' => 'cmf',
            'description' => '手机验证码'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        return true;
    }

    // 实现的 send_mobile_verification_code 钩子方法,获取手机短信验证码
    public function sendMobileVerificationCode($param)
    {

        $mobile        = $param['mobile'];
        $config        = $this->getConfig();
        $delete = new PluginMobileCodeAliyunModel();
        $resp = $delete->Sendsms($mobile,$config,$param);

        $result = [
            'error'     => 0,
            'message' => ''
        ];

        if($resp->Code != 'OK'){
            $result['message'] = $resp->Message;
        }
        return $result;
    }
}

