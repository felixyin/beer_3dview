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
namespace app\platform\model;

use think\Db;
use think\Model;

class NeedsModel extends Model
{
    public function needsdeal()
    {
        return $this->hasMany('app\needsdeal\model\NeedsDealModel','needs_id','id');
    }
    public function user()
    {
        return $this->belongsTo('app\user\model\UserModel','user_id','id');
    }

    public function getunit()
    {
        return $this->belongsTo('UnitModel','unit_id','id');
    }
    /**
     *   功能: 返回带有样式及文字说明的发布状态说明
     *   参数: 无
     *   返回: 带有样式及文字说明的发布状态
     */
    public function getTypeFixAttr($value,$data)
    {
        $arr = [
            1=>"文字",
            2=>"图文",
        ];
        return $arr[$data['type']];
    }
}
