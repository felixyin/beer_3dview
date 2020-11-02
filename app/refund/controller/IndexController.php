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
namespace app\refund\controller;

use app\admin\model\PluginModel;
use app\api\model\BillLogModel;
use app\needs\model\NeedsModel;
use app\needsdeal\model\NeedsDealModel;
use app\user\model\UserModel;
use cmf\controller\AdminBaseController;
use think\Db;

class IndexController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 退款管理
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $param = $this->request->param();
        if (!empty($param['search'])){
            $search = trim($param['search']);
            $where['a.order_number'] = ['like',"%$search%"];
        }
        if (!empty($param['refund_status'])){
            if ($param['refund_status']==4){
                $where['a.refund_status'] = ['>',2];
            }else{
                $where['a.refund_status'] = ['=',$param['refund_status']];
            }
        }
        $where['a.pay_type'] = ['=',1];
        $where['a.type'] = ['<',5];
        $data = BillLogModel::alias('a')->field('id,user_id,order_number,buy_id,type,type_payment,create_time,total_fee,refund_status,refund_number,refund_fee,update_time,transaction_id,remake,refund_description')
            ->where($where)->order('id desc')->paginate(10);

        $param['refund_status'] = empty($param['refund_status'])?0:$param['refund_status'];
        $this->assign('data',$data);
        $this->assign('param',$param);
        $data->appends($param);
        $this->assign('page',$data->render());
        return $this->fetch();
    }

    // 取消退款方法
    public function status()
    {
       $id = $this->request->param('id');
       $bill = BillLogModel::get($id);
       $bill->refund_status = 3;
       $res = $bill->save();
       if ($res){
           return $this->success('操作成功!');
       }else{
           return $this->error('操作失败!');
       }

    }

    // 添加退款
    public function add()
    {
        $id = $this->request->param('id');
        $data = BillLogModel::alias('b')->field('b.*,d.appeal_img,d.appeal_audio,d.appeal_content,d.appeal_time')
            ->join('needs_deal d','d.out_trade_no=b.order_number','LEFT')
            ->where('b.id',$id)->find();
        if (!empty($data['appeal_img'])){
//            $data['appeal_img'] = explode(',',$data['appeal_img']);
            $data['appeal_img'] = cmf_get_image_url_more($data['appeal_img']);
        }
        if (!empty($data['appeal_audio'])){
//            $data['appeal_audio'] = explode(',',$data['appeal_audio']);
            $data['appeal_audio'] = cmf_get_image_url_more($data['appeal_audio']);
        }
        $data['appeal_content'] = empty($data['appeal_content'])?'未上传退款说明内容':$data['appeal_content'];
//        $data = BillLogModel::get($id);
        if ($data['type']==1){
            $data['name'] = '充值';
            $data['refund_description'] = '充错金额';
        }elseif ($data['type']==2){
            $needs = NeedsModel::get($data['buy_id']);
            $data['name'] = '购买需求:'.$needs['title'];
            $data['refund_description'] = '需求购买错误';
        }
        switch ($data['type_payment'])
        {
            case 1: $data['type_payment'] = '微信公众号' ;break;
            case 2: $data['type_payment'] = '微信小程序' ;break;
            case 3: $data['type_payment'] = '账户余额' ;break;
        }
        $data['total_fee'] = $data['total_fee']/100;
//        dump(to_array($data));die();
        $this->assign('data',$data);
        return $this->fetch();
    }

// 后台审核并发送退款
    public function check_create_refund()
    {
        Db::startTrans();
        $param = $this->request->param();

        if (empty($param['id']) || empty($param['refund_fee']) || empty($param['refund_description'])) {
            Db::rollback();
            return $this->error('操作失败!',url('refund/index/index'));
        }

        $data = BillLogModel::get($param['id']);
        if (empty($data) || $data['total_fee'] < $param['refund_fee']) {
            Db::rollback();
            return $this->error('操作失败!',url('refund/index/index'));
        }

        $data->refund_fee         = $param['refund_fee'] * 100;
        $data->refund_description = $param['refund_description'];
        $data->refund_number      = cmf_check_out_trade_no();
        $data->update_time        = time();
        $res = $data->save();
        if (empty($res)) {
            Db::rollback();
            return $this->error('操作失败!',url('refund/index/index'));
        }

        if ($data['type_payment']==1){
            $remake          = '微信支付';
        }elseif($data['type_payment']==2){
            $remake          = '小程序支付';
        }elseif ($data['type_payment']==3){
            $remake          = '余额支付';
        }

        $refund = new BillLogModel();
        $refund->user_id            = $data['user_id'];
        $refund->user_class         = $data['user_class'];
        $refund->type_payment       = $data['type_payment'];
        $refund->total_fee          = $data['refund_fee'];
        $refund->description        = $data['refund_description'];
        $refund->refund_description = $data['remake'];
        $refund->pay_type           = 1;
        $refund->create_time        = time();
        $refund->type_advertising   = empty($data['type_advertising'])?'':$data['type_advertising'];
        $refund->type               = 8;
        $refund->buy_id             = $data['buy_id'];
        $refund->order_number       = $data['refund_number'];
        $refund->remake             = '退款: '.$remake.'->'.$data['refund_description'];
        $refund->buy_id             = $data['buy_id'];
        $refund->refund_number      = $data['order_number'];
        $refund->refund_status      = 1;
        $res = $refund->save();
        if (empty($res)) {
            Db::rollback();
            return $this->error('操作失败!',url('refund/index/index'));
        }

        $config = PluginModel::where('name','WeChat')->value('config');
        $config = json_decode($config,true);

        if ($data['type_payment']==3){
            if ($data['type']==2){
                $userbill = UserModel::get($data['user_id']);
                $userbill->balance = $userbill['balance'] + $data['refund_fee'];
                $userres = $userbill->save();
                $needsdeal = NeedsDealModel::get(['out_trade_no'=>$data['order_number']]);
                $needsdeal->pay_type = 5;
                $res = $needsdeal->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if (empty($userres) || empty($res) || empty($billres) || empty($resrefund)){
                    Db::rollback();
                    return $this->error('操作失败!',url('refund/index/index'));
                }else{
                    Db::commit();
                    return $this->success('操作成功!',url('refund/index/index'));
                }
            }elseif ($data['type']==3){
                $user = UserModel::get($data['user_id']);
                if ($user['user_valid_time']<time()) {
                    Db::rollback();
                    return $this->error('操作失败!',url('refund/index/index'));
                }
                if (($user['user_valid_time']-time()) < $data['expiration_time']){
                    $user->user_valid_time = 0;
                    $user->user_class = 4;
                }else{
                    $user->user_valid_time = $user['user_valid_time'] - $data['expiration_time'];
                }
                $user->balance = $user['balance'] + $data['refund_fee'];
                $res = $user->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if (empty($res) || empty($billres) || empty($resrefund)){
                    Db::rollback();
                    return $this->error('操作失败!',url('refund/index/index'));
                }else{
                    Db::commit();
                    return $this->success('操作成功!',url('refund/index/index'));
                }
            }elseif ($data['type']==4){
                $user = UserModel::get($data['user_id']);
                $user->balance = $user['balance'] + $data['refund_fee'];
                $res = $user->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if (empty($res) || empty($billres) || empty($resrefund)){
                    Db::rollback();
                    return $this->error('操作失败!',url('refund/index/index'));
                }else{
                    Db::commit();
                    return $this->success('操作成功!',url('refund/index/index'));
                }
            }else{
                Db::rollback();
                return $this->error('操作失败!',url('refund/index/index'));
            }
        }else{
            if ($data['type_payment']==1){
                $options['appid'] = $config['appid'];
            }elseif ($data['type_payment']==2){
                $options['appid'] = $config['appletid'];
            }else{
                Db::rollback();
                return $this->error('操作失败!',url('refund/index/index'));
            }
            $options['mch_id']        = $config['mch_id'];
            $options['out_trade_no']  = $data['order_number'];
            $options['out_refund_no'] = $data['refund_number'];
            $options['total_fee']     = floatval($data['total_fee']/100);
            $options['refund_fee']    = floatval($data['refund_fee']/100);

            if ($data['type']==1){
                $userbill = UserModel::get($data['user_id']);
                if ($userbill['balance'] < ($data['refund_fee'] + $data['dicount_fee'])){
                    Db::rollback();
                    return $this->success('操作失败,账户余额不足!',url('refund/index/index'));
                }
                $userbill->balance = $userbill['balance'] - ($data['refund_fee'] + $data['dicount_fee']);
                $userres = $userbill->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if ($userres && $billres && $resrefund){
                    $data = hook_one('create_refund',$options);
                    if ( ($data['return_code'] === 'SUCCESS') && ($data['return_msg']==='OK') && ($data['result_code']==='SUCCESS')) {
                        Db::commit();
                        return $this->success('操作成功!',url('refund/index/index'));
                    }else{
                        Db::rollback();
                        return $this->success('操作失败!',url('refund/index/index'));
                    }
                }else{
                    Db::rollback();
                    return $this->success('操作失败!',url('refund/index/index'));
                }
            }elseif ($data['type']==2){
                $needsdeal = NeedsDealModel::get(['out_trade_no'=>$data['order_number']]);
                $needsdeal->pay_type = 5;
                $res = $needsdeal->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if ($res && $billres && $resrefund){
                    $data = hook_one('create_refund',$options);
                    if ( ($data['return_code'] === 'SUCCESS') && ($data['return_msg']==='OK') && ($data['result_code']==='SUCCESS')) {
                        Db::commit();
                        return $this->success('操作成功!',url('refund/index/index'));
                    }else{
                        Db::rollback();
                        return $this->success('操作失败!',url('refund/index/index'));
                    }
                }else{
                    Db::rollback();
                    return $this->success('操作失败!',url('refund/index/index'));
                }
            }elseif ($data['type']==3){
                $user = UserModel::get($data['user_id']);
                if ($user['user_valid_time']<time()) {
                    Db::rollback();
                    return $this->error('操作失败!',url('refund/index/index'));
                }
                if (($user['user_valid_time']-time()) < $data['expiration_time']){
                    $user->user_valid_time = 0;
                    $user->user_class = 0;
                }else{
                    $user->user_valid_time = $user['user_valid_time'] - $data['expiration_time'];
                }
                $res = $user->save();

                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if ($res && $billres && $resrefund){
                    $data = hook_one('create_refund',$options);
                    if ( ($data['return_code'] === 'SUCCESS') && ($data['return_msg']==='OK') && ($data['result_code']==='SUCCESS')) {
                        Db::commit();
                        return $this->success('操作成功!',url('refund/index/index'));
                    }else{
                        Db::rollback();
                        return $this->success('操作失败!',url('refund/index/index'));
                    }
                }else{
                    Db::rollback();
                    return $this->success('操作失败!',url('refund/index/index'));
                }
            }elseif ($data['type']==4){
                $bill = BillLogModel::get($param['id']);
                $bill->refund_status = 2;
                $billres = $bill->save();

                $refund = BillLogModel::get(['order_number'=>$bill['refund_number']]);
                $refund->refund_status = 2;
                $resrefund = $refund->save();

                if ($billres && $resrefund){
                    $data = hook_one('create_refund',$options);
                    if ( ($data['return_code'] === 'SUCCESS') && ($data['return_msg']==='OK') && ($data['result_code']==='SUCCESS')) {
                        Db::commit();
                        return $this->success('操作成功!',url('refund/index/index'));
                    }else{
                        Db::rollback();
                        return $this->success('操作失败!',url('refund/index/index'));
                    }
                }else{
                    Db::rollback();
                    return $this->success('操作失败!',url('refund/index/index'));
                }
            }else{
                Db::rollback();
                return $this->success('操作失败!',url('refund/index/index'));
            }
        }
    }

}
