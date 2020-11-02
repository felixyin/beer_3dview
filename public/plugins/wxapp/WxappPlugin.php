<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\wxapp;//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;
use plugins\we_chat\WeChatClass;
use think\Db;

//Demo插件英文名，改成你的插件英文就行了
class WxappPlugin extends Plugin
{

    public $info = [
        'name'        => 'Wxapp',//Demo插件英文名，改成你的插件英文就行了
        'title'       => '微信小程序',
        'description' => '微信小程序管理工具',
        'status'      => 1,
        'author'      => 'LSQ',
        'version'     => '1.0',
    ];

    public $hasAdmin = 1;//插件是否有后台管理界面

    public function install()
    {//安装方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序预支付',
            'hook' => 'jsapi_get_applet_order_id',
            'app' => 'cmf',
            'description' => '小程序预支付'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序支付退款',
            'hook' => 'create_applet_refund',
            'app' => 'cmf',
            'description' => '微信小程序支付退款'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data2);
        }
        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序查询退款',
            'hook' => 'query_applet_refund',
            'app' => 'cmf',
            'description' => '微信小程序查询退款'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data3);
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序查询订单',
            'hook' => 'order_applet_query',
            'app' => 'cmf',
            'description' => '微信小程序查询订单'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data4);
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序关闭订单',
            'hook' => 'close_applet_query',
            'app' => 'cmf',
            'description' => '微信小程序关闭订单'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data5);
        }
        $where_data6 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序session_key',
            'hook' => 'get_auth_code_session_key',
            'app' => 'cmf',
            'description' => '微信小程序session_key'
        ];
        $test = Db::name('hook')->where($where_data6)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data6);
        }
        return true;//安装成功返回true，失败false
    }

    public function uninstall()
    {//卸载方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序预支付',
            'hook' => 'jsapi_get_applet_order_id',
            'app' => 'cmf',
            'description' => '微信小程序预支付'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序支付退款',
            'hook' => 'create_applet_refund',
            'app' => 'cmf',
            'description' => '微信小程序支付退款'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data2)->delete();
        }

        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序查询退款',
            'hook' => 'query_applet_refund',
            'app' => 'cmf',
            'description' => '微信小程序查询退款'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data3)->delete();
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序查询订单',
            'hook' => 'order_applet_query',
            'app' => 'cmf',
            'description' => '微信小程序查询订单'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data4)->delete();
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序关闭订单',
            'hook' => 'close_applet_query',
            'app' => 'cmf',
            'description' => '微信小程序关闭订单'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data5)->delete();
        }
        $where_data6 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信小程序session_key',
            'hook' => 'get_auth_code_session_key',
            'app' => 'cmf',
            'description' => '微信小程序session_key'
        ];
        $test = Db::name('hook')->where($where_data6)->find();
        if (empty($test)) {
            Db::name('hook')->where($where_data6)->delete();
        }
        return true;//卸载成功返回true，失败false get_user_open_id
    }

    //实现的jsapi_get_order_id钩子方法,获取微信预支付信息
    // param{array[open_id,body_content,order_sn,notify_url,amount]}
    public function jsapiGetAppletOrderId($param)
    {
        $open_id = $param['open_id'];
        // 1. 创建接口实例
        $wechat = new WeChatClass();
        // 2. 组装参数，可以参考官方商户文档
        $appid = $param['appid'];     //订单描述
        $mch_id = $param['mch_id'];     //订单描述
        $nonce_str = $wechat->createNoncestr();     //订单描述
        $body_content = $param['body'];     //订单描述
        $order_sn = $param['out_trade_no'];             //订单号
        $notify_url = $param['notify_url'];             //订单号
        $total_fee = floatval($param['total_fee']) * 100;             //订单金额
        $options = [
            'appid' =>$appid,
            'mch_id' =>$mch_id,
            'nonce_str' =>$nonce_str,
            'openid' => $open_id,
            'body' => $body_content,
            'notify_url' => $notify_url,
            'out_trade_no' => $order_sn,
            'spbill_create_ip' => get_client_ip($type = 0, $adv = false),
            'total_fee' => $total_fee,
            'trade_type' => 'JSAPI'                //小程序来的支付申请
        ];

        // 生成预支付码
        $result = $wechat->createOrder($options);
        if ($result['return_code'] !== 'SUCCESS') {
            $back_result = array(
                'error' => 9,
                'message' => '预支付码创建失败！',
                'data' => ''
            );
            return $back_result;
        }
//        return $result;
        // 创建JSAPI参数签名
        $options = $wechat->createParamsForJsApi($appid,$result['prepay_id']);
        $back_result = array(
            'error' => 1,
            'message' => '预支付订单创建成功！',
            'data' => $options
        );
        return $back_result;
    }
    //实现的create_refund钩子方法,微信支付原路退款
    // param{array[out_trade_no,out_refund_no,total_fee,refund_fee]}
    public function createAppletRefund($param)
    {
        // 1. 创建接口实例
        $wechat = new WeChatClass();
        // 2. 组装参数，可以参考官方商户文档
        $options = [
            'appid' =>$param['appid'],
            'mch_id' =>$param['mch_id'],
            'nonce_str' =>$wechat->createNoncestr(),
            'out_trade_no' => $param['out_trade_no'],
            'out_refund_no' => $param['out_refund_no'],
            'total_fee' => floatval($param['total_fee']) * 100,
            'refund_fee' => floatval($param['refund_fee']) * 100,
        ];
        $result = $wechat->createRefund($options);
        return $result;
    }

    //实现的query_refund钩子方法,微信支付原路退款查询
    public function queryAppletRefund($param)
    {
        $we_chat = new WeChatClass();
        $options = [
            'appid' =>$param['appid'],
            'mch_id' =>$param['mch_id'],
            'nonce_str' =>$we_chat->createNoncestr(),
            'out_trade_no' => $param['out_trade_no'],
        ];
        $data = $we_chat->queryRefund($options);
        return $data;
    }

//实现的order_query钩子方法,微信支付订单查询
    public function orderAppletQuery($param)
    {
        $we_chat = new WeChatClass();
        $options = [
            'appid' =>$param['appid'],
            'mch_id' =>$param['mch_id'],
            'nonce_str' =>$we_chat->createNoncestr(),
            'out_trade_no' => $param['out_trade_no'],
        ];
        $data = $we_chat->query($options);
        return $data;
    }

    //实现的close_query钩子方法,微信支付关闭订单
    public function closeAppletQuery($param)
    {
        $we_chat = new WeChatClass();
        $options = [
            'appid' =>$param['appid'],
            'mch_id' =>$param['mch_id'],
            'nonce_str' =>$we_chat->createNoncestr(),
            'out_trade_no' => $param['out_trade_no'],
        ];
        $data = $we_chat->close($options);
        return $data;
    }

    /**
     * 小程序获取session_key
     * @param $code
     * @return mixed
     */
    public function GetAuthCodeSessionkey($code,$conf)
    {
        if (empty($code)) {
            $res = array(
                'code'=>0,
                'msg' => '登陆失败!',
                'data'=> []
            );
            return $res;
        }
        $we = new WeChatClass();
        $datauser = $we->getAuthCode2Session($code,$conf);
        $data = json_decode($datauser,true);
        if (empty($data)){
            $res = array(
                'code'=>0,
                'msg' => '登陆失败!',
                'data'=> []
            );
        }else{
            $res = array(
                'code'=>1,
                'msg' => '登陆成功!',
                'data'=> $data
            );
        }
        return $res;
    }



}