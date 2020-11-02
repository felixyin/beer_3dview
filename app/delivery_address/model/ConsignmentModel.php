<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\delivery_address\model;

use think\Db;
use think\Model;

class ConsignmentModel extends Model
{
    public function getaddress()
    {
        return $this->belongsTo('app\delivery_address\model\DeliveryAddressModel','address_id','id');
    }
    public function getneedsdeal()
    {
        return $this->belongsTo('app\deal\model\NeedsDealModel','order_id','out_trade_no');
    }
    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs layui-btn-warm'>未发货</span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-normal'>待收货</span>",
            3=>"<span class='layui-btn layui-btn-xs'> 完 成 </span>",
        ];
        return $arr[$data['status']];
    }
    public function getTypeFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs layui-btn-warm'>正 常</span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-normal'>退 货</span>",
        ];
        return $arr[$data['type']];
    }
}
