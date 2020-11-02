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
namespace app\marketing\model;

use think\Db;
use think\Model;

class DiscountsUserModel extends Model
{
    public function getdiscountsneeds()
    {
        return $this->belongsTo('app\marketing\model\DiscountsNeedsModel','discountsneeds_id','id');
    }
    public function getuser()
    {
        return $this->belongsTo('app\user\model\UserModel','user_id','id');
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getStatusFixAttr($value,$data)
    {
        $arr = [
            1=>"<span class='layui-btn layui-btn-xs'> 未使用 </span>",
            2=>"<span class='layui-btn layui-btn-xs layui-btn-normal'> 已使用 </span>",
        ];
        return $arr[$data['status']];
    }
}
