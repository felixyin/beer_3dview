<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\instant_message;

use app\admin\model\PluginModel;
use cmf\lib\Plugin;
use think\Db;

class InstantMessagePlugin extends Plugin
{
    public $info = array(
        'name' => 'InstantMessage',
        'title' => '即时通讯',
        'description' => '即时通讯:聊天,事件,计划任务...',
        'status' => 1,
        'author' => 'LSQ',
        'version' => '1.0',
    );

    public $has_admin = 0;//插件是否有后台管理界面

    public function install()
    {//安装方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '获取即时通讯ticket',
            'hook' => 'get_ticket',
            'app' => 'cmf',
            'description' => '获取即时通讯ticket'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '计划任务',
            'hook' => 'create_cron',
            'app' => 'cmf',
            'description' => '计划任务'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data2);
        }
        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯回调方法验证',
            'hook' => 'get_notify',
            'app' => 'cmf',
            'description' => '即时通讯回调方法验证'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data3);
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯聊天注册',
            'hook' => 'get_chat_login',
            'app' => 'cmf',
            'description' => '即时通讯聊天注册'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data4);
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯事件注册',
            'hook' => 'get_event_login',
            'app' => 'cmf',
            'description' => '即时通讯事件注册'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data5);
        }
        return true;//安装成功返回true，失败false
    }

    public function uninstall()
    {//卸载方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '获取即时通讯ticket',
            'hook' => 'get_ticket',
            'app' => 'cmf',
            'description' => '获取即时通讯ticket'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '计划任务',
            'hook' => 'create_cron',
            'app' => 'cmf',
            'description' => '计划任务'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data2)->delete();
        }
        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯回调方法验证',
            'hook' => 'get_notify',
            'app' => 'cmf',
            'description' => '即时通讯回调方法验证'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data3)->delete();
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯聊天注册',
            'hook' => 'get_chat_login',
            'app' => 'cmf',
            'description' => '即时通讯聊天注册'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (empty($test)) {
            Db::name('hook')->where($where_data4)->delete();
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '即时通讯事件注册',
            'hook' => 'get_event_login',
            'app' => 'cmf',
            'description' => '即时通讯事件注册'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (empty($test)) {
            Db::name('hook')->where($where_data5)->delete();
        }
        return true;//卸载成功返回true，失败false get_user_open_id
    }

    /**
     * 获ticket的方法
    */
    public function getTicket()
    {
        //配置信息
        $config = PluginModel::where('name','InstantMessage')->value('config');
        if (empty($config)) return '请安装InstantMessage组件';
        $config = json_decode($config,true);

        // 1. 创建接口实例
        $IM = new InstantMessageClass();

        // 2. 组装参数
        $appid       = $config['appid'];
        $appsecret   = $config['appsecret'];
        $url         = $config['url'];
        $serviceType = 'ticket';

        // 3. 创建签名
        $sign = $IM->getSign($appid,$appsecret);

        // 4. 组装请求参数
        $data['appId']       = $appid;
        $data['sign']        = $sign;
        $data['serviceType'] = $serviceType;

        // 5. 换取ticket
        $json_ticket = $IM->curl($url,$data);

        // 6. 返回结果
        $arr_ticket  = json_decode($json_ticket,true);
        if ($arr_ticket['code']=='0' && $arr_ticket['status']=='success'){
                $back_result = array(
                    'code' => 0,
                    'message' => '获取即时通讯ticket成功！',
                    'data' => $arr_ticket['info']
                );
        }else{
            $back_result = array(
                'code' => $arr_ticket['code'],
                'message' => $arr_ticket['msg'],
                'data' => ''
            );
        }
        return $back_result;
    }

    /**
     * 计划任务
     * 使用见 readme.md
     */
    public function createCron($options)
    {
        //配置信息
        $config = PluginModel::where('name','InstantMessage')->value('config');
        if (empty($config)) return '请安装InstantMessage组件';
        $config = json_decode($config,true);

        //验证必填数据
        if (empty($options['cronName']) || empty($options['cronTime']) || empty($options['notifyUrl'])) {
            $back_result = array(
                'code' => 0,
                'message' => '缺少参数!(注: cronName,cronTime,notifyUrl)',
                'data' => ''
            );
            return $back_result;
        }

        $time = time() + 10;

        // 1. 创建接口实例
        $IM = new InstantMessageClass();

        // 2. 组装参数
        $appid       = $config['appid'];
        $appsecret   = $config['appsecret'];
        $url         = $config['url'];
        $modeType    = empty($options['modetype'])   ? 'add' : $options['modetype'];
        $cronType    = empty($options['crontype'])   ? 1     : $options['crontype'];
        $runNum      = empty($options['runNum'])     ? 1     : $options['runNum'];
        $startTime   = empty($options['starttime'])  ? $time : $options['starttime'];
        $callback    = empty($options['data'])       ? []    : $options['data'];
        $notifyUrl   = $options['notifyUrl'];
        $cronName    = $options['cronName'];
        $cronTime    = $options['cronTime'];
        $serviceType = 'cron';

        // 3. 创建签名
        $sign = $IM->getSign($appid,$appsecret);

        // 4. 组装请求参数
        $data['appId']        = $appid;
        $data['sign']         = $sign;
        $data['serviceType']  = $serviceType;
        $data['modeType']     = $modeType;
        $data['cronName']     = $cronName;
        $data['cronType']     = $cronType;
        $data['cronTime']     = $cronTime;
        $data['runNum']       = $runNum;
        $data['data']         = $callback;
        $data['notifyUrl']    = $notifyUrl;
        $data['start_time']   = $startTime;

        // 5. 创建计划任务
        $json_cron = $IM->curl($url,$data);

        // 6. 返回数据
        $arr_cron  = json_decode($json_cron,true);
        if ($arr_cron['code']=='0' && $arr_cron['status']=='success'){
            $back_result = array(
                'code' => 0,
                'message' => $arr_cron['msg'],
                'data' => ''
            );
        }else{
            $back_result = array(
                'code' => $arr_cron['code'],
                'message' => $arr_cron['msg'],
                'data' => ''
            );
        }
        return $back_result;
    }

    /**
     * 回调方法验证
     * @return array
     */
    public function getNotify()
    {
        $data = file_get_contents("php://input");
        $res = json_decode($data,true);
        if ($res['code']==0 && $res['status']=='success'){
            $config = PluginModel::where('name','InstantMessage')->value('config');
            $config = json_decode($config,true);
            $IM = new InstantMessageClass();
            $resIM = $IM->getSign($res['info']['randomStr'],$config['appsecret']);
            if ($resIM == $res['info']['sign']){
                $back_result = array(
                    'code' => 0,
                    'message' => 'success',
                    'data' => $res['info']
                );
            }else{
                $back_result = array(
                    'code'    => $res['code'],
                    'message' => 'sign验证未通过',
                    'data'    => $res
                );
            }
        }else{
            $back_result = array(
                'code'    => $res['code'],
                'message' => $res['msg'],
                'data'    => $res
            );
        }
        return $back_result;
    }

    /**
     * 注册聊天(chat)
     * @param $options
     * @return array|string
     * @throws \Exception
     */
    public function getChatLogin($options)
    {
        //验证必填数据
        if (empty($options['token'])) {
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数token!',
                'data' => ''
            );
            return $back_result;
        }
        if (empty($options['notifyUrl'])){
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数notifyUrl!',
                'data' => ''
            );
            return $back_result;
        }

        if (empty($options['uid'])) {
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数uid',
                'data' => ''
            );
            return $back_result;
        }
        if (empty($options['gids'])){
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数gids',
                'data' => ''
            );
            return $back_result;
        }

        $config = PluginModel::where('name','InstantMessage')->value('config');
        if (empty($config)){
            $back_result = array(
                'code' => -1,
                'message' => '请安装InstantMessage组件',
                'data' => ''
            );
            return $back_result;
        }
        $config = json_decode($config,true);

        // 1. 创建接口实例
        $IM = new InstantMessageClass();

        // 2. 组装参数
        $appid          = $config['appid'];
        $appsecret      = $config['appsecret'];
        $url            = $config['url'];
        $modeType       = empty($options['modetype'])    ? 'add'  : $options['modetype'];
        $attach         = empty($options['attach'])      ? []     : $options['attach'];
        $notifyUrl      = $options['notifyUrl'];
        $token          = $options['token'];
        $uid            = $options['uid'];
        if ($modeType == 'add'){
            $gids[0]['gid'] = $options['gids'];
        }else{
            $gids[0]        = $options['gids'];
        }
        $serviceType    = 'chat';

        // 3. 创建签名
        $sign = $IM->getSign($appid,$appsecret);

        // 4. 组装请求参数
        $data['appId']        = $appid;
        $data['sign']         = $sign;
        $data['serviceType']  = $serviceType;
        $data['modeType']     = $modeType;
        $data['notifyUrl']    = $notifyUrl;
        $data['token']        = $token;
        $data['attach']       = $attach;
        $data['uid']          = $uid;
        $data['gids']         = $gids;

        // 5. 注册
        $json_login = $IM->curl($url,$data);

        // 6. 返回数据
        $arr_login  = json_decode($json_login,true);
        if ($arr_login['code']=='0' && $arr_login['status']=='success'){
            $back_result = array(
                'code' => 0,
                'message' => $arr_login['msg'],
                'data' => $arr_login['info']
            );
        }else{
            $back_result = array(
                'code' => $arr_login['code'],
                'message' => $arr_login['msg'],
                'data' => $arr_login
            );
        }
        return $back_result;
    }

    /**
     * 注册事件(chat)
     * @param $options
     * @return array|string
     * @throws \Exception
     */
    public function getEventLogin($options)
    {
        //验证必填数据
        if (empty($options['token'])) {
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数token!',
                'data' => ''
            );
            return $back_result;
        }
        if (empty($options['notifyUrl'])){
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数notifyUrl!',
                'data' => ''
            );
            return $back_result;
        }
        if (empty($options['eventName'])) {
            $back_result = array(
                'code' => -1,
                'message' => '缺少参数eventName',
                'data' => ''
            );
            return $back_result;
        }

        $config = PluginModel::where('name','InstantMessage')->value('config');
        if (empty($config)){
            $back_result = array(
                'code' => -1,
                'message' => '请安装InstantMessage组件',
                'data' => ''
            );
            return $back_result;
        }
        $config = json_decode($config,true);

        // 1. 创建接口实例
        $IM = new InstantMessageClass();

        // 2. 组装参数
        $appid       = $config['appid'];
        $appsecret   = $config['appsecret'];
        $url         = $config['url'];
        $modeType    = empty($options['modetype'])    ? 'add'   : $options['modetype'];
        $attach      = empty($options['attach'])      ? []      : $options['attach'];
        $notifyUrl   = $options['notifyUrl'];
        $token       = $options['token'];
        $eventName   = $options['eventName'];
        $serviceType = 'event';

        // 3. 创建签名
        $sign = $IM->getSign($appid,$appsecret);

        // 4. 组装请求参数
        $data['appId']        = $appid;
        $data['sign']         = $sign;
        $data['serviceType']  = $serviceType;
        $data['modeType']     = $modeType;
        $data['notifyUrl']    = $notifyUrl;
        $data['token']        = $token;
        $data['attach']       = $attach;
        $data['eventName']    = $eventName;

        // 5. 注册
        $json_login = $IM->curl($url,$data);

        // 6. 返回数据
        $arr_login  = json_decode($json_login,true);
        if ($arr_login['code']=='0' && $arr_login['status']=='success'){
            $back_result = array(
                'code' => 0,
                'message' => $arr_login['msg'],
                'data' => $arr_login['info']
            );
        }else{
            $back_result = array(
                'code' => $arr_login['code'],
                'message' => $arr_login['msg'],
                'data' => $arr_login
            );
        }
        return $back_result;
    }
























}
