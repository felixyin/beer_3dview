<?php
// +----------------------------------------------------------------------
// | Alidayu [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 Tangchao All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tangchao <79300975@qq.com>
// +----------------------------------------------------------------------
return array (
	'AppKey' => array (
		'title' => 'Access Key ID',
		'type' => 'text',
		'value' => '',
		'tip' => '阿里云短信接口'
	),
    'AppSecret' => array (
        'title' => 'Access Key Secret',
        'type' => 'text',
        'value' => '',
        'tip' => '秘钥管理页面：https://ak-console.aliyun.com/?spm=5176.doc55451.2.3.3h2Kej#/accesskey'
    ),
    'autograph' => array (
        'title' => '签名',
        'type' => 'text',
        'value' => '',
        'tip' => '测试请输入“阿里云短信测试专用”'
    ),
    'Template' => array (
        'title' => '模板ID',
        'type' => 'text',
        'value' => '',
        'tip' => '默认格式为：SMS_92100059。如需添加，请自行申请。格式：验证码${code}，您正在注册成为新用户，感谢您的支持！' //表单的帮助提示
    ),
    'expire_minute' => array (
        'title' => '有效期',
        'type' => 'text',
        'value' => '30',
        'tip' => '短信验证码过期时间，单位分钟'
    ),
);
					