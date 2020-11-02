<?php
// +----------------------------------------------------------------------
// | Author: LSQ <2545644408@qq.com>
// +----------------------------------------------------------------------
namespace plugins\instant_message;

use think\Controller;

/**
 * Class WeChatClass 微信基础类
 * @package plugins\we_chat
 */
class InstantMessageClass extends Controller
{
    /**
     * 生成签名
     * @param array $data 参与签名的数据
     * @param string $buff 参与签名字符串前缀
     * @return string
     */
    public function getSign($str1,$str2)
    {
        $buff = $str1.$str2;
        $sign = md5($buff);
        return $sign;
    }

    /**
     * 网络请求
     * @param array $url 请求的网络地址
     * @param string $postFields 请求网络地址携带的参数
     */
    public function curl($url, $postFields = null)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_array($postFields) && 0 < count($postFields)) {
            $postMultipart = false;
//            $postBodyString = json_encode($postFields, JSON_UNESCAPED_UNICODE);

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
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
                curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($postFields));
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