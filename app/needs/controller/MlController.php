<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\needs\controller;

use app\needs\model\MlModel;
use cmf\controller\AdminBaseController;
use think\Db;

class MlController extends AdminBaseController
{
    /**
     * 商品容量列表
     */
    public function index()
    {
        $param = $this->request->param();
        $where['delete_time'] = ['=',0];
        if (!empty($param['status'])) $where['status'] = ['=',$param['status']];
        if (!empty($param['name']))   $where['name'] = ['like',"%".trim($param['name'])."%"];
        $data = MlModel::field('*')->where($where)->select();
        $param['status'] = 0;
        $this->assign('param', $param);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加/编辑容量
     */
    public function add()
    {
        $id = $this->request->param('id');
        $data = [];
        if (!empty($id)) $data = MlModel::get($id);
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
                $goods_cat = new MlModel();
                $goods_cat->create_time = time();
            }else{
                $goods_cat = MlModel::get($data['id']);
            }
            $goods_cat->update_time = time();
            $goods_cat->name       = $data['name'];
            $goods_cat->unit       = $data['unit'];
            $goods_cat->status     = $data['status'];
            $goods_cat->list_order = $data['list_order'];
            $res = $goods_cat->save();
            if (empty($res)){
                $this->error('操作失败!');
            }else{
                $this->success('操作成功!', url('Ml/add', ['id' => $goods_cat->id]));
            }
        }
    }

    /**
     * 添加/编辑提交
     */
    public function status()
    {
        $param = $this->request->param();
        $res = MlModel::where('id',$param['id'])->update(['status' => $param['status']]);
        if ($res){
            return $this->success('操作成功!');
        }else{
            return $this->error('操作失败!');
        }
    }

}
