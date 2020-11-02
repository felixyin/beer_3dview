<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace app\marketing\controller;

use app\marketing\model\DiscountsModel;
use app\marketing\model\DiscountsNeedsModel;
use app\needs\model\CatModel;
use app\needs\model\NeedsModel;
use cmf\controller\AdminBaseController;
use think\Db;

class AdminVoucherNeedsController extends AdminBaseController
{
    /**
     * 优惠券应用管理
     */
    public function index()
    {
        $param = $this->request->param();
        $where = [];
        if (!empty($param['num']))    $where['d.num'] = ['=',$param['num']];
        if (!empty($param['name']))   $where['g.title'] = ['like',"%".trim($param['name'])."%"];
        if (!empty($param['status'])) $where['d.status'] = ['=',$param['status']];
        if (!empty($param['type'])){
            if ($param['type']==1){
                $where['d.needs_id'] = ['>',0];
            }elseif ($param['type']==2){
                $where['d.needs_id'] = ['=',0];
            }
        }
        $data = DiscountsNeedsModel::alias('d')->field('d.*')
            ->join('needs g','g.id=d.needs_id','LEFT')
            ->where($where)->paginate(10);
        $param['status'] = empty($param['status'])?0:$param['status'];
        $param['type']   = empty($param['type'])?0:$param['type'];
        $this->assign('param', $param);
        $data->appends($param);
        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    /**
     * 添加/编辑优惠券
     */
    public function add()
    {
        $id = $this->request->param('id');
        $data = [];
        if (!empty($id)) {
            $data = DiscountsNeedsModel::get($id);
            $data['cat_id'] = $data->getneeds->cat_id;
            $data['type'] = $data->getdiscounts->status;
        }
        $rule = DiscountsModel::all();
        $data['get_mode']     = empty($data['get_mode'])?1:$data['get_mode'];
        $data['type']         = empty($data['type'])?1:$data['type'];
        $data['status']       = empty($data['status'])?1:$data['status'];
        $data['cat_id']       = empty($data['cat_id'])?0:$data['cat_id'];
        $data['discounts_id'] = empty($data['discounts_id'])?0:$data['discounts_id'];
        $data['needs_id']     = empty($data['needs_id'])?0:$data['needs_id'];
        $data['start_time']   = empty($data['start_time'])?time():$data['start_time'];
        $data['end_time']     = empty($data['end_time'])?time():$data['end_time'];
        $this->assign('rule', $rule);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加/编辑提交新人
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (empty($data['needs_id'])) $this->error('请选择商品!');
            if (empty($data['discounts_id'])) $this->error('请选择优惠规则');
            if (empty($data['id'])){
                $ne = new DiscountsNeedsModel();
                $ne->create_time = time();
            }else{
                $ne = DiscountsNeedsModel::get($data['id']);
            }
            $chech = DiscountsModel::get($data['discounts_id']);
            if ($chech['status']==2) $data['needs_id'] = 0;
            $start_time = strtotime($data['start_time']);
            $end_time   = strtotime($data['end_time']);
            if ($start_time > ($end_time-600)) $this->error('活动时间选择有误,请重新选择!');
            $ne->needs_id         = $data['needs_id'];
            $ne->discounts_id     = $data['discounts_id'];
            $ne->conversion_code  = empty($data['conversion_code'])?strtoupper(cmf_create_noncestr(6)):strtoupper($data['conversion_code']);
            $ne->start_time       = $start_time;
            $ne->end_time         = $end_time;
            $ne->status           = $data['status'];
            $res = $ne->save();
            if (empty($res)){
                $this->error('操作失败!');
            }else{
                $this->success('操作成功!', url('AdminVoucherNeeds/index', ['id' => $ne->id]));
            }
        }
    }

    public function catlist()
    {
        $data = CatModel::all(['delete_time'=>0]);
        return $this->result($data,'1','cat_info');
    }
    public function needslist()
    {
        $cat = $this->request->param('pcode');
        $data = NeedsModel::all(['cat_id'=>$cat,'status'=>1]);
        return $this->result($data,'1','needs_info');
    }


}
