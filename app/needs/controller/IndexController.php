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
namespace app\needs\controller;

use app\delivery_address\model\ConsignmentModel;
use app\needs\model\CatModel;
use app\needs\model\MlModel;
use app\needs\model\NeedsMlModel;
use app\needs\model\NeedsModel;
use app\needs\model\UnitModel;
use cmf\controller\AdminBaseController;
use think\Db;

class IndexController extends AdminBaseController
{
    /**
     * 商品列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $param = $this->request->param();
        $param['page'] = empty($param['page'])?1:$param['page'];
        $search = empty($param['search'])?'':trim($param['search']);
        $where['title'] = ['like',"%$search%"];
        if (!empty($param['status'])) $where['status'] = ['=',$param['status']];
        if (!empty($param['type'])) $where['type'] = ['=',$param['type']];
        $data = NeedsModel::where($where)->paginate(12);
        $param['status'] = empty($param['status'])?0:$param['status'];
        $param['type']   = empty($param['type'])?0:$param['type'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
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

        $ml = MlModel::all();
        $data = NeedsModel::get($id);
        $ml_needs= NeedsMlModel::where('needs_id','=',$id)->column('ml_id');
        foreach ($ml as $k=>$v){
            if (in_array($v['id'],$ml_needs)){
                $ml[$k]['check'] = 1;
            }else{
                $ml[$k]['check'] = 0;
            }
        }
        if (!empty($data['price'])) $data['price'] = $data['price']/100;
        $data['type']    = empty($data['type'])?1:$data['type'];
        $data['status']  = empty($data['status'])?1:$data['status'];
        $data['cat_id']  = empty($data['cat_id'])?0:$data['cat_id'];
        $data['unit_id'] = empty($data['unit_id'])?0:$data['unit_id'];
        $cat = CatModel::all();
        $unit = UnitModel::all();

        $this->assign('cat',$cat);
        $this->assign('unit',$unit);
        $this->assign('ml',$ml);
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
        $result = $this->validate($param, 'needs');
        if ($result !== true) return $this->error($result);

        if (!empty($param['id'])){
            $data = NeedsModel::get($param['id']);
        }else{
            $data = new NeedsModel();
            $data->create_time = time();
        }


        $data->update_time      = time();
        $data->type             = $param['type'];
        $data->status           = $param['status'];
        $data->cat_id           = $param['cat_id'];
        $data->unit_id          = $param['unit_id'];
        $data->num              = $param['num'];
        $data->title            = $param['title'];
        $data->price            = $param['price']*100;
        $data->thumbnail        = $param['thumbnail'];
        $data->specimen_image   = $param['specimen_image'];
        $data->background_image = $param['background_image'];
        $res = $data->save();
        if (count($param['ml'])>0){
            foreach ($param['ml'] as $k=>$v){
                $ml[$k]['needs_id']=$data['id'];
                $ml[$k]['ml_id']=$v;
            }
        }else{
            $ml[0]['needs_id']=$data['id'];
            $ml[0]['ml_id']=1;
        }
        $nm =  new NeedsMlModel();
        $nmdel = $nm->destroy(['needs_id'=>$data['id']]);
        $nmadd = $nm->saveAll($ml);
        if ($res){
            $this->success('操作成功!');
        }else {
            $this->error('操作失败!');
        }
    }

    /**
     * 修改状态
     * @throws \think\exception\DbException
     */
    public function status()
    {
        $param = $this->request->param();
        $res = NeedsModel::where('id',$param['id'])->update(['status' => $param['status']]);
        if ($res){
            return $this->success('操作成功!');
        }else{
            return $this->error('操作失败!');
        }
    }
}
