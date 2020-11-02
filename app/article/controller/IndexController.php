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
namespace app\article\controller;

use app\article\model\ArticleModel;
use cmf\controller\AdminBaseController;
use think\Db;

class IndexController extends AdminBaseController
{
    /**
     * 文章列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $param = $this->request->param();
        $param['page'] = empty($param['page'])?1:$param['page'];
        $search = empty($param['search'])?'':trim($param['search']);
        $where['title'] = ['like',"%$search%"];
        if (!empty($param['type'])) $where['type'] = ['=',$param['type']];
        if (!empty($param['status'])) $where['status'] = ['=',$param['status']];
        $data = ArticleModel::where($where)->order('id desc')->paginate(10);
        $param['type']   = empty($param['type'])?0:$param['type'];
        $param['status'] = empty($param['status'])?0:$param['status'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
        return $this->fetch();
    }

    /**
     * 添加/修改文章
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $id = $this->request->param('id');

        if (!empty($id)){
            $data = ArticleModel::get($id);
            $data['content'] = htmlspecialchars_decode($data['content']);
        }else{
            $data['status'] = 1;
            $data['type'] = 1;
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存提交的需求
     * @throws \think\exception\DbException
     */
    public function addpost()
    {
        $param = $this->request->param();
        if (!empty($param['id'])){
            $data = ArticleModel::get($param['id']);
            $data->update_time = time();
            $data->update_time = time();
        }else{
            $data = new ArticleModel();
            $data->create_time = time();
            $data->update_time = time();
        }
        $data->type        = $param['type'];
        $data->status      = $param['status'];
        $data->title       = $param['title'];
        $data->content     = $param['content'];
        $data->user_id     = cmf_get_current_admin_id();
        $res = $data->save();
        if ($res){
            $this->success('操作成功!');
        }else {
            $this->error('操作失败!');
        }
    }

    /**
     * 下架需求
     * @throws \think\exception\DbException
     */
    public function status()
    {
        $id = $this->request->param('id');
        $data = ArticleModel::get($id);
        if ($data->status == 1){
            $data->status = 2;
        }elseif ($data->status == 2){
            $data->status = 1;
        }
        $res = $data->save();
        if ($res){
            return $this->success('操作成功');
        }else{
            return $this->error('操作成功');
        }
    }

}
