<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\controller;

use app\marketing\model\DiscountsModel;
use cmf\controller\AdminBaseController;
use think\Db;

class AdminVoucherRuleController extends AdminBaseController
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
        if (!empty($param['num']))     $where['num'] = ['=',$param['num']];
        $data = DiscountsModel::field('*')->where($where)->paginate(10);
        $this->assign('param', $param);
        $data->appends($param);
        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    /**
     * 添加/编辑优惠券
     * @adminMenu(
     *     'name'   => '添加/编辑优惠券',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加/编辑优惠券',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $id = $this->request->param('id');
        $data = [];
        if (!empty($id)) {
            $data = DiscountsModel::get($id);
        }
        $data['get_mode'] = empty($data['get_mode'])?1:$data['get_mode'];
        $data['status']   = empty($data['status'])?1:$data['status'];
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加/编辑提交新人
     * @adminMenu(
     *     'name'   => '添加/编辑提交优惠券',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加/编辑提交优惠券',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (empty($data['money'])) $this->error('金额不能为空');
            if (empty($data['num'])) $this->error('优惠金额不能为空');
            if (empty($data['name'])) $this->error('规则名称不能为空');
            if (empty($data['id'])){
                $ne = new DiscountsModel();
                $ne->create_time = time();
            }else{
                $ne = DiscountsModel::get($data['id']);
            }
            $ne->update_time = time();

            $ne->name     = $data['name'];
            $ne->get_mode = $data['get_mode'];
            $ne->status   = $data['status'];
            $ne->money    = $data['money'];
            $ne->num      = $data['num'];
            $res = $ne->save();
            if (empty($res)){
                $this->error('操作失败!');
            }else{
                $this->success('操作成功!', url('AdminVoucherRule/index', ['id' => $ne->id]));
            }
        }
    }


}
