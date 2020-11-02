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
namespace app\wechat_menu\controller;

use app\admin\model\PluginModel;
use app\company_intro\model\CompanyModel;
use app\wechat_menu\model\WechatMenuModel;
use cmf\controller\AdminBaseController;
use plugins\we_chat\WeChatClass;

class IndexController extends AdminBaseController
{
    public function index()
    {
        $data = WechatMenuModel::alias('a')
            ->orderRaw("concat(a.path,a.id,',')")
            ->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //添加一级分类
    public function parentadd()
    {
        return $this->fetch();
    }
    public function parentaddpost()
    {
        $param = $this->request->param();
        if (empty($param)) return $this->error('请填写菜单信息!');
        if ( strlen($param['name']) > 12 ) return $this->error('一级菜单名称不能超过4个汉字!');
        $config = PluginModel::where('name','WeChat')->value('config');
        if (empty($config)) return $this->error('请检查你的WeChat组件!');
        $config = json_decode($config,true);
        $chek = WechatMenuModel::where('parent_id',0)->select();
        $num = count($chek);
        if ($num >=3 ) return $this->error('一级菜单已经创建3个,且最多可以创建三个!');
        $data = new WechatMenuModel();
        $data->parent_id = 0;
        $data->path      = '0,';
        $data->name      = $param['name'];
        $data->type      = $param['type'];
        $data->url       = $param['url'];
        $data->appid     = $config['appletid'];
        $data->pagepath  = $param['pagepath'];
        $res = $data->save();
        if ($res){
            return $this->success('操作成功!');
        }else {
            return $this->error('操作失败!');
        }
    }

    //添加子菜单
    public function add()
    {
        $param = $this->request->param();
        if (empty($param['parent_id'])) return $this->error('缺少参数!');
        if (empty($param['name'])) return $this->error('缺少参数!');
        $param['name'] = urldecode($param['name']);
        $this->assign('param',$param);
        return $this->fetch();
    }
    public function addpost()
    {
        $param = $this->request->param();
        if (empty($param)) return $this->error('请填写菜单信息!');
        if ( strlen($param['name']) > 21 ) return $this->error('二级菜单不能超过7个汉字!');
        $config = PluginModel::where('name','WeChat')->value('config');
        if (empty($config)) return $this->error('请检查你的WeChat组件!');
        $config = json_decode($config,true);
        $chek = WechatMenuModel::where('parent_id',$param['parent_id'])->select();
        $num = count($chek);
        if ($num >=5 ) return $this->error('一级菜单已经创建5个,且最多可以创建五个!');
        $data = new WechatMenuModel();
        $data->parent_id = $param['parent_id'];
        $data->path      = '0,'.$param['parent_id'].',';
        $data->name      = $param['name'];
        $data->type      = $param['type'];
        $data->url       = $param['url'];
        if (!empty($param['pagepath'])){
            $data->appid     = $config['appletid'];
            $data->pagepath  = $param['pagepath'];
        }
        $res = $data->save();
        if ($res){
            return $this->success('操作成功!');
        }else {
            return $this->error('操作失败!');
        }
    }

    //添加子菜单
    public function edit()
    {
        $param = $this->request->param();
//        dump($param);
//        die();
        if (empty($param['id'])) return $this->error('缺少参数!');
        if ($param['parent_id']!=='0'){
            if (empty($param['parent_id'])) return $this->error('缺少参数!');
        }
        $data = WechatMenuModel::get($param['id']);
        if (empty($data))  return $this->error('参数错误!');
        $this->assign('data',$data);
        $this->assign('param',$param);
        return $this->fetch();
    }
    public function editpost()
    {
        $param = $this->request->param();
        if (empty($param)) return $this->error('请填写菜单信息!');
        if ( empty($param['id'])) return $this->error('缺少参数!');
        if ( strlen($param['name']) > 21 ) return $this->error('二级菜单不能超过7个汉字!');
        $config = PluginModel::where('name','WeChat')->value('config');
        if (empty($config)) return $this->error('请检查你的WeChat组件!');
        $config = json_decode($config,true);
        $data = WechatMenuModel::get($param['id']);
        $data->name      = $param['name'];
        $data->type      = $param['type'];
        $data->url       = $param['url'];
        $data->appid     = $config['appletid'];
        $data->pagepath  = $param['pagepath'];
        $res = $data->save();
        if ($res){
            return $this->success('操作成功!');
        }else {
            return $this->error('操作失败!');
        }
    }

    public function delete()
    {
        $id = $this->request->param('id');
        $check = WechatMenuModel::get(['parent_id'=>$id]);
        if (!empty($check)) return $this->error('该菜单下有子菜单,请先删除子菜单!');
        $res = WechatMenuModel::destroy($id);
        if ($res){
            return $this->success('删除菜单成功!');
        }else{
            return $this->error('删除菜单失败!');
        }

    }

    /**
     * 设置微信公众号菜单
     */
    public function setmenu()
    {

        $data = WechatMenuModel::all();
        $arr = cmf_get_tree($data, 0);
        foreach ($arr as $k=>$v){
            $menu[$k]['name']  = $v['name'];
            if (!empty($v['sub_button']) && is_array($v['sub_button']) && (count($v['sub_button'])>0)){
                foreach ($v['sub_button'] as $kk=>$vv){
                    $menu[$k]['sub_button'][$kk]['name']     = $vv['name'];
                    $menu[$k]['sub_button'][$kk]['url']      = $vv['url'];
                    if ($vv['type']=='miniprogram'){
                        if (empty($vv['pagepath']) || empty($vv['appid'])){
                            $menu[$k]['sub_button'][$kk]['type']     = 'view';
                        }else{
                            $menu[$k]['sub_button'][$kk]['type']     = 'miniprogram';
                            $menu[$k]['sub_button'][$kk]['appid']    = $vv['appid'];
                            $menu[$k]['sub_button'][$kk]['pagepath'] = $vv['pagepath'];
                        }
                    }else{
                        $menu[$k]['sub_button'][$kk]['type']     = 'view';
                    }
                }
            }else{
                $menu[$k]['url']   = $v['url'];
                if ($v['type']=='miniprogram'){
                    if (empty($v['pagepath']) || empty($v['appid'])){
                        $menu[$k]['type']  = 'view';
                    }else{
                        $menu[$k]['type']     = 'miniprogram';
                        $menu[$k]['appid']    = $v['appid'];
                        $menu[$k]['pagepath'] = $v['pagepath'];
                    }
                }else{
                    $menu[$k]['type']  = 'view';
                }
            }
        }

        $setmenu = ['button'=>$menu];

        $config = PluginModel::where('name','WeChat')->value('config');
        $config = json_decode($config,true);
        $dataconf = [
            'appid' =>$config['appid'],
            'appsecret'=>$config['appsecret'],
        ];
        $wx = new WeChatClass();
        $res = $wx->setMenu($setmenu,$dataconf);
        $res = json_decode($res, true);
        if ($res['errcode']==0){
            return $this->success('菜单提交成功!');
//            return $this->success('菜单提交成功!',url('admin/index/index#/wechat_menu/index/index'));
        }else{
            return $this->error('菜单提交失败!');
        }
    }

}
