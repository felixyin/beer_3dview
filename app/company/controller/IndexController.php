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
namespace app\company\controller;

use app\city\model\CityModel;
use app\company\model\CompanyModel;
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

        // province city county

        $province = CityModel::all(['grade'=>0,'status'=>1]);
        $city     = CityModel::all(['grade'=>1,'status'=>1]);
        $county   = CityModel::all(['grade'=>2,'status'=>1]);
        $data     = CompanyModel::get(1);
        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('county',$county);
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
        if (empty($param['id'])){
            $data = new CompanyModel();
            $data->create_time = time();
        }else{
            $data = CompanyModel::get($param['id']);
        }

        $data->update_time = time();
        $data->server_img1 = $param['server_img1'];
        $data->server_tel  = $param['server_tel'];
        $data->province    = $param['province'];
        $data->city        = $param['city'];
        $data->county      = $param['county'];
        $data->address     = $param['address'];
        $data->user        = $param['user'];
        $data->phone       = $param['phone'];
        $data->name        = $param['name'];
        $res = $data->save();
        if ($res){
            $this->success('操作成功!');
        }else {
            $this->error('操作失败!');
        }
    }

    public function city()
    {
        $id = $this->request->param('pcode');
        if ( $id !== '0' && empty($id)){
            return $this->result(0,'id');
        }
        $city = CityModel::field('id,name')->where('parent_id',$id)->select();
        $num = count($city);
        if ($num>0){
            return $this->result($city,1,$num);
        }else{
            return $this->result('',0,'error');
        }

    }



}
