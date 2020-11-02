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
namespace app\invoice\model;

use think\Db;
use think\Model;

class InvoiceLogModel extends Model
{
    public function getinvoice()
    {
        return $this->belongsTo('app\invoice\model\InvoiceModel','invoice_id','id');
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getGetStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs layui-btn-disabled'> 已领取 </span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-warm'> 未领取  </span>"
        ];
        return $arr[$data['get_status']];
    }

    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs layui-btn-warm'> 未开发票 </span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-disabled'> 已开发票  </span>"
        ];
        return $arr[$data['status']];
    }
    public function getInvoiceTypeFixAttr($value,$data)
    {
        $arr = [
            1=>"电子发票",
            2=>"纸质发票"
        ];
        return $arr[$data['invoice_type']];
    }
    public function getInvoiceStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"普票",
            2=>"专票"
        ];
        return $arr[$data['invoice_status']];
    }

}
