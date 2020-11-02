<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\validate;

use think\Validate;

class MarketingValidate extends Validate
{
    protected $rule = [
        'name'             => 'require',
        'thumbnail'        => 'require',
        'status'           => 'require',
        'brief'            => 'require',
        'goods_cat_id'     => 'require',
        'brand_id'         => 'require',
        'is_nomal_virtual' => 'require',
        'show_buy_count'   => 'require',
        'is_recommend'     => 'require',
        'is_hot'           => 'require',
        'can_coupon'       => 'require',
    ];

    protected $message = [
        'name.require'             => '商品名称不能为空！',
        'thumbnail.require'        => '缩略图不能为空！',
        'status.require'           => '发布状态不能为空！',
        'brief.require'            => '商品简介不能为空！',
        'goods_cat_id.require'     => '商品分类不能为空！',
        'brand_id.require'         => '商品品牌不能为空！',
        'is_nomal_virtual.require' => '虚拟正常商品不能为空！',
        'show_buy_count.require'   => '是否显示购买数不能为空！',
        'is_recommend.require'     => '推荐不能为空！',
        'is_hot.require'           => '热门不能为空！',
        'can_coupon.require'       => '是否可以使用优惠券不能为空！',
    ];

    protected $scene = [
//        'add'  => ['user_login,user_pass,user_email'],
//        'edit' => ['user_login,user_email'],
    ];
}