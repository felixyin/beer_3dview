<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\model;

use think\Model;

class DiscountsModel extends Model
{
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getTypeFixAttr($value,$data)
    {
        $arr = [
            1=>"自动领取",
            2=>"手动领取",
            3=>"兑换码领取"
        ];
        return $arr[$data['get_mode']];
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"专券",
            2=>"通券"
        ];
        return $arr[$data['status']];
    }
}