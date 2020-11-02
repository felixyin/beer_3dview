<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: LSQ < 2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\platform\validate;

use think\Validate;
use think\Db;

class PlatformValidate extends Validate
{
    protected $rule = [
        'num'                     => 'require|number',
        'title'                   => 'require',
        'price'                   => 'require|number',
        'thumbnail'               => 'require',
        'background_image'        => 'require',
    ];

    protected $message = [
        'num.require'             => '请填写接单基数(起订基数)',
        'num.number'              => '接单基数请填写数值',
        'title.require'           => '请填写接标题',
        'price.require'           => '请填写单价',
        'price.number'            => '请正确的填写单价',
        'thumbnail.number'        => '接单次数请填写数值',
        'background_image.number' => '接单次数请填写数值',

    ];

    protected $scene = [
//        'add'  => ['name'],
//        'edit' => ['sex'=>'require|checkAb:sex|gg'],
    ];

}