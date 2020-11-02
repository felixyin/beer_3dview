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
namespace app\city\controller;

use app\city\model\CityModel;
use cmf\controller\AdminBaseController;
use think\Db;

class IndexController extends AdminBaseController
{

    public function index()
    {
        $data = CityModel::field('id,name,grade,path,create_time,update_time')->orderRaw("concat(path,id,',')")->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function add()
    {
        $param = $this->request->param();
        if (empty($param)) return $this->error(0,'参数错误!');
        $data = [];
        if (!empty($param['id'])) $data = CityModel::get($param['id']);
        $this->assign('param',$param);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function addpost()
    {
        $param = $this->request->param();
        if (empty($param['name'])) return $this->error('缺少参数1!');
        if ($param['grade']!=='0' && empty($param['grade'])) return $this->error('缺少参数2!');
//        if (empty($param['grade'])) return $this->error('缺少参数2!');

        $city = new CityModel();
        if (empty($param['parent_id'])){
            $city->path = '0,';
            $city->parent_id = 0;
        }else{
            $parentcity = CityModel::get($param['parent_id']);
            if (empty($parentcity)) return $this->error('参数错误!');
            $where['parent_id'] = ['=',$param['parent_id']];
            $where['name'] = ['=',$param['name']];
            $checkcity = CityModel::field('id')->where($where)->find();
            if ($checkcity) return $this->error('该城市已经添加!');
            $city->path = $parentcity['path'].$parentcity['id'].',';
            $city->parent_id = $param['parent_id'];
        }
        $city->create_time = time();
        $city->update_time = time();
        $city->name  = $param['name'];
        $city->grade = $param['grade'];
        $res = $city->save();
        if ($res){
            return $this->success('操作成功!',url('city/index/index'));
        }else{
            return $this->error('操作失败!');
        }
    }
    public function edit()
    {
        $id = $this->request->param('id');
        if (empty($id)) return $this->error(0,'参数错误!');
        $data = CityModel::get($id);
        if (empty($data)) return $this->error('参数错误!');
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function editpost()
    {
        $param = $this->request->param();
        if (empty($param['id'])) return $this->error('缺少参数!');
        if (empty($param['name'])) return $this->error('缺少参数!');
        $city = CityModel::get($param['id']);
        $city->name = $param['name'];
        $city->update_time = time();
        $res = $city->save();
        if ($res){
            return $this->success('操作成功!',url('city/index/index'));
        }else{
            return $this->error('操作失败!');
        }
    }

    public function deletecity()
    {
        $id = $this->request->param('id');
        if (empty($id)) return $this->error('缺少参数!');
        $check = CityModel::field('id')->where('parent_id',$id)->find();
        if ($check){
            return $this->error('该城市下有子城市,请先处理子城市!');
        }else{
            $res = CityModel::destroy($id);
            if ($res){
                return $this->success('操作成功!');
            }else{
                return $this->error('操作失败!');
            }

        }
    }

}
