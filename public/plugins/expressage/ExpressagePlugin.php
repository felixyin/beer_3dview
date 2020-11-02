<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\expressage;

use app\admin\model\PluginModel;
use cmf\lib\Plugin;
use think\Db;

class ExpressagePlugin extends Plugin
{
    public $info = array(
        'name' => 'Expressage',
        'title' => '阿里云快递查询',
        'description' => '阿里云快递查询',
        'status' => 1,
        'author' => 'LSQ',
        'version' => '1.0'
    );

    public $has_admin = 0;//插件是否有后台管理界面

    public function install()
    {//安装方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '快递',
            'hook' => 'get_api_kuai_di',
            'app' => 'cmf',
            'description' => '快递'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }

        return true;//安装成功返回true，失败false
    }

    public function uninstall()
    {//卸载方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '快递',
            'hook' => 'get_api_kuai_di',
            'app' => 'cmf',
            'description' => '快递'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        return true;//卸载成功返回true，失败false get_user_open_id
    }

    public function getApiKuaiDi($no)
    {
        // 1. 创建接口实例
        $wechat = new ExpressageClass();

        // 2. 组装参数，可以参考官方商户文档
        $config = PluginModel::where('name','Expressage')->value('config');
        $config = json_decode($config,true);

        // 3. 换取数据
        $options = $wechat->get_expressage($config['AppCode'],$no);

        // 4. 返回数据
        return $options;
    }





}
