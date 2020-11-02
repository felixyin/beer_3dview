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
namespace app\deal\model;

use think\Db;
use think\Model;

class NeedsDealModel extends Model
{
    public function getuser()
    {
        return $this->belongsTo('app\user\model\UserModel','user_id','id');
    }
    public function getneeds()
    {
        return $this->belongsTo('app\needs\model\NeedsModel','needs_id','id');
    }
    public function getdiscountsuser()
    {
        return $this->hasOne('app\marketing\model\DiscountsUserModel','order_id','out_trade_no');
    }
    public function getconsignment()
    {
        return $this->hasMany('app\delivery_address\model\ConsignmentModel','order_id','out_trade_no');
    }
    public function getconsignmentamdin()
    {
        return $this->hasMany('app\delivery_address\model\ConsignmentModel','order_id','out_refund_no');
    }
    public function getml()
    {
        return $this->belongsTo('app\needs\model\MlModel','ml_id','id');
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs layui-btn-disabled'> 待 付 款 </span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-warm'> 待 确 认  </span>",
            3=>"<span class='layui-btn layui-btn-xs layui-btn-danger'> 待 发 货 </span>",
            4=>"<span class='layui-btn layui-btn-xs layui-btn-normal'> 待 收 货 </span>",
            5=>"<span class='layui-btn layui-btn-xs layui-btn-primary'> 已 完 成 </span>",
        ];
        return $arr[$data['status']];
    }
}
