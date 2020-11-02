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

use app\api\model\ImTicketModel;
use app\api\model\UserChatModel;
use cmf\controller\ApiBaseController;
use think\Db;
use think\Validate;

class ImController extends ApiBaseController
{
    /**
     * 获取即时通讯ticket
     */
    public function getticket()
    {
        $user_id = $this->getUserId();
        $ticketarr = hook_one('get_ticket');
        if (!empty($ticketarr['code'])) return $this->result($ticketarr['code'],$ticketarr['message']);
        $data = new ImTicketModel();
        $data->ticket      = $ticketarr['data']['ticket'];
        $data->out_time     = $ticketarr['data']['outTime'];
        $data->create_time = time();
        $res = $data->save();
        if ($res){
            return $this->success($ticketarr['message'],$ticketarr['data']);
        }else{
            return $this->error('获取即时通讯ticket失败!');
        }
    }
    /**
     * 计划任务
     */
    public function cron()
    {
        $user_id = $this->getUserId();
        $param = $this->request->param();
        $data['cronName']     = $param['cronName'];//'kw'
        $data['crontype']     = $param['crontype'];//1;
        $data['cronTime']     = $param['cronTime'];//10;
        $data['runNum']       = $param['runNum'];//5;
        $data['starttime']    = $param['starttime'];//1595042580;

        $data['data']         = $param['data'];//11;
//        $data['data']['uid']  = $param[''];//11;
//        $data['data']['name'] = $param[''];//'LSQL1'
//;
        $data['notifyUrl']    = $param['notifyUrl'];//'http://115.29.140.95/message/index/notify';
        $res = hook_one('create_cron',$data);
        if ($res['code']==0){
            return $this->success($res['message']);
        }else{
            return $this->error($res['message']);
        }
    }



    /**
     * 注册聊天方法
     */
    public function getchat($token,$uid)
    {
        $data['token']       = $token;
        $data['uid']         = $uid;
        $data['gids']        = 'chat';
        $server_name = 'http://'.$_SERVER['SERVER_NAME'];
        $data['notifyUrl']   = $server_name.'/api.php/portal/text_chat/chatnotify';
        $res = hook_one('get_chat_login',$data);
        return $res;
    }
    /**
     * 聊天内容处理
     */
    public function chatnotify()
    {
        $res = hook_one('get_notify');
        if ($res['code']==0){
            $chat = new UserChatModel();
            $chat->scene       = $res['info']['scene'];
            $chat->type        = $res['info']['type'];
            $chat->gid         = $res['info']['gid'];
            $chat->from        = $res['info']['from'];
            $chat->to          = empty($res['info']['to'])?0:$res['info']['to'];
            $chat->attach      = empty($res['info']['attach'])?'':$res['info']['attach'];
            $chat->content     = $res['info']['content'];
            $chat->create_time = time();
            $chatres = $chat->save();
            if ($chatres){
                $this->success('success');
            }else{
                $this->error('fail');
            }
        }else{
//             log 日志;
            file_put_contents('log_chat.txt',print_r($res['data'],true),FILE_APPEND);
        }
    }

    /**
     * 注册事件方法
     */
    public function getevent($token)
    {
        $data['token']       = $token;
        $data['eventName']   = 'event';
        //跳转移动端域名配置
        $server_name = 'http://'.$_SERVER['SERVER_NAME'];
        $data['notifyUrl']   = $server_name.'/api.php/portal/text_chat/eventnotify';
        $res = hook_one('get_event_login',$data);
        return $res;
    }
    /**
     * 上下线事件处理
     */
    public function eventnotify()
    {
        $res = hook_one('get_notify');
        if ($res['code']==0){
            if ($res['info']['serviceType']=='colse'){
                Db::name('user_chat')->where('im_token', $res['info']['token'])->update(['like_status'=>0]);
            }
        }else{
//             log 日志;
            file_put_contents('log_event.txt',print_r($res['data'],true),FILE_APPEND);
        }
    }

    /**
     * 注册IM
     */
    public function IMlogin()
    {
        $user_id = $this->getUserId();
        $IMtoken = $this->request->param('im_token');
        $chat  = $this->getchat($IMtoken,$user_id);
        $event = $this->getevent($IMtoken);
        if ($chat['status']=='success' && $event['status']=='success'){
            Db::name('user_token')->where('user_id', $user_id)->update(['im_token' => $IMtoken,'like_status'=>1]);
            $this->success($chat['msg']);
        }else{
            $this->error($chat['msg'].','.$event['msg']);
        }
    }



}