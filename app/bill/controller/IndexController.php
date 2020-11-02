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
namespace app\bill\controller;

use app\bill\model\BillLogModel;
use app\deal\model\NeedsDealModel;
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
        $where['out_trade_no'] = ['like',"%$search%"];
        if (!empty($param['invoice_status'])) $where['invoice_status'] = ['=',$param['invoice_status']];

        $data = NeedsDealModel::field('*')->where($where)->paginate(12);

        $param['invoice_status']     = empty($param['invoice_status'])?0:$param['invoice_status'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
        return $this->fetch();
    }


}
