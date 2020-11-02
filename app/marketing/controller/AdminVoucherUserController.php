<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\controller;

use app\marketing\model\DiscountsModel;
use app\marketing\model\DiscountsUserModel;
use cmf\controller\AdminBaseController;
use think\Db;

class AdminVoucherUserController extends AdminBaseController
{
    /**
     * 优惠券管理 per-plane
     * @adminMenu(
     *     'name'   => '优惠券管理列表',
     *     'parent' => 'marketing/AdminVoucher/index',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 1000,
     *     'icon'   => '',
     *     'remark' => '优惠券管理列表',
     *     'param'  => ''
     * )
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $param = $this->request->param();
        $where = [];
        if (!empty($param['name'])) $where['n.title'] = ['like',"%".$param['name']."%"];
        $data = DiscountsUserModel::alias('du')->field('du.*,dn.end_time')
            ->join('discounts_needs dn','dn.id=du.discountsneeds_id','LEFT')
            ->join('discounts d','d.id=dn.discounts_id','LEFT')
            ->join('needs n','n.id=dn.needs_id','LEFT')
            ->where($where)->paginate(10);
        $time = time();
        $this->assign('time', $time);
        $this->assign('param', $param);
        $data->appends($param);
        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this->fetch();
    }


}
