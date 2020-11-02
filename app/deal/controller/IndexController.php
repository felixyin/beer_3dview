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
namespace app\deal\controller;

use app\deal\model\NeedsDealModel;
use app\delivery_address\model\ConsignmentModel;
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
        $where['n.title|d.out_trade_no'] = ['like',"%$search%"];
        if (!empty($param['status'])) {
            $where['d.status'] = ['=',$param['status']];
        }else{
            $where['d.status'] = ['>',1];
        }
        if (!empty($param['order_type'])) $where['d.order_type'] = ['=',$param['order_type']];
        $data = NeedsDealModel::alias('d')->field('d.*,n.title')
            ->join('needs n','n.id=d.needs_id','LEFT')
            ->where($where)->paginate(12);
        $param['status']     = empty($param['status'])?0:$param['status'];
        $param['order_type'] = empty($param['type'])?0:$param['order_type'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
        return $this->fetch();
    }

    /**
     * 商品详情列表consignment_time
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function info()
    {
        $id = $this->request->param('id');
        $data = NeedsDealModel::get($id);
        $data['compound_imamge'] = cmf_get_image_url($data['compound_imamge']);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 商品发货详情列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function consignment()
    {
        $id = $this->request->param('id');
        $data = ConsignmentModel::get($id);
        $data->status = 2;
        $res = $data->save();
        if ($res){
            $this->result('',1,'发货成功!');
        }else{
            $this->result('',0,'操作失败!');
        }
    }

    /**
     * 保存订单号码
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_dh()
    {
        $param = $this->request->param();
        $data = ConsignmentModel::get($param['id']);
        $data->dh = $param['dh'];
        $res = $data->save();
        if ($res){
            $this->result('',1,'操作成功!');
        }else{
            $this->error('',0,'操作失败!');
        }
    }

    /**
     * 修改状态
     * @throws \think\exception\DbException
     */
    public function status()
    {
        $param = $this->request->param();
        $res = NeedsDealModel::where('id',$param['id'])->update(['status' => $param['status']]);
        if ($res){
            return $this->success('操作成功!');
        }else{
            return $this->error('操作失败!');
        }
    }

    /**
     * 订单配送信息
     */
    public function logistics()
    {
        $param = $this->request->param();
        $param['page'] = empty($param['page'])?1:$param['page'];
        $where = [];
        if (!empty($param['status'])) $where['c.status'] = ['=',$param['status']];
        if (!empty($param['search'])) {
            $search = trim($param['search']);
            $where['g.title|c.order_id'] = ['like',"%$search%"];
        }

        $data = ConsignmentModel::alias('c')->field('c.*')
            ->join('needs_deal n','n.out_trade_no=c.order_id','LEFT')
            ->join('needs g','g.id=n.needs_id','LEFT')
            ->where($where)->paginate(12);

        $param['status'] = empty($param['status'])?0:$param['status'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
        return $this->fetch();
    }
    /**
     * 修改状态
     * @throws \think\exception\DbException
     */
    public function logistics_status()
    {
        $param = $this->request->param();
        $logistics_check = ConsignmentModel::get($param['id']);
        if (empty($logistics_check['dh']))  $this->error('操作失败,请填写快递单号!');
        $logistics_check->status       = $param['status'];
        $logistics_check->update_time = time();
        $res = $logistics_check->save();
        if ($res){
            $this->success('操作成功!');
        }else{
            $this->error('操作失败!');
        }
    }

    /**
     * 阿里云 快递单号查询
     * @return mixed
     */
    public function kuai_di()
    {
//        $no = 552022383857602;
        $no = $this->request->param('no');
        $id = $this->request->param('id');
        $status = $this->request->param('status');
        $data = hook('get_api_kuai_di',$no);
        $data = json_decode($data[0],true);
        $status = empty($status)?0:$status;
        $this->assign('data',$data);
        $this->assign('info',$id);
        $this->assign('status',$status);
        return $this->fetch();
    }


}
