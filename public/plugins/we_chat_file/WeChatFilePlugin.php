<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\we_chat_file;

use app\admin\model\PluginModel;
use cmf\lib\Plugin;
use think\Db;

class WeChatFilePlugin extends Plugin
{
    public $info = array(
        'name' => 'WeChatFile',
        'title' => '微信支付商户管理',
        'description' => '微信支付商户管理',
        'status' => 1,
        'author' => 'LSQ',
        'version' => '1.0'
    );

    public $has_admin = 0;//插件是否有后台管理界面

    public function install()
    {
        //安装方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '获取支付辅助文件路径',
            'hook' => 'get_we_chat_file',
            'app' => 'cmf',
            'description' => '获取支付辅助文件路径'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }
        return true;//安装成功返回true，失败false
    }

    public function uninstall()
    {
        //卸载方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '获取支付辅助文件路径',
            'hook' => 'get_we_chat_file',
            'app' => 'cmf',
            'description' => '获取支付辅助文件路径'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        return true;//卸载成功返回true，失败false get_user_open_id
    }

    //实现的 get_we_chat_file 钩子方法,
    public function GetWeChatFile()
    {
        $data = Db::name('plugin')->where('name','WeChatFile')->value('config');
        return $data;
    }



}
