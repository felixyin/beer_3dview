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

namespace app\users\controller;

use app\api\model\BillLogModel;
use app\user\model\UserModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

/**
 * Class AdminIndexController
 * @package app\user\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'用户管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'用户管理'
 * )
 *
 * @adminMenuRoot(
 *     'name'   =>'用户组',
 *     'action' =>'default1',
 *     'parent' =>'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'',
 *     'remark' =>'用户组'
 * )
 */
class AdminIndexController extends AdminBaseController
{

    /**
     * 后台本站用户列表
     * @adminMenu(
     *     'name'   => '本站用户',
     *     'parent' => 'default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('user_admin_index_view');

        if (!empty($content)) {
            return $content;
        }
        $data = $this->request->param();
        if (!empty($data['uid'])) {
            $where['id']   = ['=',$data['uid']];
        }
        if (!empty($data['keyword'])){
            $keyword = $data['keyword'];
            $where['user_login|user_nickname|mobile']   = ['like',"%$keyword%"];
        }
        $where['user_type']   = ['=',2];
        $list = UserModel::field('*')
            ->where($where)->order("create_time DESC")->paginate(10);
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        // 渲染模板输出
        return $this->fetch();
    }

    // 充值
    public function edit_delete()
    {
        $id = $this->request->param('id');
        $user = UserModel::field('*')
            ->where('id',$id)->find();
        $this->assign('user', $user);
        return $this->fetch();
    }

    // 保存充值
    public function editPost_delete()
    {
        Db::startTrans();
        $data = $this->request->param();

        $user = UserModel::get($data['id']);
//        $user->mobile = $data['mobile'];
        $user->balance = $user['balance'] + $data['recharge']*100;
        $res = $user->save();
        if (empty($res)){
            Db::rollback();
            return $this->error('操作失败!');
        }

        $bill = new BillLogModel();
        $bill->user_id      = $data['id'];
        $bill->order_number = cmf_check_out_trade_no();
        $bill->user_class   = $user['user_class'];
        $bill->type         = 1;
        $bill->type_payment = 4;
        $bill->pay_type     = 1;
        $bill->create_time  = time();
        $bill->total_fee    = $data['recharge']*100;
        $bill->ip           = $this->request->ip();
        $bill->remake       = '线下支付充值';
        $res = $bill->save();

        if ($res){
            Db::commit();
            return $this->success('操作成功!',url('AdminIndex/index'));
        }else{
            Db::rollback();
            return $this->error('操作失败!',url('AdminIndex/index'));
        }
    }

    /**
     * 管理员修改用户手机
     * @throws \think\exception\DbException
     */
    public function editmobile()
    {
        $data = $this->request->param();
        if (empty($data) || empty($data['id']) || empty($data['mobile'])) return $this->error('参数错误,请联系管理员!');
        $user = UserModel::get($data['id']);
        $user->mobile = $data['mobile'];
        $res = $user->save();
        if($res){
            return $this->result('操作成功!',1,'操作成功!');
        }else{
            return $this->result('操作失败!',0,'操作失败!');
        }
    }

    /**
     * 本站用户拉黑
     * @adminMenu(
     *     'name'   => '本站用户拉黑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户拉黑',
     *     'param'  => ''
     * )
     */
    public function ban()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('user_status', 0);
            if ($result) {
                $this->success("会员拉黑成功！", "adminIndex/index");
            } else {
                $this->error('会员拉黑失败,会员不存在,或者是管理员！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 本站用户启用
     * @adminMenu(
     *     'name'   => '本站用户启用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户启用',
     *     'param'  => ''
     * )
     */
    public function cancelBan()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('user_status', 1);
            $this->success("会员启用成功！", '');
        } else {
            $this->error('数据传入失败！');
        }
    }
}
