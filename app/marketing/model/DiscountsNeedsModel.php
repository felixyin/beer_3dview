<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\model;

use think\Model;

class DiscountsNeedsModel extends Model
{
    /**
     *   功能: 关联商品
     *   参数: 无
     *   返回: 商品信息
     */
    public function getneeds()
    {
        return $this->belongsTo('app\needs\model\NeedsModel','needs_id','id');
    }
    /**
     *   功能: 关联规则
     *   参数: 无
     *   返回: 规则信息
     */
    public function getdiscounts()
    {
        return $this->belongsTo('app\marketing\model\DiscountsModel','discounts_id','id');
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs'> 启 用 </span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-danger'> 禁 止 </span>",
        ];
        return $arr[$data['status']];
    }

}