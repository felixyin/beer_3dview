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

use app\api\model\IndustryModel;
use app\city\model\CityModel;
use app\company\model\CompanyModel;
use app\deal\model\NeedsDealModel;
use app\delivery_address\model\ConsignmentModel;
use app\delivery_address\model\DeliveryAddressModel;
use app\invoice\model\InvoiceModel;
use app\marketing\model\DiscountsNeedsModel;
use app\marketing\model\DiscountsUserModel;
use app\needs\model\CatModel;
use app\needs\model\NeedsModel;
use app\user\model\UserModel;
use cmf\controller\ApiBaseController;
use think\Db;
use think\Validate;

class ContentController extends ApiBaseController
{

    /**
     * 获取分类
     */
    public function getcat()
    {
        $param = $this->request->param();
//        $param['page'] = empty($param['page'])?1:$param['page'];
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        if (!empty($param['search'])) $where['a.name'] = ['like',"%".trim($param['search'])."%"];
        $where['a.status'] = ['=',1];
        $cat = CatModel::alias('a')->field('a.id,a.name,a.thumbnail')
            ->where($where)
            ->order('a.id desc')->paginate(10);
        foreach ($cat as $k=>$v){
            $cat[$k]['thumbnail'] = cmf_get_image_url($v['thumbnail']);
        }
        $data['cat']   = $cat;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }

    /**
     * 获取商品
     */
    public function getneeds()
    {
        $param = $this->request->param();
//        $param['page'] = empty($param['page'])?1:$param['page'];
        if (empty($param['token']))  $this->result(0,'token');
        if (empty($param['cat_id']))  $this->result(0,'cat_id');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        if (!empty($param['search'])) $where['a.title'] = ['like',trim($param['search'])];
        $where['a.cat_id'] = ['=',$param['cat_id']];
        $where['a.status'] = ['=',1];
        $needs = NeedsModel::alias('a')
            ->field('a.*')
            ->where($where)
            ->order('a.id desc')->paginate(10);
        $data['needs'] = $needs;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 获取商品
     */
    public function getneedsinfo()
    {
        $param = $this->request->param();
//        $param['page'] = empty($param['page'])?1:$param['page'];
        if (empty($param['token']))  $this->result(0,'token');
        if (empty($param['id']))  $this->result(0,'id');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $needs = NeedsModel::alias('n')
            ->field('n.id,n.specimen_image,n.title,n.price,u.name unit_name')
            ->join('unit u','u.id=n.unit_id','LEFT')
            ->where('n.id',$param['id'])->find();

        $where['dn.needs_id'] = ['=',$param['id']];
        $where['du.user_id']  = ['=',$user['id']];
        $where['du.status']   = ['=',1];
        $where['dn.end_time'] = ['>',time()];

        $discounts = DiscountsUserModel::alias('du')->field('du.status,dn.start_time,dn.end_time,d.name,d.money,d.num')
            ->join('discounts_needs dn','dn.id=du.discountsneeds_id','LEFT')
            ->join('discounts d','d.id=dn.discounts_id','LEFT')
            ->where($where)->select();

        $needs['price']    = $needs['price']/100;
        $data['discounts'] = $discounts;
        $data['needs']     = $needs;
        $data['token']     = cmf_generate_user_token($user['id'], 'web');
        $this->result(1,'',$data);
    }

    /**
     * 发票信息列表
     */
    public function getinvoicelist()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $where['a.user_id'] = ['=',$user['id']];
        $invoice = InvoiceModel::alias('a')
            ->field('a.company,a.default_head,a.duty_num,a.mailbox,a.company_province,a.company_city,a.company_county,a.company_address')
//            ->field('a.company,a.duty_num,a.mailbox,cy.name province_name,cr.name city_name,cs.name county_name,a.company_address')
//            ->join('city cy','cy.id=a.company_province','LEFT')
//            ->join('city cr','cr.id=a.company_city','LEFT')
//            ->join('city cs','cs.id=a.company_county','LEFT')
            ->where($where)
//            ->select();
            ->paginate(10);
        $data['invoice'] = $invoice;
        $data['token']   = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 发票详情信息
     */
    public function getinvoiceinfo()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        if (empty($param['id']))  $this->result(0,'id');
        $user = cmf_generate_token_user($param['token'],'id,wxgzh_openid');
        if (empty($user))  $this->result(0,'user');
        $where['a.user_id'] = ['=',$user['id']];
        $where['a.id'] = ['=',$param['id']];
        $invoice = InvoiceModel::alias('a')->field('a.*')->where($where)->find();
        $data['invoice'] = $invoice;
        $data['token']   = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }

    /**
     * 编辑发票详情信息
     */
    public function putinvoiceinfo()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        if (empty($param['id'])){
            $data = new InvoiceModel();
            $data->create_time = time();
        }else{

            if ($param['default_head']==1){
                $check = InvoiceModel::get(['id'=>$param['id'],'default_head'=>1]);
                if($check){
                    $check->default_head = 0;
                    $check->save();
                }
            }
            $data = InvoiceModel::get($param['id']);
        }
        $data->update_time      = time();
        $data->user_id          = $user['id'];
        $data->company          = $param['company'];
        $data->duty_num         = $param['duty_num'];
        $data->mailbox          = $param['mailbox'];
        $data->default_head     = $param['default_head'];
        $data->type             = $param['type'];
        $data->company_province = $param['company_province'];
        $data->company_city     = $param['company_city'];
        $data->company_county   = $param['company_county'];
        $data->company_address  = $param['company_address'];
        $data->phone            = $param['phone'];
        $data->bank             = $param['bank'];
        $data->bank_num         = $param['bank_num'];
        $res = $data->save();
        $info['token'] = cmf_generate_user_token($user['id'], 'web');
        if ($res){
             $this->result(1,'',$info);
        }else{
             $this->result(0,'',$info);
        }
    }

    /**
     * 获取行业列表
     */
    public function getindustry()
    {
        $token = $this->request->param('token');
        if (empty($token))  $this->result(0,'token');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))  $this->result(0,'user');
        $Industry = IndustryModel::all();
        $data['industry'] = $Industry;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 获取用户信息
     */
    public function getuser()
    {
        $token = $this->request->param('token');
        if (empty($token))  $this->result(0,'token');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))  $this->result(0,'user');
//        $where['user_type']   = ['=',2];
        $where['user_status'] = ['=',1];
        $where['id']          = ['=',$user['id']];
        $userData = UserModel::field('mobile,user_name,user_nickname,sex,birthday,industry_id,position')->where($where)->find();
        $data['user'] = $userData;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 保存编辑用户信息
     */
    public function putuser()
    {
        $param = $this->request->param();
        $rules = [
//            'code'   => 'require',
            'mobile'        => 'require',
            'token'         => 'require',
            'user_name'     => 'require',
            'user_nickname' => 'require',
            'birthday'      => 'require',
            'industry_id'   => 'require',
            'position'      => 'require',
        ];
        $validate = new Validate($rules);
        $validate->message([
//            'code.require'   => '验证码不能为空',
            'mobile.require'        => '请填写电话号码',
            'token.require'         => '请登录后再进行操作',
            'user_name.require'     => '请填写名称',
            'user_nickname.require' => '请填写昵称',
            'birthday.require'      => '请选择生日',
            'industry_id.require'   => '请选择行业',
            'position.require'      => '请填职位'
        ]);

        //'user_name,user_nickname,sex,birthday,industry_id,position'

        if (!$validate->check($param)) {
             $this->result(0,$validate->getError());
        }

        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))   $this->result(0,'token！');

//        $check = cmf_check_verification_code($param['mobile'],$param['code']);
//        if (!empty($check)) {
//             $this->result(0,$check);
//        }

        $userdata = UserModel::get($user['id']);
        $userdata->mobile        = $param['mobile'];
        $userdata->user_name     = $param['user_name'];
        $userdata->user_nickname = $param['user_nickname'];
        $userdata->birthday      = $param['birthday'];
        $userdata->industry_id   = $param['industry_id'];
        $userdata->position      = $param['position'];
        $res = $userdata->save();
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        if ($res){
             $this->result(1,'success',$data['token']);
        }else{
             $this->result(0,'error',$data['token']);
        }
    }

    /**
     * 联系我们
     */
    public function contact_us()
    {
        $token = $this->request->param('token');
        if (empty($token))  $this->result(0,'token');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))  $this->result(0,'user');
        $contact_us = CompanyModel::field('server_img1,server_tel')->where('id',1)->find();
        $contact_us['server_img1'] = cmf_get_image_url($contact_us['server_img1']);
        $data['contact_us'] = $contact_us;
        $data['token']      = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 城市
     */
    public function gitcity()
    {
        $token = $this->request->param('token');
        if (empty($token))  $this->result(0,'token');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))  $this->result(0,'user');
        $city = CityModel::field('id,name')->select();
        $data['city']  = $city;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        $this->result(1,'',$data);
    }
    /**
     * 收货地址列表
     */
    public function getdelivery_address()
    {
        $token = $this->request->param('token');
        if (empty($token))  $this->result(0,'token');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))  $this->result(0,'user');
        $delivery_address = DeliveryAddressModel::alias('da')
//            ->field('da.*')
            ->field('da.*,cy.name province_name,cr.name city_name,cs.name county_name')
            ->join('city cy','cy.id=da.province','LEFT')
            ->join('city cr','cr.id=da.city','LEFT')
            ->join('city cs','cs.id=da.county','LEFT')
            ->where('da.user_id',$user['id'])->order('default desc')->select();
        $data['delivery_address'] = $delivery_address;
        $data['token']      = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 编辑收货地址
     */
    public function putdelivery_address()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        if (empty($param['id'])){
            $da = new DeliveryAddressModel();
            $da->create_time = time();
        }else{
            $da = DeliveryAddressModel::get($param['id']);
            if ($param['default']==1){
                if ($da['default']!=1){
                    $check = DeliveryAddressModel::get(['default'=>1]);
                    if (!empty($check)){
                        $check->default = 0;
                        $check->save();
                    }
                }
            }
        }
        $da->update_time = time();
        $da->user_id     = $user['id'];
        $da->province    = $param['province'];
        $da->city        = $param['city'];
        $da->county      = $param['county'];
        $da->address     = $param['address'];
        $da->default     = $param['default'];
        $da->phone       = $param['phone'];
        $da->user        = $param['user'];
        $res = $da->save();
        $data['token']      = cmf_generate_user_token($user['id'], 'web');
        if ($res){
             $this->result(1,'',$data);
        }else{
             $this->result(0,'',$data);
        }
    }

    /**
     * 领券中心
     */
    public function discount_center()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $where['dn.status'] = ['=',1];
        $dis = DiscountsNeedsModel::alias('dn')->field('dn.*,d.name,d.money,d.num')
            ->join('discounts d','d.id=dn.discounts_id','LEFT')
            ->where('dn.status',1)
            ->paginate(10);
        foreach ($dis as $k=>$v){
            $check = DiscountsUserModel::get(['user_id'=>$user['id'],'discountsneeds_id'=>$v['id']]);
            if ($check){
                $dis[$k]['discounts_status'] = 1;
            }else{
                $dis[$k]['discounts_status'] = 0;
            }
        }
        $data['discounts'] = $dis;
        $data['token']     = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }
    /**
     * 领取优惠券
     */
    public function get_discount()
    {
        $param = $this->request->param();
        if (empty($param['id']))  $this->result(0,'id');
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        $dis = DiscountsNeedsModel::get($param['id']);
        if (empty($dis)) $this->result(0,'id',$data);
        $check = DiscountsUserModel::get(['user_id'=>$user['id'],'discountsneeds_id'=>$param['id']]);
        if (!empty($check)) $this->result(0,'优惠券已领取,请不要重复操作,谢谢!!!',$data);

        if ($dis->getdiscounts->get_mode==3){
            if (!empty($param['code']) && $param['code']==$dis['conversion_code']) {
                $du = new DiscountsUserModel();
                $du->user_id           = $user['id'];
                $du->discountsneeds_id = $param['id'];
                $du->create_time       = time();
                $res = $du->save();
                if ($res){
                     $this->result(1,'ok',$data);
                }else{
                     $this->result(0,'error',$data);
                }
            }else{
                $this->result(0,'Code',$data);
            }
        }elseif($dis->getdiscounts->get_mode==2){
            $du = new DiscountsUserModel();
            $du->user_id           = $user['id'];
            $du->discountsneeds_id = $param['id'];
            $du->create_time       = time();
            $res = $du->save();
            if ($res){
                 $this->result(1,'yes',$data);
            }else{
                 $this->result(0,'no',$data);
            }
        }else{
             $this->result(0,'get_mode',$data);
        }
    }
    /**
     * 我的优惠券
     */
    public function my_discount()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $where['du.user_id'] = ['=',$user['id']];
        if (empty($param['status']) || ($param['status']==1)){
            $where['du.status'] = ['=',1];
        }elseif($param['status']==2){
            $where['du.status'] = ['=',2];
        }else{
            $where['du.status'] = ['=',1];
            $where['dn.end_time'] = ['>',time()];
        }
        $my_dis = DiscountsUserModel::alias('du')->field('du.id,du.create_time,du.use_time,du.order_id,d.name,d.money,d.num,n.title')
            ->join('discounts_needs dn','dn.id=du.discountsneeds_id','LEFT')
            ->join('discounts d','d.id=dn.discounts_id','LEFT')
            ->join('needs n','n.id=dn.needs_id','LEFT')
            ->where($where)
            ->paginate(10);
        foreach ($my_dis as $k=>$v){
            $check = DiscountsUserModel::get(['user_id'=>$user['id'],'discountsneeds_id'=>$v['id']]);
            if ($check){
                $my_dis[$k]['discounts_status'] = 1;
            }else{
                $my_dis[$k]['discounts_status'] = 0;
            }
        }
        $data['discounts'] = $my_dis;
        $data['token']     = cmf_generate_user_token($user['id'], 'web');
         $this->result(1,'',$data);
    }

    /**
     * 订单列表
     */
    public function orderlist()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');

        $where['nd.user_id'] = ['=',$user['id']];

        if (!empty($param['status'])) $where['nd.status'] = ['=',$param['status']];
        $order = NeedsDealModel::alias('nd')
//            ->field('nd.*')
            ->field('nd.order_type,nd.out_trade_no,nd.status,nd.total_fee,nd.num,nd.create_time,nd.compound_imamge,n.title,m.name,m.unit')
            ->join('needs n','n.id=nd.needs_id','LEFT')
            ->join('ml m','m.id=nd.ml_id','LEFT')
            ->where($where)
            ->paginate();
        $data['order'] = $order;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        $this->result(1,'',$data);
    }
    /**
     * 订单详情
     */
    public function orderinfo()
    {
        $param = $this->request->param();
        if (empty($param['token']))  $this->result(0,'token');
        if (empty($param['id']))  $this->result(0,'id');
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))  $this->result(0,'user');
        $where['nd.id']      = ['=',$param['id']];
        $where['nd.user_id'] = ['=',$user['id']];
        $order = NeedsDealModel::alias('nd')
//            ->field('nd.*')
            ->field('nd.id,nd.order_type,nd.out_trade_no,nd.status,nd.total_fee,nd.num,nd.create_time,n.thumbnail,n.title,n.price,n.num needs_num,u.name unit_name,m.name ml_name,m.unit ml_unit')
            ->join('ml m','m.id=nd.ml_id','LEFT')
            ->join('needs n','n.id=nd.needs_id','LEFT')
            ->join('unit u','u.id=n.unit_id','LEFT')
            ->where($where)
            ->find();
        $address = ConsignmentModel::alias('c')
            ->field('c.id,c.num,c.dh,c.status,c.create_time,pr.name province,ci.name city,co.name county,da.address,da.user,da.phone')
            ->join('delivery_address da','da.id=c.address_id','LEFT')
            ->join('city pr','pr.id=da.province','LEFT')
            ->join('city ci','ci.id=da.city','LEFT')
            ->join('city co','co.id=da.county','LEFT')
            ->where('order_id',$order['out_trade_no'])
            ->select();
        $data['order'] = $order;
        $data['address'] = $address;
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        $this->result(1,'',$data);
    }


    //图片上传
    public function uploadimg()
    {
        header('Access-Control-Allow-Origin: *');
        $param = $this->request->param();
        $user = cmf_generate_token_user($param['token'],'id');
        if (!$user)   $this->result(0,'token');
        if (empty($_FILES))   $this->result(0,'file');
        $file = $_FILES['file'];
        $uploaded_file = $file['tmp_name'];
        $user_path = "upload/uploadcontract/".date("Ymd");
        if(!file_exists($user_path)) mkdir($user_path,0777,true);
        $extension = empty(pathinfo($file['name'])['extension'])?$param['extension']:pathinfo($file['name'])['extension'];
        $move_to_file = $user_path."/".md5(time().rand(1000,9999)).'.'.$extension;
        // 返回boolean值
        $res = move_uploaded_file($uploaded_file, $move_to_file);
        $data['token'] = cmf_generate_user_token($user['id'], 'web');
        if ($res){
            $data['file']  = str_replace('upload/','',$move_to_file);
              $this->result(1,'success',$data);
        }else{
            //删除
//            if (file_exists($move_to_file)) unlink($move_to_file);
              $this->result(0,'error',$data);
        }
    }

    //取消上传图片
    public function canceluploadimg()
    {
        $param = $this->request->param();
        $user = cmf_generate_token_user($param['token'],'id');
        if (empty($user))   $this->result(0,'token');
        if (empty($param['id']))   $this->result(0,'id');
        if (empty($param['contract_img']))   $this->result(0,'contract_img');
        $data = NeedsDealModel::get(['id'=>$param['id']]);
        if (empty($data))   $this->result(0,'id');
        $token['token'] = cmf_generate_user_token($user['id'], 'web');
        if (!empty($data['contract_img'])){
            $arr_img = explode(',',$data['contract_img']);
            if (!in_array($param['contract_img'],$arr_img)){
                $res = unlink('upload/'.$param['contract_img']);
                if ($res){
                      $this->result(1,'success',$token);
                }else{
                      $this->result(0,'error',$token);
                }
            }
        }else{
            $res = unlink('upload/'.$param['contract_img']);
            if ($res){
                  $this->result(1,'success',$token);
            }else{
                  $this->result(0,'error',$token);
            }
        }
    }

    //发送验证码
    public function send_code(){
//        $username = input('post.username','');
        $token = $this->request->param('token');
        if (empty($token))    $this->result(0,'token！');
        $user = cmf_generate_token_user($token,'id');
        if (empty($user))     $this->result(0,'token！');
        $username = $this->request->param('mobile');
        if(empty($username))   $this->result(1,'手机号不能为空！');

        $res = preg_match('/^(1[3-9]{1}\d{9})$/', $username);
        if (!$res){
              $this->error("请输入正确的手机格式!");
        }

        $code = cmf_get_verification_code($username,6);
        if (empty($code)) {
              $this->result(0,'验证码发送过多,请明天再试!');
        }
        cmf_verification_code_log($username, $code);
        $param  = ['mobile' => $username, 'code' => $code];
        $result = hook_one("send_mobile_verification_code", $param);
        $data['token'] = cmf_generate_user_token($user['id'], 'web');

        if(empty($result['error'])&& empty($result['message'])){
              $this->result(1,'验证码已发送！',$data);
        }else{
              $this->result(0,$result['message'],$data);
        }
    }

    //快递
    public function expressage()
    {
        $kd = $this->request->param('dn');
        $kd = 552022383857602;
        $data = hook('get_api_kuai_di',$kd);
        $data = json_decode($data[0],true);
        $this->result(0,'',$data);
    }








}