<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\invoice\controller;

use app\invoice\model\InvoiceLogModel;
use cmf\controller\AdminBaseController;
use think\Db;

class IndexController extends AdminBaseController
{
    /**
     * 发票管理
     * @adminMenu(
     *     'name'   => '发票管理',
     *     'parent' => 'invoice/invoice/invoice',
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
        if (!empty($param['search']))     $where['il.invoice_num|i.company'] = ['like',"%".trim($param['search'])."%"];
        if (!empty($param['status']))     $where['il.status'] = ['=',$param['status']];
        if (!empty($param['get_status']))     $where['il.get_status'] = ['=',$param['get_status']];
        $data = InvoiceLogModel::alias('il')->field('il.*')
            ->join('invoice i','i.id=il.invoice_id','LEFT')
            ->where($where)->paginate(10);

        $param['status']     = empty($param['status'])?0:$param['status'];
        $param['get_status'] = empty($param['get_status'])?0:$param['get_status'];

        $this->assign('param', $param);
        $data->appends($param);
        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    /**
     * 添加/修改
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $id = $this->request->param('id');
        if (empty($id)) $this->error('系统错误!');
        $data = InvoiceLogModel::get($id);
        if (empty($data)) $this->error('系统错误!');

        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存提交
     * @throws \think\exception\DbException
     */
    public function addpost()
    {
        $param = $this->request->param();
        if (empty($param['id'])) $this->error('操作失败!');
        if (empty($param['invoice_num'])) $this->error('请填写发票单号!');
        if (empty($param['thumbnail'])) $this->error('请上传发票图片!');
        $data = InvoiceLogModel::get($param['id']);

        $data->update_time = time();
        $data->status      = 2;
        $data->thumbnail   = $param['thumbnail'];
        $data->invoice_num = $param['invoice_num'];
        $res = $data->save();
        if ($res){
            $this->success('操作成功!');
        }else {
            $this->error('操作失败!');
        }
    }

    /**
     * 添加/修改
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getadd()
    {
        $id = $this->request->param('id');
        if (empty($id)) $this->error('系统错误!');
        $data = InvoiceLogModel::get($id);
        if (empty($data)) $this->error('系统错误!');

        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存提交
     * @throws \think\exception\DbException
     */
    public function getaddpost()
    {
        $param = $this->request->param();
        if (empty($param['id'])) $this->error('操作失败!');
        if (empty($param['get_user_name'])) $this->error('请填写领取人!');
        if (empty($param['get_user_phone'])) $this->error('请填写领取人电话!');
        $data = InvoiceLogModel::get($param['id']);
        $data->get_time       = time();
        $data->get_status     = 1;
        $data->get_user_name  = $param['get_user_name'];
        $data->get_user_phone = $param['get_user_phone'];
        $res = $data->save();
        if ($res){
            $this->success('操作成功!');
        }else {
            $this->error('操作失败!');
        }
    }

}
