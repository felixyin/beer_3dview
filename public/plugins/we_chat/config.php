<?php

// +----------------------------------------------------------------------
// | 添加微信公众号参数菜单
// +----------------------------------------------------------------------
// | Author: Lsq <2545644408@qq.com>
// +----------------------------------------------------------------------

return array(
    'token' => array(
        'title' => 'token',
        'type' => 'text',
        'value' => '',
        'tip' => '微信公众号及小程序的Token,链接凭证(统一设置TOKEN)'

    ),
    'appid' => array(
        'title' => 'appid',
        'type' => 'text',
        'value' => '',
        'tip' => '微信公众号的AppID,链接凭证'
    ),
    'appsecret' => array(
        'title' => 'appsecret',
        'type' => 'text',
        'value' => '',
        'tip' => '微信公众号的AppSecret,链接凭证'
    ),
    'encodingaeskey' => array(
        'title' => 'encodingaeskey',
        'type' => 'text',
        'value' => '',
        'tip' => '微信公众号的EncodingAESKey,链接凭证'
    ),
    'cache_path' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'cache_path', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '配置缓存目录，需要拥有写权限' //表单的帮助提示
    ),
    'open_platform' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'open_platform', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '绑定的微信开放平台帐号' //表单的帮助提示
    ),
);
