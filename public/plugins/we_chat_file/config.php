<?php
// +----------------------------------------------------------------------
// | 添加微信支付辅助文件
// +----------------------------------------------------------------------
// | Author: Lsq <2545644408@qq.com>
// +----------------------------------------------------------------------

return array(
    'mch_id' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'mch_id', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '商户号(支付必填)' //表单的帮助提示
    ),
    'mch_key' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'mch_key', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '商户后台支付密匙(支付必填)' //表单的帮助提示
    ),
    'ssl_p12' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'ssl_p12', // 表单的label标题
        'type' => 'file',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '配置商户支付双向证书目录 （p12 | key,cert 二选一，php必须使用key,cert类型，两者都配置时p12优先）' //表单的帮助提示
    ),
    'ssl_key' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'ssl_key', // 表单的label标题
        'type' => 'file',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '支付双向证书(选填)' //表单的帮助提示
    ),
    'ssl_cer' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'ssl_cer', // 表单的label标题
        'type' => 'file',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '支付双向证书(选填)' //表单的帮助提示
    ),
    'cache_path' => array(// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'cache_path', // 表单的label标题
        'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip' => '配置缓存目录，需要拥有写权限' //表单的帮助提示
    ),
);
