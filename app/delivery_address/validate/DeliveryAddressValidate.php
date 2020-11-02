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
namespace app\delivery_address\validate;

use think\Validate;
use think\Db;

class DeliveryAddressValidate extends Validate
{
    protected $rule = [
        'title'         => 'require',
        'detail'        => 'require',
        'processAll'    => 'require|number',
        'price'         => 'require|number',
        'name'          => 'require',
        'mobile'        => 'require|number|between:7,12',  //电话长度
    ];

    protected $message = [
        'processAll.require'    => '请填写接单次数',
        'price.require'         => '请填写单价',
        'mobile.require'        => '请填写联系电话',
        'name.require'          => '请填写联系人',
        'title.require'         => '请填写接标题',
        'detail.require'        => '请填写需求描述',
        'processAll.number'     => '接单次数请填写数值',
        'price.number'          => '单价请填写数值',
        'mobile.number'         => '电话请填写数值',
        'mobile.between'        => '请填写正确的电话',
    ];

    protected $scene = [
//        'add'  => ['name'],
//        'edit' => ['sex'=>'require|checkAb:sex|gg'],
    ];

}