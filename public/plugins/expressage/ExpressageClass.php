<?php
// +----------------------------------------------------------------------
// | 微信辅助类
// +----------------------------------------------------------------------
// | Author: Lsq <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\expressage;

use app\admin\model\PluginModel;
use think\Controller;

/**
 * Class 阿里云快递基础类
 * @package plugins\we_chat
 */
class ExpressageClass extends Controller
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
     * 获取快递信息
     */
    public function get_expressage($AppCode,$no){
        error_reporting(E_ALL || ~E_NOTICE);
        $host = "https://wuliu.market.alicloudapi.com";//api访问链接
        $path = "/kdi";//API访问后缀
        $method = "GET";
        $appcode = $AppCode;//开通服务后 买家中心-查看AppCode
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "no=$no";  //参数写在这里
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$" . $host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $out_put = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        list($header, $body) = explode("\r\n\r\n", $out_put, 2);
        return $body;
    }


}