<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\we_chat;

use cmf\lib\Plugin;
use think\Db;

class WeChatPlugin extends Plugin
{
    public $info = array(
        'name' => 'WeChat',
        'title' => '微信公众号管理',
        'description' => '微信公众号管理',
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
            'name' => '微信预支付',
            'hook' => 'jsapi_get_order_id',
            'app' => 'cmf',
            'description' => '微信预支付'
        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data1);
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信支付退款',
            'hook' => 'create_refund',
            'app' => 'cmf',
            'description' => '微信支付退款'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data2);
        }
        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信网页授权及登录',
            'hook' => 'wx_get_user',
            'app' => 'cmf',
            'description' => '微信网页授权及登录'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data3);
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信查询退款',
            'hook' => 'query_refund',
            'app' => 'cmf',
            'description' => '微信查询退款'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data4);
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信查询订单',
            'hook' => 'order_query',
            'app' => 'cmf',
            'description' => '微信查询订单'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data5);
        }
        $where_data6 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信关闭订单',
            'hook' => 'close_query',
            'app' => 'cmf',
            'description' => '微信关闭订单'
        ];
        $test = Db::name('hook')->where($where_data6)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data6);
        }
        $where_data7 = [
            'type' => 1,
            'once' => 1,
            'name' => '获取用户openid',
            'hook' => 'get_user_open_id',
            'app' => 'cmf',
            'description' => '微信辅助用户授权'
        ];
        $test = Db::name('hook')->where($where_data7)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data7);
        }
        $where_data8 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信获取用户信息',
            'hook' => 'get_user_data',
            'app' => 'cmf',
            'description' => '微信辅助用户授权'
        ];
        $test = Db::name('hook')->where($where_data8)->find();
        if (empty($test)) {
            Db::name('hook')->insert($where_data8);
        }
        return true;//安装成功返回true，失败false
    }

    public function uninstall()
    {//卸载方法必须实现
        $where_data1 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信预支付',
            'hook' => 'jsapi_get_order_id',
            'app' => 'cmf',
            'description' => '微信预支付'

        ];
        $test = Db::name('hook')->where($where_data1)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data1)->delete();
        }
        $where_data2 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信支付退款',
            'hook' => 'create_refund',
            'app' => 'cmf',
            'description' => '微信支付退款'
        ];
        $test = Db::name('hook')->where($where_data2)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data2)->delete();
        }
        $where_data3 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信网页授权及登录',
            'hook' => 'wx_get_user',
            'app' => 'cmf',
            'description' => '微信网页授权及登录'
        ];
        $test = Db::name('hook')->where($where_data3)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data3)->delete();
        }
        $where_data4 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信查询退款',
            'hook' => 'query_refund',
            'app' => 'cmf',
            'description' => '微信查询退款'
        ];
        $test = Db::name('hook')->where($where_data4)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data4)->delete();
        }
        $where_data5 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信查询订单',
            'hook' => 'order_query',
            'app' => 'cmf',
            'description' => '微信查询订单'
        ];
        $test = Db::name('hook')->where($where_data5)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data5)->delete();
        }
        $where_data6 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信关闭订单',
            'hook' => 'close_query',
            'app' => 'cmf',
            'description' => '微信关闭订单'
        ];
        $test = Db::name('hook')->where($where_data6)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data6)->delete();
        }
        $where_data7 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信获取用户open_id',
            'hook' => 'get_user_open_id',
            'app' => 'cmf',
            'description' => '微信辅助用户授权'
        ];
        $test = Db::name('hook')->where($where_data7)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data7)->delete();
        }
        $where_data8 = [
            'type' => 1,
            'once' => 1,
            'name' => '微信获取用户信息',
            'hook' => 'get_user_data',
            'app' => 'cmf',
            'description' => '微信辅助用户授权'
        ];
        $test = Db::name('hook')->where($where_data8)->find();
        if (!empty($test)) {
            Db::name('hook')->where($where_data8)->delete();
        }
        return true;//卸载成功返回true，失败false get_user_open_id
    }

    //实现的jsapi_get_order_id钩子方法,获取微信预支付信息
    // param{array[open_id,body_content,order_sn,notify_url,amount]}
    public function jsapiGetOrderId($param)
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

    //实现的wx_get_user钩子方法,
    // param{array[appid,appsecret]}
    public function wxGetUser($conf)
    {
        $we_chat = new WeChatClass();
        $user = $we_chat->getOAuth($conf);
        return $user;
    }

    //实现的get_user_open_id钩子方法,
    public function getUserOpenId($conf)
    {
        $code = $conf['code'];
        unset($conf['code']);
        $we_chat = new WeChatClass();
        $user = $we_chat->getUserOpenId($code,$conf);
        return $user;
    }

    //实现的get_user_data钩子方法,
    public function getUserData($conf)
    {
        $code = $conf['code'];
        unset($conf['code']);
        $we_chat = new WeChatClass();
        $user = $we_chat->getUserData($code,$conf);
        return $user;
    }

    //实现的create_refund钩子方法,微信支付原路退款
    // param{array[out_trade_no,out_refund_no,total_fee,refund_fee]}
    public function createRefund($param)
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
    public function queryRefund($param)
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
    public function orderQuery($param)
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
    public function closeQuery($param)
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

}
