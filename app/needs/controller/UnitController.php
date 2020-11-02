<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\needs\controller;

use app\needs\model\UnitModel;
use cmf\controller\AdminBaseController;
use think\Db;

class UnitController extends AdminBaseController
{
    /**
     * 商品分类列表
     */
    public function index()
    {
        $param = $this->request->param();
        $where['delete_time'] = ['=',0];
        if (!empty($param['status'])) $where['status'] = ['=',$param['status']];
        if (!empty($param['name']))   $where['name'] = ['like',"%".trim($param['name'])."%"];
        $data = UnitModel::field('*')->where($where)->select();
        $param['status'] = 0;
        $this->assign('param', $param);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加/编辑分类
     */
    public function add()
    {
        $id = $this->request->param('id');
        $data = [];
        if (!empty($id)) $data = UnitModel::get($id);
        $data['status']    = empty($data['status'])?1:$data['status'];
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加/编辑提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (empty($data['id'])){
                $goods_cat = new UnitModel();
                $goods_cat->create_time = time();
            }else{
                $goods_cat = UnitModel::get($data['id']);
            }
            $goods_cat->update_time = time();
            $goods_cat->name       = $data['name'];
            $goods_cat->status     = $data['status'];
            $goods_cat->list_order = $data['list_order'];
            $res = $goods_cat->save();
            if (empty($res)){
                $this->error('操作失败!');
            }else{
                $this->success('操作成功!', url('Unit/add', ['id' => $goods_cat->id]));
            }
        }
    }

    /**
     * 添加/编辑提交
     */
    public function status()
    {
        $param = $this->request->param();
        $res = UnitModel::where('id',$param['id'])->update(['status' => $param['status']]);
        if ($res){
            return $this->success('操作成功!');
        }else{
            return $this->error('操作失败!');
        }
    }

}
