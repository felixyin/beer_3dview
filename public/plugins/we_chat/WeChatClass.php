<?php
// +----------------------------------------------------------------------
// | 微信辅助类
// +----------------------------------------------------------------------
// | Author: Lsq <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\we_chat;


use app\admin\model\PluginModel;
use plugins\we_chat\Exceptions\InvalidArgumentException;
use plugins\we_chat\Exceptions\InvalidResponseException;
use plugins\we_chat\Exceptions\LocalCacheException;
use think\Controller;
use think\controller\Yar;

/**
 * Class WeChatClass 微信基础类
 * @package plugins\we_chat
 */
class WeChatClass extends Controller
{
    /**
     * 缓存路径
     * @var null
     */
    public static $cache_path = null;

    /**
     * 缓存写入操作
     * @var array
     */
    public static $cache_callable = [
        'set' => null, // 写入缓存
        'get' => null, // 获取缓存
        'del' => null, // 删除缓存
        'put' => null, // 写入文件
    ];

    /**
     * 网络缓存
     * @var array
     */
    private static $cache_curl = [];

    /**
     * 产生随机字符串
     * @param int $length 指定字符长度
     * @param string $str 字符串前缀
     * @return string
     */
    public static function createNoncestr($length = 32, $str = "")
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 设置菜单 暂时没有使用
     */
    public function setMenu($menu,$conf)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getAccessToken($conf);
        return $this->curl($url, $menu);
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo($openid,$conf)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->getAccessToken($conf) . '&openid=' . $openid . '&lang=zh_CN';
        $res = file_get_contents($url);
        $res = json_decode($res, TRUE);
        return $res;
    }

    /**
     * 小程序获取session_key
     * @param $code
     * @param $conf
     * @return mixed
     */
    public function getAuthCode2Session($code,$conf)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$conf['appid'].'&secret='.$conf['appsecret'].'&js_code='.$code.'&grant_type=authorization_code';
        $res = file_get_contents($url);
        return $res;
    }

    /**
     * 微信网页授权及登录
     */
    public function getOAuth($conf)
    {
        $redirect_uri = urlencode("https://www.lsqspace.com/api/we_chat/getUserOpenId");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$conf['appid'].'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=lsq#wechat_redirect';
        header('location:'.$url);
    }
    /**
     * access_token 获取用户信息 OpenId 辅助getOAuth()方法进行网页授权
     * @return bool|string
     * @throws \Exception
     */
    public function getUserData($code,$conf)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$conf['appid'].'&secret='.$conf['appsecret'].'&code='.$code.'&grant_type=authorization_code';
        $res = file_get_contents($url);
        $res = json_decode($res, true);
        $access_token = $res['access_token'];
        $openid       = $res['openid'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $userres = file_get_contents($url);
        $userres = json_decode($userres, true);
        return $userres;
    }

    /**
     * access_token 获取用户OpenId 辅助getOAuth()方法进行网页授权
     * @return bool|string
     * @throws \Exception
     */
    public function getUserOpenId($code,$conf)
    {
//        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$conf['appid'].'&secret='.$conf['appsecret'].'&code='.$code.'&grant_type=authorization_code';
        $res = file_get_contents($url);
        $res = json_decode($res, true);
        return $res['openid'];
    }

    /**
     * 统一下单
     * @param array $options
     * @return array
     */
    public function createOrder(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        return self::callPostApi($url, $options, false, 'MD5');
    }

    /**
     * 查询订单
     * @param array $options
     * @return array
     */
    public function query(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        return $this->callPostApi($url, $options);
    }

    /**
     * 关闭订单
     * @param string $outTradeNo 商户订单号
     * @return array
     */
    public function close($outTradeNo)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        return $this->callPostApi($url, ['out_trade_no' => $outTradeNo]);
    }

    /**
     * 申请退款
     * @param array $options
     * @return array
     */
    public function createRefund(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        return $this->callPostApi($url, $options, true);
    }
    /**
     * 查询退款
     * @param array $options
     * @return array
     */
    public function queryRefund(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        return $this->callPostApi($url, $options);
    }


    /**
     * 获取ticket
     */
    public function getTicket($data)
    {
        $json = \think\Cache::get($data['appid'] . '_ticket');
        $res = json_decode($json, true);

        if (!isset($res['expires_out']) || $res['expires_out'] <= time()) {
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken($data) . '&type=jsapi';
            $json = file_get_contents($url);
            $res = json_decode($json, true);
            if ($res['ticket']) {
                $res['expires_out'] = time() + $res['expires_in'];
                \think\Cache::set($data['appid'] . '_ticket', json_encode($res), $res['expires_in']);
            } else {
                return false;
            }
        }
        return $res['ticket'];
    }

    /**
     * 获取jsSIGN
     * @param $url
     * @return array
     */
    public function getJsSign($conf,$url = '')
    {
        $jsapi_ticket = $this->getTicket($conf);
        $timestamp = time();
        $nonceStr = 'lsq';
        $string   = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$nonceStr.'&timestamp='.$timestamp.'.&url='.$url;
        $signature = sha1($string);
        return ['appId' => $conf['appid'], 'nonceStr' => $nonceStr, 'timestamp' => $timestamp, 'signature' => $signature, 'rawString' => $string];
    }

    /**
     * 获取微信二维码
     */
    public function getQrcode($openid,$conf)
    {
        // 1.获取 ticket 票据
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->getAccessToken($conf);
        $postArr = array(
            'action_name'=>"QR_LIMIT_STR_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_str'=> $openid),
            ),
        );
//        $postArr2 = array(
//            'expire_seconds'=> 604800,
//            'action_name'=>"QR_STR_SCENE",
//            'action_info'=>array(
//                'scene'=>array('scene_str'=> $openid),
//            ),
//        );

        $res = $this->curl($url,$postArr);
        $res = json_decode($res, TRUE);
        $ticket = $res['ticket'];
        // 2.使用 ticket 获取二维码图片
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        $res = file_get_contents($url);
        $user_path = "upload/avatar/".date("Ymd");
        if(!file_exists($user_path)) mkdir($user_path,0777,true);
        $move_to_file = $user_path."/".md5(time().rand(1000,9999)).'.jpg';
        file_put_contents($move_to_file,$res);
        $move_to_file = str_replace('upload/','',$move_to_file);
        return $move_to_file;
    }

    /**
     * 获取 AccessToken
     * $data = ['appid'=>'','appsecret'=>'']; 参数
     */
    public function getAccessToken($conf)
    {
        $json = \think\Cache::get($conf['appid'] . '_access_token');
        $res = json_decode($json, true);

        if (!isset($res['expires_out']) || $res['expires_out'] <= time()) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $conf['appid'] . '&secret=' .$conf['appsecret'];
            $json = file_get_contents($url);
            $res = json_decode($json, true);
            $res['expires_out'] = time() + $res['expires_in'] - 60;
            \think\Cache::set($conf['appid'] . '_access_token', json_encode($res), $res['expires_in']);
        }
        return $res['access_token'];
    }

    /**
     * 发送模板消息
     * @return bool|string
     * @throws \Exception
     */
    public function sendMsg($conf,$openid, $template_id, $arr, $miniprogram=['appid'=>'','pagepath' => ''], $url = '')
    {
        $curl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->getAccessToken($conf);
        $data['touser']      = $openid;
        $data['template_id'] = $template_id;
        if (!empty($url)){
            $data['url']         = $url;
        }
        if (!empty($miniprogram['appid'])){
            $data['miniprogram'] = $miniprogram;
        }
        $data['data']        = $arr;
        $json = $this->curl($curl, $data);
        return $json;
    }

    /**
     * 生成支付签名
     * @param array $data 参与签名的数据
     * @param string $signType 参与签名的类型
     * @param string $buff 参与签名字符串前缀
     * @return string
     */
    public function getPaySign(array $data, $signType = 'MD5', $buff = '')
    {
        ksort($data);
        if (isset($data['sign'])) unset($data['sign']);
        if (isset($data['refund_channel'])) unset($data['refund_channel']);
        foreach ($data as $k => $v) $buff .= "{$k}={$v}&";
        $buff .= ("key=" . $data['mch_key']);
        if (strtoupper($signType) === 'MD5') {
            return strtoupper(md5($buff));
        }
        return strtoupper(hash_hmac('SHA256', $buff, $data['mch_key']));
    }
    /**
     * 解析XML内容到数组
     * @param string $xml
     * @return array
     */
    public static function xml2arr($xml)
    {
        $entity = libxml_disable_entity_loader(true);
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_disable_entity_loader($entity);
        return json_decode(json_encode($data), true);
    }

    /**
     * 获取微信支付通知
     * @return array
     */
    public function getNotify( $param )
    {
        $data = self::xml2arr($param);
        if (isset($data['sign']) && $this->getPaySign($data) === $data['sign']) {
            return $data;
        }
        throw new InvalidResponseException('Invalid Notify.', '0');
//        throw new InvalidResponseException('Invalid Notify.', '0');
    }


    /**
     * 创建JsApi及H5支付参数
     * @param string $prepay_id 统一下单预支付码
     * @return array
     */
    public function createParamsForJsApi($appid,$prepay_id)
    {
        $option = [];
        $option["appId"] = $appid;
        $option["timeStamp"] = (string)time();
        $option["nonceStr"] = self::createNoncestr();
        $option["package"] = "prepay_id=".$prepay_id;
        $option["signType"] = "MD5";
        $option["paySign"] = self::getPaySign($option, 'MD5');
        $option['timestamp'] = time();
        return $option;
    }

    public function callPostApi($url, array $data, $isCert = false, $signType = 'HMAC-SHA256', $needSignType = true)
    {
        $option = [];
        if ($isCert) {
            $config = PluginModel::where('name','WeChatFile')->value('config');
            $config = json_decode($config,true);
            $option['ssl_p12'] = 'upload/'.$config['ssl_p12'];
            if (is_string($option['ssl_p12']) && file_exists($option['ssl_p12'])) {
                $content = file_get_contents($option['ssl_p12']);
                if (openssl_pkcs12_read($content, $certs, $config['mch_id'])) {
                    $option['ssl_key'] = self::pushFile(md5($certs['pkey']) . '.pem', $certs['pkey']);
                    $option['ssl_cer'] = self::pushFile(md5($certs['cert']) . '.pem', $certs['cert']);
                } else throw new InvalidArgumentException("P12 certificate does not match MCH_ID --- ssl_p12");
            }else{
                $option['ssl_cer'] = WEB_ROOT.'/upload/'.$config['ssl_cer'];
                $option['ssl_key'] = WEB_ROOT.'/upload/'.$config['ssl_key'];
            }
            if (empty($option['ssl_cer']) || !file_exists($option['ssl_cer'])) {
                throw new InvalidArgumentException("Missing Config -- ssl_cer", '0');
            }
            if (empty($option['ssl_key']) || !file_exists($option['ssl_key'])) {
                throw new InvalidArgumentException("Missing Config -- ssl_key", '0');
            }
        }
        $needSignType && ($data['sign_type'] = strtoupper($signType));
        $data['sign'] = $this->getPaySign($data, $signType);
        $result = self::xml2arr(self::post($url, self::arr2xml($data), $option));
        if ($result['return_code'] !== 'SUCCESS') {
            throw new InvalidResponseException($result['return_msg'], '0');
        }
        return $result;
    }

    /**
     * 写入文件
     * @param string $name 文件名称
     * @param string $content 文件内容
     * @return string
     * @throws LocalCacheException
     */
    public static function pushFile($name, $content)
    {
        if (is_callable(self::$cache_callable['put'])) {
            return call_user_func_array(self::$cache_callable['put'], func_get_args());
        }
        $file = self::_getCacheName($name);
        if (!file_put_contents($file, $content)) {
            throw new LocalCacheException('local file write error.', '0');
        }
        return $file;
    }
    /**
     * 应用缓存目录
     * @param string $name
     * @return string
     */
    private static function _getCacheName($name)
    {
        if (empty(self::$cache_path)) {
            self::$cache_path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
        }
        self::$cache_path = rtrim(self::$cache_path, '/\\') . DIRECTORY_SEPARATOR;
        file_exists(self::$cache_path) || mkdir(self::$cache_path, 0755, true);
        return self::$cache_path . $name;
    }
    /**
     * 以post访问模拟访问
     * @param string $url 访问URL
     * @param array $data POST数据
     * @param array $options
     * @return boolean|string
     * @throws LocalCacheException
     */
    public static function post($url, $data = [], $options = [])
    {
        $options['data'] = $data;
        return self::doRequest('post', $url, $options);
    }

    /**
     * CURL模拟网络请求
     * @param string $method 请求方法
     * @param string $url 请求方法
     * @param array $options 请求参数[headers,data,ssl_cer,ssl_key]
     * @return boolean|string
     * @throws LocalCacheException
     */
    public static function doRequest($method, $url, $options = [])
    {
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }
        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }
        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options['data']);
        }
        // 证书文件设置
        if (!empty($options['ssl_cer'])) if (file_exists($options['ssl_cer'])) {
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $options['ssl_cer']);
        } else throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_cer]");
        // 证书文件设置
        if (!empty($options['ssl_key'])) if (file_exists($options['ssl_key'])) {
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $options['ssl_key']);
        } else throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_key]");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content) = [curl_exec($curl), curl_close($curl)];
        // 清理 CURL 缓存文件
        if (!empty(self::$cache_curl)) foreach (self::$cache_curl as $key => $file) {
            self::delCache($file);
            unset(self::$cache_curl[$key]);
        }
        return $content;
    }

    /**
     * 移除缓存文件
     * @param string $name 缓存名称
     * @return boolean
     */
    public static function delCache($name)
    {
        if (is_callable(self::$cache_callable['del'])) {
            return call_user_func_array(self::$cache_callable['del'], func_get_args());
        }
        $file = self::_getCacheName($name);
        return file_exists($file) ? unlink($file) : true;
    }

    /**
     * 数组转XML内容
     * @param array $data
     * @return string
     */
    public static function arr2xml($data)
    {
        return "<xml>" . self::_arr2xml($data) . "</xml>";
    }

    /**
     * XML内容生成
     * @param array $data 数据
     * @param string $content
     * @return string
     */
    private static function _arr2xml($data, $content = '')
    {
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = 'item';
            $content .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $content .= self::_arr2xml($val);
            } elseif (is_string($val)) {
                $content .= '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
            } else {
                $content .= $val;
            }
            $content .= "</{$key}>";
        }
        return $content;
    }

    public function curl($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //curl_setopt($ch, CURLOPT_USERAGENT, "91shiwan");
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {

            $postMultipart = false;
            $postBodyString = json_encode($postFields, JSON_UNESCAPED_UNICODE);

            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    if (defined('CURLOPT_SAFE_UPLOAD')) {
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            } else {
                $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
            }
        }

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new \Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

}