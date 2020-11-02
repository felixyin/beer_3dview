<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: LSQ
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\admin\model\PluginModel;
use app\api\model\BillLogModel;
use app\delivery_address\model\ConsignmentModel;
use app\needs\model\NeedsModel;
use app\deal\model\NeedsDealModel;
use app\user\model\UserModel;
use cmf\controller\ApiBaseController;
use plugins\we_chat\WeChatClass;
use think\Db;

class WeChatController extends ApiBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  服务器验证 与微信服务器连接
     */
    public function index()
    {
        $echoStr =  isset($_GET["echostr"])?$_GET["echostr"]:false;
        if($this->checkSignature() && $echoStr) {
            echo $echoStr;
            exit;
        }else{
            $this->rMsg();
        }
    }

    /**
     * 与微信服务器请求验证
     * @return boolean
     */
    private function checkSignature()
    {
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce     = isset($_GET["nonce"])?$_GET["nonce"]:'';
        $token     = WX['token'];
        //形成数组
        $tmpArr    = array($token, $timestamp, $nonce);
        //按字典排序
        sort($tmpArr);
        //拼接成字符串
        $tmpStr    = implode( $tmpArr );
        //加密后与$signature进行比较
        $tmpStr    = sha1( $tmpStr );
        //标识该请求来源于微信
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 关注事件
     */
    public function rMsg()
    {
        //get post data, May be due to the different environments
        $postStr = file_get_contents("php://input");
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $data = CompanyModel::field('wx_subscribe,wx_text')->find(1);
            if (empty($data)){
                $data['wx_subscribe'] ="欢迎来到捷速信息网!";
                $data['wx_text']      = "请点击下方按钮了解具体情况!";
            }else{
                $data['wx_subscribe'] = empty( $data['wx_subscribe'])?"欢迎来到机械超人!": $data['wx_subscribe'];
                $data['wx_text']      = empty( $data['wx_text'])?"请点击下方按钮了解具体情况!": $data['wx_text'];
            }
            if ( $postObj->MsgType == 'event' ) {
                if ($postObj->Event == 'subscribe') {
                    $fromUsername = $postObj->FromUserName;
                    $toUsername = $postObj->ToUserName;
                    $time = time();
                    $textTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                </xml>";
                    $msgType = "text";
//
                    $myfriendopenid = (isset($postObj->EventKey) && !empty($postObj->EventKey)) ? $postObj->EventKey : 0;
                    $user = $this->getUserInfo($fromUsername,$myfriendopenid);
                    $contentStr = $data['wx_subscribe'];
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;

                } elseif ($postObj->Event == 'unsubscribe') {

                    $fromUsername = $postObj->FromUserName;
                    $user = UserModel::get(['wxgzh_openid' => $fromUsername]);
                    if (!empty($user)) {
                        $user->we_chat_status = 0;
                        $res = $user->save();
                    }
                }elseif ($postObj->Event == 'SCAN'){
                    $fromUsername = $postObj->FromUserName;
                    $toUsername = $postObj->ToUserName;
                    $time = time();
                    $textTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                </xml>";
                    $msgType = "text";
                    $myfriendopenid = (isset($postObj->EventKey) && !empty($postObj->EventKey)) ? $postObj->EventKey : 0;
                    $user = $this->getUserInfo($fromUsername,$myfriendopenid);
                    $contentStr = $data['wx_subscribe'];
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }elseif ($postObj->Event == 'TEMPLATESENDJOBFINISH'){

//                    if ($postObj->Status == 'success'){
//                        $code = 1;
//                    }
                    $Status       = $postObj->Status;
                    $CreateTime   = $postObj->CreateTime;
                    $MsgID        = $postObj->MsgID;
                    if ($Status=='success'){
//                        Db::name('wechat_template_message')->where('msgid',$MsgID)->delete();
                        Db::name('wechat_template_message')
                            ->where('msgid', $MsgID)
                            ->update([
                                'code'       => 1,
                                'status'     => $Status,
                                'update_time'=> $CreateTime,
                            ]);
                    }else{
                        Db::name('wechat_template_message')
                            ->where('msgid', $MsgID)
                            ->update([
                                'code'       => 3,
                                'status'     => $Status,
                                'update_time'=> $CreateTime,
                            ]);
                    }
                }
            }else{
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $time = time();
                $textTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                </xml>";
                $msgType = "text";
                $contentStr = $data['wx_text'];
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }else {
            echo "";
            exit;
        }
    }

    /**
     * 通过openid获取用户信息
     */
    public function getUserInfo($openid, $myfriendopenid = '0')
    {
        if (!empty($openid)){
            $data['appid'] = WX['appid'];
            $data['appsecret'] = WX['appsecret'];
            $wv = new WeChatClass();
            $userres = $wv->getUserInfo($openid,$data);
            $userres['headimgurl'] = str_replace('http://','https://',$userres['headimgurl']);
            $user = UserModel::get(['union_id' => $userres['unionid']]);

            if (!empty($user)){
                $time = time();
                if (empty($user['wxgzh_openid'])){
                    if ($user['user_class']>0 && $user['user_valid_time']<$time) $user->user_class = 0;
                    $user_qrcode = $this->getQrcode($userres['openid']);
                    $user->user_qrcode    = $user_qrcode;
                    $user->wxgzh_openid   = $userres['openid'];
                    $user->sex            = $userres['sex'];
                    $user->user_name      = $userres['nickname'];
                    $user->user_nickname  = $userres['nickname'];
                    $user->avatar         = $userres['headimgurl'];
                }else{
                    if (empty($user['user_nickname']) || ($user['user_nickname'] != $userres['nickname'])) $user->user_nickname = $userres['nickname'];
                    if (empty($user['avatar']) || ($user['avatar'] != $userres['headimgurl']) )            $user->avatar        = $userres['headimgurl'];
                    if (empty($user['user_qrcode'])){
                        $user_qrcode = $this->getQrcode($userres['openid']);
                        $user->user_qrcode   = $user_qrcode;
                    }
                }
                if ($user['user_class']>0 && $user['user_valid_time']<$time) $user->user_class = 0;
                if ($user['user_status'] == 0) $user->user_status = 1;
                $user->last_login_ip  = $this->request->ip();
                $user->we_chat_status = 1;
                $datares = $user->save();
                if ($datares){
                    cmf_generate_user_token($user['id'], 'web');
                    return 1;
                }else{
                    return 0;
                }
            }else{
                if (!empty($myfriendopenid)){
                    $user_openid = str_replace('qrscene_','',$myfriendopenid);
                    $user_parent_id = UserModel::where('wxgzh_openid' ,$user_openid)->value('id');
                    if (empty($user_parent_id)) $user_parent_id = 0;
                }else{
                    $user_parent_id = 0;
                }
                $user_qrcode = $this->getQrcode($userres['openid']);
                $user = new UserModel();
                $user->user_type      = 2;
                $user->user_qrcode    = $user_qrcode;
                $user->parent_id      = empty($myfriendopenid)? 0 : $user_parent_id;
                $user->wxgzh_openid   = $userres['openid'];
                $user->sex            = $userres['sex'];
                $user->create_time    = time();
                $user->user_nickname  = $userres['nickname'];
                $user->user_name      = $userres['nickname'];
                $user->union_id       = $userres['unionid'];
                $user->avatar         = $userres['headimgurl'];
                $user->we_chat_status = 1;
                $user->last_login_ip  = $this->request->ip();
                $datares = $user->save();
                if ($datares){
                    cmf_generate_user_token($user['id'], 'web');
                    return 1;
                }else{
                    return 0;
                }
            }
        }else{
            return 0;
        }
    }


    /**
     * 微信网页授权及登录  暂时没有使用
     */
    public function getOAuth()
    {
        $conf = [
            'appid' => WX['appid'],
        ];
        $url = hook_one('wx_get_user',$conf);
    }

    /**
     *  微信网页授权及登录辅助方法及数据处理
     */
    public function getUserOpenId()
    {
        $code = $this->request->param('code');
        $conf['appid'] = WX['appid'];
        $conf['appsecret'] = WX['appsecret'];
        $conf['code'] = $code;
        $openid = hook_one('get_user_open_id',$conf);
        $wv = new WeChatClass();
        $userres = $wv->getUserInfo($openid,$conf);
        if (empty($userres['unionid'])) return $this->result(0,'error');

        $user = UserModel::get(['union_id' => $userres['unionid']]);
        if (empty($user)){
            $user_qrcode = $this->getQrcode($userres['openid']);
            $user = new UserModel();
            $user->user_type     = 2;
            $user->user_qrcode   = $user_qrcode;
            $user->wxgzh_openid  = $userres['openid'];
            $user->sex           = $userres['sex'];
            $user->create_time   = time();
            $user->user_nickname = $userres['nickname'];
            $user->user_name     = $userres['nickname'];
            $user->union_id      = $userres['unionid'];
            $user->avatar        = $userres['headimgurl'];
            $user->last_login_ip = $this->request->ip();
            $datares = $user->save();
            if ($datares){
                $data['token'] = cmf_generate_user_token($user['id'], 'web');
                return $this->result(1,'success',$data);
            }else{
                return $this->result(0,'error');
            }
        }else{
            if (empty($user['wxgzh_openid'])){
                $user_qrcode = $this->getQrcode($userres['openid']);
                $user->user_qrcode   = $user_qrcode;
                $user->wxgzh_openid  = $userres['openid'];
                $user->sex           = $userres['sex'];
                $user->user_name     = $userres['nickname'];
                $user->user_nickname = $userres['nickname'];
                $user->avatar        = $userres['headimgurl'];
            }else{
                if (empty($user['user_nickname']) || ($user['user_nickname'] != $userres['nickname'])) $user->user_nickname = $userres['nickname'];
                if (empty($user['avatar']) || ($user['avatar'] != $userres['headimgurl']) )            $user->avatar        = $userres['headimgurl'];
                if (empty($user['user_qrcode'])){
                    $user_qrcode = $this->getQrcode($userres['openid']);
                    $user->user_qrcode   = $user_qrcode;
                }
            }
            if ($user['user_status'] == 0) $user->user_status = 1;
            $user->last_login_ip   = $this->request->ip();
            $user->last_login_time = time();
            $datares = $user->save();
            if ($datares){
                $data['token'] = cmf_generate_user_token($user['id'], 'web');
                return $this->result(1,'success',$data);
            }else{
                return $this->result(0,'error');
            }
        }
    }

    /**
     *  微信网页授权及登录辅助方法及数据处理
     */
    public function getUserData()
    {
        $code = $this->request->param('code');
        $conf['appid'] = WX['appid'];
        $conf['appsecret'] = WX['appsecret'];
        $conf['code'] = $code;
        $userres = hook_one('get_user_data',$conf);
        if (empty($userres['unionid'])) return $this->result(0,'error');

        $user = UserModel::get(['union_id' => $userres['unionid']]);
        if (empty($user)){
            $user_qrcode = $this->getQrcode($userres['openid']);
            $user = new UserModel();
            $user->user_type     = 2;
            $user->user_qrcode   = $user_qrcode;
            $user->wxgzh_openid  = $userres['openid'];
            $user->sex           = $userres['sex'];
            $user->create_time   = time();
            $user->user_nickname = $userres['nickname'];
            $user->user_name     = $userres['nickname'];
            $user->union_id      = $userres['unionid'];
            $user->avatar        = $userres['headimgurl'];
            $user->last_login_ip = $this->request->ip();
            $datares = $user->save();
            if ($datares){
                $data['token'] = cmf_generate_user_token($user['id'], 'web');
                return $this->result(1,'success',$data);
            }else{
                return $this->result(0,'error');
            }
        }else{
            if (empty($user['wxgzh_openid'])){
                $user_qrcode = $this->getQrcode($userres['openid']);
                $user->user_qrcode   = $user_qrcode;
                $user->wxgzh_openid  = $userres['openid'];
                $user->sex           = $userres['sex'];
                $user->user_name     = $userres['nickname'];
                $user->user_nickname = $userres['nickname'];
                $user->avatar        = $userres['headimgurl'];
            }else{
                if (empty($user['user_nickname']) || ($user['user_nickname'] != $userres['nickname'])) $user->user_nickname = $userres['nickname'];
                if (empty($user['avatar']) || ($user['avatar'] != $userres['headimgurl']) )            $user->avatar        = $userres['headimgurl'];
                if (empty($user['user_qrcode'])){
                    $user_qrcode = $this->getQrcode($userres['openid']);
                    $user->user_qrcode   = $user_qrcode;
                }
            }
            if ($user['user_status'] == 0) $user->user_status = 1;
            $user->last_login_ip   = $this->request->ip();
            $user->last_login_time = time();
            $datares = $user->save();
            if ($datares){
                $data['token'] = cmf_generate_user_token($user['id'], 'web');
                return $this->result(1,'success',$data);
            }else{
                return $this->result(0,'error');
            }
        }
    }

    //生成微信二维码
    public function getQrcode($openid)
    {
        $data['appid'] = WX['appid'];
        $data['appsecret'] = WX['appsecret'];
        $wv = new WeChatClass();
        $url = $wv->getQrcode($openid,$data);
        return $url;
    }

    /**
     * 小程序获取session_key
     * @param $code
     * @return mixed
     */
    public function getAuthCode2Session()
    {
        $code = $this->request->param('code');
        if (empty($code)) return $this->result(0,'code');
        $we = new WeChatClass();
        // todo  小程序 重写
        $conf['appid']     = WEAPP['appid'];
        $conf['appsecret'] = WEAPP['appsecret'];

        $datauser = $we->getAuthCode2Session($code,$conf);
        $data = json_decode($datauser,true);
        if (empty($data)) return $this->result(0,'登陆失败!');
        $token = [];

        if (!empty($data['session_key']) && !empty($data['unionid'])){
            $user = UserModel::get(['union_id'=>$data['unionid']]);
            if (!empty($user)){
                if (empty($user['app_openid'])){
                    $user->app_openid      = $data['openid'];
                    $user->last_login_ip   = $this->request->ip();
                    $user->user_status     = 1;
                    $user->last_login_time = time();
                    $res = $user->save();
                    if (empty($res)){
                        return $this->result(0,'系统错误,请联系管理员!');
                    }else{
                        $token['session_key'] = $data['session_key'];
                        $token['token'] = cmf_generate_user_token($user['id'], 'web');
                        return $this->result(1,'登陆成功!',$token);
                    }
                }else{
                    $user->last_login_ip   = $this->request->ip();
                    $user->last_login_time = time();
                    $res = $user->save();
                    if (empty($res)){
                        return $this->result(0,'系统错误,请联系管理员!');
                    }else{
                        $token['session_key'] = $data['session_key'];
                        $token['token'] = cmf_generate_user_token($user['id'], 'web');
                        return $this->result(1,'登陆成功!',$token);
                    }
                }
            }else{
                $userdata = new UserModel();
                $userdata->app_openid    = $data['openid'];
                $userdata->union_id      = $data['unionid'];

                $userdata->user_status   = 1;
                $userdata->user_type     = 2;
                $userdata->create_time   = time();
                $userdata->last_login_ip = $this->request->ip();
                $userdata->last_login_time   = time();

                $res = $userdata->save();
                if (empty($res)){
                    return $this->result(0,'系统错误,请联系管理员!');
                }else{
                    $token['session_key'] = $data['session_key'];
                    $token['token'] = cmf_generate_user_token($userdata['id'], 'web');
                    return $this->result(1,'登陆成功!',$token);
                }
            }
        }else{
            return $this->result(0,'登陆失败!');
        }
    }


    /**
     *  添加订单
     */
    public function putorder()
    {
        $param = $this->request->param();
        if (empty($param['token']))           $this->result(0,'token');
        if (empty($param['needs_id']))        $this->result(0,'needs_id');
        if (empty($param['order_type']))      $this->result(0,'order_type');
        if (empty($param['total_fee']))       $this->result(0,'total_fee');
        if (empty($param['num']))             $this->result(0,'num');
        if (empty($param['ml_id']))           $this->result(0,'ml_id');
        if (empty($param['description']))     $this->result(0,'description');
        if (empty($param['compound_imamge'])) $this->result(0,'compound_imamge');
        if (count($param['adderss'])<1)       $this->result(0,'adderss');

        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        $n = NeedsModel::get($param['needs_id']);
        $arr = [];
        foreach ($param['adderss'] as $k=>$v){
            if ($param['dz_num'] < $n['num']) $this->result(0,'dz_num');
            if (empty($param['address_id']))  $this->result(0,'address_id');
            $arr[$k]['order_id']   = $nd['out_trade_no'];
            $arr[$k]['num']        = $v['dz_num'];
            $arr[$k]['address_id'] = $v['address_id'];
            $arr[$k]['create_time'] = time();
            $arr[$k]['update_time'] = time();
        }
        $consignment = new ConsignmentModel();
        $res = $consignment->saveAll($arr);
        if ($res){
            $this->result(1,'ok',$data);
        }else{
            $this->result(0,'error',$data);
        }
    }

    /**
     * 支付
     * @throws \think\exception\DbException
     */
    public function pay()
    {
        Db::startTrans();
        $param = $this->request->param();
        if (empty($param['token']) || empty($param['obj_id'])) {
            Db::rollback();
            return $this->result(0,'error');
        }
        if (empty($param['token']))           $this->result(0,'token');
        if (empty($param['obj_id']))          $this->result(0,'obj_id');
        if (empty($param['order_type']))      $this->result(0,'order_type');
        if (empty($param['total_fee']))       $this->result(0,'total_fee');
        if (empty($param['num']))             $this->result(0,'num');
        if (empty($param['ml_id']))           $this->result(0,'ml_id');
        if (empty($param['description']))     $this->result(0,'description');
        if (empty($param['compound_imamge'])) $this->result(0,'compound_imamge');
        if (count($param['adderss'])<1)       $this->result(0,'adderss');

        $user = cmf_generate_token_user($param['token'],'id,wxgzh_openid,app_openid,user_class,user_valid_time,user_discount');
        $token['token'] = cmf_generate_user_token($user['id'], 'web');
        if (empty($user)) {
            Db::rollback();
            return $this->result(0,'user');
        }

        if ($param['type_payment']==1){
            $config = PluginModel::where('name','WeChat')->value('config');
            $config = json_decode($config,true);
            $data['appid']   = $config['appid'];
            $data['open_id'] = $user['wxgzh_openid'];
        }elseif($param['type_payment']==2){
            Db::rollback();
            return $this->result(0,'微信小程序插件未安装',$token);
        }elseif ($param['type_payment']==3){
            Db::rollback();
            return $this->result(0,'支付宝支付插件未安装',$token);
        }else{
            Db::rollback();
            return $this->result(0,'error',$token);
        }

        $data['body']             = $param['title'];
        $data['notify_url']       = 'https://'.$_SERVER['SERVER_NAME'].'/api/we_chat/notify_url';
        $data['spbill_create_ip'] = get_client_ip($type = 0, $adv = false);
        $data['total_fee']        = $param['total_fee'];
        $data['trade_type']       = 'JSAPI';
        $data['out_trade_no']     = cmf_check_out_trade_no();

        $money = $param['total_fee']*100;
        $checkneeds = NeedsModel::get($param['obj_id']);
        if (empty($checkneeds)) {
            Db::rollback();
            return $this->result(0,'未找到该商品,请重新选择或联系管理员!',$token);
        }

        $deal = new NeedsDealModel();
        $deal->needs_id        = $param['obj_id'];
        $deal->order_type      = $param['order_type'];
        $deal->ml_id           = $param['ml_id'];
        $deal->description     = $param['description'];
        $deal->total_fee       = $money;
        $deal->num             = $param['num'];
        $deal->out_trade_no    = $data['out_trade_no'];
        $deal->create_time     = time();
        $deal->update_time     = time();
        $deal->user_id         = $user['id'];
        $deal->type_payment    = $param['type_payment'];
        $deal->compound_imamge = $param['compound_imamge'];  // todo 合成图片
        $deal->status          = 1;
        $deal->pay_type     = 4;
        $res = $deal->save();
        if (empty($res)) {
            Db::rollback();
            $this->result(0, 'deal');
        }
        $n = NeedsModel::get($param['needs_id']);
        $arr = [];
        foreach ($param['adderss'] as $k=>$v){
            if ($param['dz_num'] < $n['num']) $this->result(0,'dz_num');
            if (empty($param['address_id']))  $this->result(0,'address_id');
            $arr[$k]['order_id']   = $deal['out_trade_no'];
            $arr[$k]['num']        = $v['dz_num'];
            $arr[$k]['address_id'] = $v['address_id'];
            $arr[$k]['create_time'] = time();
            $arr[$k]['update_time'] = time();
        }
        $consignment = new ConsignmentModel();
        $res = $consignment->saveAll($arr);
        if ($res){
            $res = hook_one('jsapi_get_order_id',$data);
            $res['token'] = $token['token'];
            Db::commit();
            return $this->result(1,'success',$res);
        }else{
            Db::rollback();
            return $this->result(0,'支付失败!',$token);
        }

    }

    /**
     * 获取微信支付通知
     */
    public function notify_url()
    {
        $param = file_get_contents("php://input");
        $wx = new WeChatClass();
        $data = $wx->getNotify($param);
//        $param = file_put_contents("upload/wj/".date("Ymd")."/".'txt',$data);

        if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS'){

            $deal = NeedsDealModel::get(['out_trade_no' => $data['out_trade_no']]);
            $deal->status         = 2;
            $deal->transaction_id = $data['transaction_id'];
            $res = $deal->save();
            if ($res){
                return '<xml>
                          <return_code><![CDATA[SUCCESS]]></return_code>
                          <return_msg><![CDATA[OK]]></return_msg>
                        </xml>';

            }else{
                return '<xml>
                          <return_code><![CDATA[FAIL]]></return_code>
                        </xml>';
            }
        }else{
            return '<xml>
                      <return_code><![CDATA[FAIL]]></return_code>
                    </xml>';
        }
    }

    //退款
    public function create_refund()
    {
        $param = $this->request->param();
        if (empty($param['token']) || empty($param['id'])) return $this->result(0,'error');
        $user = cmf_generate_token_user($param['token'],'id,user_class,user_valid_time');
        $token['token'] = cmf_generate_user_token($user['id'], 'web');
        if (empty($user)) return $this->result(0,'error');


        // todo 写到退款时在说吧
        $refund = BillLogModel::get($param['id']);
        if (empty($refund)) return $this->result(0,'error');

        if ($user['user_valid_time']>time()){
            $refund->user_class    = $user['user_class'];
        }else{
            $refund->user_class    = 0;
        }
        $refund->pay_type      = 2;
        $refund->description   = $param['title'];
        $refund->refund_status = 1;
        $res = $refund->save();
        if ($res){
            return $this->result(1,'success',$token);
        }else{
            return $this->result(0,'error',$token);
        }
    }




    /**
     * 测试 公众号模板消息推送
     */
    public function to_4()
    {
        $check = $this->request->param('token');
        if ($check!='lsqnb'){
            return '兄弟,长点心吧';
        }
        $config = PluginModel::where('name','WeChat')->value('config');
        $config = json_decode($config,true);
        $conf['appid']     = $config['appid'];
        $conf['appsecret'] = $config['appsecret'];
        $server_name = str_replace('www','m',$_SERVER['SERVER_NAME']);
        $data = [
            'item'    => '我的家在东北',
            'num'     => '1条/4条',
            'remark'  => '山东省/青岛市/胶州市',
            'keyword1'=> '张三'.'/'.'15051545757',
            'url'     => 'https://'.$server_name.'/h5/#/pages/main/detail-page?id=102',
            'appid'   => '',
            'page'    => ''
        ];
        $res = wxinform(4,$data,$conf);
    }
    
































}