<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
use app\needsdeal\model\NeedsDealModel;
use app\api\model\BillLogModel;
use app\api\model\CustomMadeModel;
use think\Db;
use \think\Request;
use think\facade\Env;
use think\facade\Url;
use dir\Dir;
use think\facade\Route;
use think\Loader;
use cmf\lib\Storage;
use think\facade\Hook;
use plugins\we_chat\WeChatClass;
use app\user\model\UserModel;

// 应用公共文件



/**
 * 获取当前登录的管理员ID
 * @return int
 */
function cmf_get_current_admin_id()
{
    return session('ADMIN_ID');
}

/**
 * 判断前台用户是否登录
 * @return boolean
 */
function cmf_is_user_login()
{
    $sessionUser = session('user');
    return !empty($sessionUser);
}

/**
 * 获取当前登录的前台用户的信息，未登录时，返回false
 * @return array|boolean
 */
function cmf_get_current_user()
{
    $sessionUser = session('user');
    if (!empty($sessionUser)) {
        unset($sessionUser['user_pass']); // 销毁敏感数据
        return $sessionUser;
    } else {
        return false;
    }
}

/**
 * 更新当前登录前台用户的信息
 * @param array $user 前台用户的信息
 */
function cmf_update_current_user($user)
{
    session('user', $user);
}

/**
 * 获取当前登录前台用户id
 * @return int
 */
function cmf_get_current_user_id()
{
    $sessionUserId = session('user.id');
    if (empty($sessionUserId)) {
        return 0;
    }

    return $sessionUserId;
}

/**
 * 返回带协议的域名
 */
function cmf_get_domain()
{
    return request()->domain();
}

/**
 * 获取网站根目录
 * @return string 网站根目录
 */
function cmf_get_root()
{
    $root = request()->root();
    $root = str_replace("//", '/', $root);
    $root = str_replace('/index.php', '', $root);
    if (defined('APP_NAMESPACE') && APP_NAMESPACE == 'api') {
        $root = preg_replace('/\/api$/', '', $root);
    }

    $root = rtrim($root, '/');

    return $root;
}

/**
 * 获取当前主题名
 * @return string
 */
function cmf_get_current_theme()
{
    static $_currentTheme;

    if (!empty($_currentTheme)) {
        return $_currentTheme;
    }

    $t     = 't';
    $theme = config('template.cmf_default_theme');

    $cmfDetectTheme = config('template.cmf_detect_theme');
    if ($cmfDetectTheme) {
        if (isset($_GET[$t])) {
            $theme = $_GET[$t];
            cookie('cmf_template', $theme, 864000);
        } elseif (cookie('cmf_template')) {
            $theme = cookie('cmf_template');
        }
    }

    $hookTheme = hook_one('switch_theme');

    if ($hookTheme) {
        $theme = $hookTheme;
    }

    $designT = '_design_theme';
    if (isset($_GET[$designT])) {
        $theme = $_GET[$designT];
        cookie('cmf_design_theme', $theme, 4);
    } elseif (cookie('cmf_design_theme')) {
        $theme = cookie('cmf_design_theme');
    }

    $_currentTheme = $theme;

    return $theme;
}


/**
 * 获取当前后台主题名
 * @return string
 */
function cmf_get_current_admin_theme()
{
    static $_currentAdminTheme;

    if (!empty($_currentAdminTheme)) {
        return $_currentAdminTheme;
    }

    $t     = '_at';
    $theme = config('template.cmf_admin_default_theme');

    $cmfDetectTheme = true;
    if ($cmfDetectTheme) {
        if (isset($_GET[$t])) {
            $theme = $_GET[$t];
            cookie('cmf_admin_theme', $theme, 864000);
        } elseif (cookie('cmf_admin_theme')) {
            $theme = cookie('cmf_admin_theme');
        }
    }

    $hookTheme = hook_one('switch_admin_theme');

    if ($hookTheme) {
        $theme = $hookTheme;
    }

    $_currentAdminTheme = $theme;

    return $theme;
}

/**
 * 获取前台模板根目录
 * @param string $theme
 * @return string 前台模板根目录
 */
function cmf_get_theme_path($theme = null)
{
    $themePath = config('template.cmf_theme_path');
    if ($theme === null) {
        // 获取当前主题名称
        $theme = cmf_get_current_theme();
    }

    return WEB_ROOT . $themePath . $theme;
}

/**
 * 获取用户头像地址
 * @param $avatar 用户头像文件路径,相对于 upload 目录
 * @return string
 */
function cmf_get_user_avatar_url($avatar)
{
    if (!empty($avatar)) {
        if (strpos($avatar, "http") === 0) {
            return $avatar;
        } else {
            if (strpos($avatar, 'avatar/') === false) {
                $avatar = 'avatar/' . $avatar;
            }

            return cmf_get_image_url($avatar, 'avatar');
        }

    } else {
        return $avatar;
    }

}

/**
 * CMF密码加密方法
 * @param string $pw       要加密的原始密码
 * @param string $authCode 加密字符串
 * @return string
 */
function cmf_password($pw, $authCode = '')
{
    if (empty($authCode)) {
        $authCode = config('database.authcode');
    }
    $result = "###" . md5(md5($authCode . $pw));
    return $result;
}

/**
 * CMF密码加密方法 (X2.0.0以前的方法)
 * @param string $pw 要加密的原始密码
 * @return string
 */
function cmf_password_old($pw)
{
    $decor = md5(config('database.prefix'));
    $mi    = md5($pw);
    return substr($decor, 0, 12) . $mi . substr($decor, -4, 4);
}

/**
 * CMF密码比较方法,所有涉及密码比较的地方都用这个方法
 * @param string $password     要比较的密码
 * @param string $passwordInDb 数据库保存的已经加密过的密码
 * @return boolean 密码相同，返回true
 */
function cmf_compare_password($password, $passwordInDb)
{
    if (strpos($passwordInDb, "###") === 0) {
        return cmf_password($password) == $passwordInDb;
    } else {
        return cmf_password_old($password) == $passwordInDb;
    }
}

/**
 * 文件日志
 * @param        $content 要写入的内容
 * @param string $file    日志文件,在web 入口目录
 */
function cmf_log($content, $file = "log.txt")
{
    file_put_contents($file, $content, FILE_APPEND);
}

/**
 * 随机字符串生成
 * @param int $len 生成的字符串长度
 * @return string
 */
function cmf_random_string($len = 6)
{
    $chars    = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    ];
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 * 清空系统缓存
 */
function cmf_clear_cache()
{
    // 清除 opcache缓存
    if (function_exists("opcache_reset")) {
        opcache_reset();
    }

    $dirs     = [];
    $rootDirs = cmf_scan_dir(Env::get('runtime_path') . "*");
    //$noNeedClear=array(".","..","Data");
    $noNeedClear = ['.', '..', 'log'];
    $rootDirs    = array_diff($rootDirs, $noNeedClear);
    foreach ($rootDirs as $dir) {

        if ($dir != "." && $dir != "..") {
            $dir = Env::get('runtime_path') . $dir;
            if (is_dir($dir)) {
                //array_push ( $dirs, $dir );
                $tmpRootDirs = cmf_scan_dir($dir . "/*");
                foreach ($tmpRootDirs as $tDir) {
                    if ($tDir != "." && $tDir != "..") {
                        $tDir = $dir . '/' . $tDir;
                        if (is_dir($tDir)) {
                            array_push($dirs, $tDir);
                        } else {
                            @unlink($tDir);
                        }
                    }
                }
            } else {
                @unlink($dir);
            }
        }
    }
    $dirTool = new Dir("");
    foreach ($dirs as $dir) {
        $dirTool->delDir($dir);
    }
}

/**
 * 保存数组变量到php文件
 * @param string $path 保存路径
 * @param mixed  $var  要保存的变量
 * @return boolean 保存成功返回true,否则false
 */
function cmf_save_var($path, $var)
{
    $result = file_put_contents($path, "<?php\treturn " . var_export($var, true) . ";?>");
    return $result;
}

/**
 * 设置动态配置
 * @param array $data <br>如：['template' => ['cmf_default_theme' => 'default']];
 * @return boolean
 */
function cmf_set_dynamic_config($data)
{

    if (!is_array($data)) {
        return false;
    }

    $configFile = CMF_ROOT . "data/conf/config.php";
    if (file_exists($configFile)) {
        $configs = include $configFile;
    } else {
        $configs = [];
    }

    $configs = array_replace_recursive($configs, $data);
    $result  = file_put_contents($configFile, "<?php\treturn " . var_export($configs, true) . ";");

    cmf_clear_cache();
    return $result;
}

/**
 * 转化格式化的字符串为数组
 * @param string $tag 要转化的字符串,格式如:"id:2;cid:1;order:post_date desc;"
 * @return array 转化后字符串<pre>
 *                    array(
 *                    'id'=>'2',
 *                    'cid'=>'1',
 *                    'order'=>'post_date desc'
 *                    )
 */
function cmf_param_lable($tag = '')
{
    $param = [];
    $array = explode(';', $tag);
    foreach ($array as $v) {
        $v = trim($v);
        if (!empty($v)) {
            list($key, $val) = explode(':', $v);
            $param[trim($key)] = trim($val);
        }
    }
    return $param;
}

/**
 * 获取后台管理设置的网站信息，此类信息一般用于前台
 * @return array
 */
function cmf_get_site_info()
{
    $siteInfo = cmf_get_option('site_info');

    if (isset($siteInfo['site_analytics'])) {
        $siteInfo['site_analytics'] = htmlspecialchars_decode($siteInfo['site_analytics']);
    }

    return $siteInfo;
}

/**
 * 获取CMF系统的设置，此类设置用于全局
 * @return array
 */
function cmf_get_cmf_setting()
{
    return cmf_get_option('cmf_setting');
}

/**
 * 更新CMF系统的设置，此类设置用于全局
 * @param $data
 * @return bool
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_set_cmf_setting($data)
{
    if (!is_array($data) || empty($data)) {
        return false;
    }

    return cmf_set_option('cmf_setting', $data);
}

/**
 * 设置系统配置，通用
 * @param string $key     配置键值,都小写
 * @param array  $data    配置值，数组
 * @param bool   $replace 是否完全替换
 * @return bool 是否成功
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_set_option($key, $data, $replace = false)
{
    if (!is_array($data) || empty($data) || !is_string($key) || empty($key)) {
        return false;
    }

    $key        = strtolower($key);
    $option     = [];
    $findOption = Db::name('option')->where('option_name', $key)->find();
    if ($findOption) {
        if (!$replace) {
            $oldOptionValue = json_decode($findOption['option_value'], true);
            if (!empty($oldOptionValue)) {
                $data = array_merge($oldOptionValue, $data);
            }
        }

        $option['option_value'] = json_encode($data);
        Db::name('option')->where('option_name', $key)->update($option);
//        echo Db::name('option')->getLastSql() . "\n";
    } else {
        $option['option_name']  = $key;
        $option['option_value'] = json_encode($data);
        Db::name('option')->insert($option);
    }

    cache('cmf_options_' . $key, null);//删除缓存

    return true;
}

/**
 * 获取系统配置，通用
 * @param string $key 配置键值,都小写
 * @return array
 */
function cmf_get_option($key)
{
    if (!is_string($key) || empty($key)) {
        return [];
    }

    static $cmfGetOption;

    if (empty($cmfGetOption)) {
        $cmfGetOption = [];
    } else {
        if (!empty($cmfGetOption[$key])) {
            return $cmfGetOption[$key];
        }
    }

    $optionValue = cache('cmf_options_' . $key);

    if (empty($optionValue)) {
        $optionValue = Db::name('option')->where('option_name', $key)->value('option_value');
        if (!empty($optionValue)) {
            $optionValue = json_decode($optionValue, true);

            cache('cmf_options_' . $key, $optionValue);
        }
    }

    $cmfGetOption[$key] = $optionValue;

    return $optionValue;
}

/**
 * 获取CMF上传配置
 */
function cmf_get_upload_setting()
{
    $uploadSetting = cmf_get_option('upload_setting');
    if (empty($uploadSetting) || empty($uploadSetting['file_types'])) {
        $uploadSetting = [
            'file_types' => [
                'image' => [
                    'upload_max_filesize' => '10240',//单位KB
                    'extensions'          => 'jpg,jpeg,png,gif,bmp4'
                ],
                'video' => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'mp4,avi,wmv,rm,rmvb,mkv'
                ],
                'audio' => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'mp3,wma,wav'
                ],
                'file'  => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar'
                ]
            ],
            'chunk_size' => 512,//单位KB
            'max_files'  => 20 //最大同时上传文件数
        ];
    }

    if (empty($uploadSetting['upload_max_filesize'])) {
        $uploadMaxFileSizeSetting = [];
        foreach ($uploadSetting['file_types'] as $setting) {
            $extensions = explode(',', trim($setting['extensions']));
            if (!empty($extensions)) {
                $uploadMaxFileSize = intval($setting['upload_max_filesize']) * 1024;//转化成B
                foreach ($extensions as $ext) {
                    if (!isset($uploadMaxFileSizeSetting[$ext]) || $uploadMaxFileSize > $uploadMaxFileSizeSetting[$ext]) {
                        $uploadMaxFileSizeSetting[$ext] = $uploadMaxFileSize;
                    }
                }
            }
        }

        $uploadSetting['upload_max_filesize'] = $uploadMaxFileSizeSetting;
    }

    return $uploadSetting;
}

/**
 * 获取html文本里的img
 * @param string $content html 内容
 * @return array 图片列表 数组item格式<pre>
 *                        [
 *                        "src"=>'图片链接',
 *                        "title"=>'图片标签的 title 属性',
 *                        "alt"=>'图片标签的 alt 属性'
 *                        ]
 *                        </pre>
 */
function cmf_get_content_images($content)
{
    //import('phpQuery.phpQuery', EXTEND_PATH);
    \phpQuery::newDocumentHTML($content);
    $pq         = pq(null);
    $images     = $pq->find("img");
    $imagesData = [];
    if ($images->length) {
        foreach ($images as $img) {
            $img            = pq($img);
            $image          = [];
            $image['src']   = $img->attr("src");
            $image['title'] = $img->attr("title");
            $image['alt']   = $img->attr("alt");
            array_push($imagesData, $image);
        }
    }
    \phpQuery::$documents = null;
    return $imagesData;
}

/**
 * 去除字符串中的指定字符
 * @param string $str   待处理字符串
 * @param string $chars 需去掉的特殊字符
 * @return string
 */
function cmf_strip_chars($str, $chars = '?<*.>\'\"')
{
    return preg_replace('/[' . $chars . ']/is', '', $str);
}

/**
 * 发送邮件
 * @param string $address 收件人邮箱
 * @param string $subject 邮件标题
 * @param string $message 邮件内容
 * @return array<br>
 *                        返回格式：<br>
 *                        array(<br>
 *                        "error"=>0|1,//0代表出错<br>
 *                        "message"=> "出错信息"<br>
 *                        );
 * @throws phpmailerException
 */
function cmf_send_email($address, $subject, $message)
{
    $smtpSetting = cmf_get_option('smtp_setting');
    $mail        = new \PHPMailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    $mail->IsHTML(true);
    //$mail->SMTPDebug = 3;
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet = 'UTF-8';
    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);
    // 设置邮件正文
    $mail->Body = $message;
    // 设置邮件头的From字段。
    $mail->From = $smtpSetting['from'];
    // 设置发件人名字
    $mail->FromName = $smtpSetting['from_name'];
    // 设置邮件标题
    $mail->Subject = $subject;
    // 设置SMTP服务器。
    $mail->Host = $smtpSetting['host'];
    //by Rainfer
    // 设置SMTPSecure。
    $Secure           = $smtpSetting['smtp_secure'];
    $mail->SMTPSecure = empty($Secure) ? '' : $Secure;
    // 设置SMTP服务器端口。
    $port       = $smtpSetting['port'];
    $mail->Port = empty($port) ? "25" : $port;
    // 设置为"需要验证"
    $mail->SMTPAuth    = true;
    $mail->SMTPAutoTLS = false;
    $mail->Timeout     = 10;
    // 设置用户名和密码。
    $mail->Username = $smtpSetting['username'];
    $mail->Password = $smtpSetting['password'];
    // 发送邮件。
    if (!$mail->Send()) {
        $mailError = $mail->ErrorInfo;
        return ["error" => 1, "message" => $mailError];
    } else {
        return ["error" => 0, "message" => "success"];
    }
}

/**
 * 转化数据库保存的文件路径，为可以访问的url
 * @param string $file
 * @param mixed  $style 图片样式,支持各大云存储
 * @return string
 */
function cmf_get_asset_url($file, $style = '')
{
    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return $file;
    } else {
//        $storage = cmf_get_option('storage');
//        if (empty($storage['type'])) {
//            $storage['type'] = 'Local';
//        }
//        if ($storage['type'] != 'Local') {
//            $watermark = cmf_get_plugin_config($storage['type']);
//            $style     = empty($style) ? $watermark['styles_watermark'] : $style;
//        }
        $storage = Storage::instance();
        return $storage->getUrl($file, $style);
    }
}

/**
 * 转化数据库保存图片的文件路径，为可以访问的url
 * @param string $file  文件路径，数据存储的文件相对路径
 * @param string $style 图片样式,支持各大云存储
 * @return string 图片链接
 */
function cmf_get_image_url($file, $style = 'watermark')
{
    if (empty($file)) {
        return '';
    }

    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return cmf_get_domain() . $file;
    } else {
//        $storage = cmf_get_option('storage');
//        if (empty($storage['type'])) {
//            $storage['type'] = 'Local';
//        }
//        if ($storage['type'] != 'Local') {
//            $watermark = cmf_get_plugin_config($storage['type']);
//            $style     = empty($style) ? $watermark['styles_watermark'] : $style;
//        }
        $storage = Storage::instance();
        return $storage->getImageUrl($file, $style);
    }
}

/**
 * 获取图片预览链接
 * @param string $file  文件路径，相对于upload
 * @param string $style 图片样式,支持各大云存储
 * @return string
 */
function cmf_get_image_preview_url($file, $style = 'watermark')
{
    if (empty($file)) {
        return '';
    }

    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return $file;
    } else {
//        $storage = cmf_get_option('storage');
//        if (empty($storage['type'])) {
//            $storage['type'] = 'Local';
//        }
//        if ($storage['type'] != 'Local') {
//            $watermark = cmf_get_plugin_config($storage['type']);
//            $style     = empty($style) ? $watermark['styles_watermark'] : $style;
//        }
        $storage = Storage::instance();
        return $storage->getPreviewUrl($file, $style);
    }
}

/**
 * 获取文件下载链接
 * @param string $file    文件路径，数据库里保存的相对路径
 * @param int    $expires 过期时间，单位 s
 * @return string 文件链接
 */
function cmf_get_file_download_url($file, $expires = 3600)
{
    if (empty($file)) {
        return '';
    }

    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return $file;
    } else {
        $storage = Storage::instance();
        return $storage->getFileDownloadUrl($file, $expires);
    }
}

/**
 * 解密用cmf_str_encode加密的字符串
 * @param        $string    要解密的字符串
 * @param string $key       加密时salt
 * @param int    $expiry    多少秒后过期
 * @param string $operation 操作,默认为DECODE
 * @return bool|string
 */
function cmf_str_decode($string, $key = '', $expiry = 0, $operation = 'DECODE')
{
    $ckey_length = 4;

    $key  = md5($key ? $key : config("database.authcode"));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey   = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string        = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box    = range(0, 255);

    $rndkey = [];
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result  .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

/**
 * 加密字符串
 * @param        $string 要加密的字符串
 * @param string $key    salt
 * @param int    $expiry 多少秒后过期
 * @return bool|string
 */
function cmf_str_encode($string, $key = '', $expiry = 0)
{
    return cmf_str_decode($string, $key, $expiry, "ENCODE");
}

/**
 * 获取文件相对路径
 * @param string $assetUrl 文件的URL
 * @return string
 */
function cmf_asset_relative_url($assetUrl)
{
    if (strpos($assetUrl, "http") === 0) {
        return $assetUrl;
    } else {
        return str_replace('/upload/', '', $assetUrl);
    }
}

/**
 * 检查用户对某个url内容的可访问性，用于记录如是否赞过，是否访问过等等;开发者可以自由控制，对于没有必要做的检查可以不做，以减少服务器压力
 * @param string  $object     访问对象的id,格式：不带前缀的表名+id;如post1表示xx_post表里id为1的记录;如果object为空，表示只检查对某个url访问的合法性
 * @param int     $countLimit 访问次数限制,如1，表示只能访问一次
 * @param boolean $ipLimit    ip限制,false为不限制，true为限制
 * @param int     $expire     距离上次访问的最小时间单位s，0表示不限制，大于0表示最后访问$expire秒后才可以访问
 * @return true 可访问，false不可访问
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_check_user_action($object = "", $countLimit = 1, $ipLimit = false, $expire = 0)
{
    $request = request();
    $action  = $request->module() . "/" . $request->controller() . "/" . $request->action();

    if (is_array($object)) {
        $userId = $object['user_id'];
        $object = $object['object'];
    } else {
        $userId = cmf_get_current_user_id();
    }

    $ip = get_client_ip(0, true);//修复ip获取

    $where = ["user_id" => $userId, "action" => $action, "object" => $object];

    if ($ipLimit) {
        $where['ip'] = $ip;
    }

    $findLog = Db::name('user_action_log')->where($where)->find();

    $time = time();
    if ($findLog) {
        Db::name('user_action_log')->where($where)->update([
            "count"           => Db::raw("count+1"),
            "last_visit_time" => $time,
            "ip"              => $ip
        ]);

        if ($findLog['count'] >= $countLimit) {
            return false;
        }

        if ($expire > 0 && ($time - $findLog['last_visit_time']) < $expire) {
            return false;
        }
    } else {
        Db::name('user_action_log')->insert([
            "user_id"         => $userId,
            "action"          => $action,
            "object"          => $object,
            "count"           => Db::raw("count+1"),
            "last_visit_time" => $time, "ip" => $ip
        ]);
    }

    return true;
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function cmf_is_mobile()
{
    static $cmf_is_mobile;

    if (isset($cmf_is_mobile))
        return $cmf_is_mobile;

    $cmf_is_mobile = request()->isMobile();
//    $cmf_is_mobile = Request::instance()->isMobile();

    return $cmf_is_mobile;
}

/**
 * 判断是否为微信访问
 * @return boolean
 */
function cmf_is_wechat()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

/**
 * 判断是否为Android访问
 * @return boolean
 */
function cmf_is_android()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
        return true;
    }
    return false;
}

/**
 * 判断是否为ios访问
 * @return boolean
 */
function cmf_is_ios()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        {
            return true;
        }
        return false;
    }
}

/**
 * 判断是否为iPhone访问
 * @return boolean
 */
function cmf_is_iphone()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
        {
            return true;
        }
        return false;
    }
}

/**
 * 判断是否为iPad访问
 * @return boolean
 */
function cmf_is_ipad()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        {
            return true;
        }
        return false;
    }
}

/**
 * 添加钩子
 * @param string $hook   钩子名称
 * @param mixed  $params 传入参数
 * @return void
 */
function hook($hook, &$params = null)
{
    return Hook::listen($hook, $params);
}

/**
 * 添加钩子,只执行一个
 * @param string $hook   钩子名称
 * @param mixed  $params 传入参数
 * @return mixed
 */
function hook_one($hook, &$params = null)
{
    return Hook::listen($hook, $params, true);
}

/**
 * 获取插件类名
 * @param string $name 插件名
 * @return string
 */
function cmf_get_plugin_class($name)
{
    $name      = ucwords($name);
    $pluginDir = cmf_parse_name($name);
    $class     = "plugins\\{$pluginDir}\\{$name}Plugin";
    return $class;
}

/**
 * 获取插件配置
 * @param string $name 插件名，大驼峰格式
 * @return array
 */
function cmf_get_plugin_config($name)
{
    $class = cmf_get_plugin_class($name);
    if (class_exists($class)) {
        $plugin = new $class();
        return $plugin->getConfig();
    } else {
        return [];
    }
}

/**
 * 替代scan_dir的方法
 * @param string $pattern 检索模式 搜索模式 *.txt,*.doc; (同glog方法)
 * @param int    $flags
 * @param        $pattern
 * @return array
 */
function cmf_scan_dir($pattern, $flags = null)
{
    $files = glob($pattern, $flags);
    if (empty($files)) {
        $files = [];
    } else {
        $files = array_map('basename', $files);
    }

    return $files;
}

/**
 * 获取某个目录下所有子目录
 * @param $dir
 * @return array
 */
function cmf_sub_dirs($dir)
{
    $dir     = ltrim($dir, "/");
    $dirs    = [];
    $subDirs = cmf_scan_dir("$dir/*", GLOB_ONLYDIR);
    if (!empty($subDirs)) {
        foreach ($subDirs as $subDir) {
            $subDir = "$dir/$subDir";
            array_push($dirs, $subDir);
            $subDirSubDirs = cmf_sub_dirs($subDir);
            if (!empty($subDirSubDirs)) {
                $dirs = array_merge($dirs, $subDirSubDirs);
            }
        }
    }

    return $dirs;
}

/**
 * 生成访问插件的url
 * @param string $url    url格式：插件名://控制器名/方法
 * @param array  $param  参数
 * @param bool   $domain 是否显示域名 或者直接传入域名
 * @return string
 */
function cmf_plugin_url($url, $param = [], $domain = false)
{
    $url              = parse_url($url);
    $case_insensitive = true;
    $plugin           = $case_insensitive ? Loader::parseName($url['scheme']) : $url['scheme'];
    $controller       = $case_insensitive ? Loader::parseName($url['host']) : $url['host'];
    $action           = trim($case_insensitive ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if (isset($url['query'])) {
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = [
        '_plugin'     => $plugin,
        '_controller' => $controller,
        '_action'     => $action,
    ];
    $params = array_merge($params, $param); //添加额外参数

    return url('\\cmf\\controller\\PluginController@index', $params, true, $domain);
}

/**
 * 检查权限
 * @param $userId   int        要检查权限的用户 ID
 * @param $name     string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
 * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @return boolean            通过验证返回true;失败返回false
 */
function cmf_auth_check($userId, $name = null, $relation = 'or')
{
    if (empty($userId)) {
        return false;
    }

    if ($userId == 1) {
        return true;
    }

    $authObj = new \cmf\lib\Auth();
    if (empty($name)) {
        $request    = request();
        $module     = $request->module();
        $controller = $request->controller();
        $action     = $request->action();
        $name       = strtolower($module . "/" . $controller . "/" . $action);
    }
    return $authObj->check($userId, $name, $relation);
}

function cmf_alpha_id($in, $to_num = false, $pad_up = 4, $passKey = null)
{
    $index = "aBcDeFgHiJkLmNoPqRsTuVwXyZAbCdEfGhIjKlMnOpQrStUvWxYz0123456789";
    if ($passKey !== null) {
        // Although this function's purpose is to just make the
        // ID short - and not so much secure,
        // with this patch by Simon Franz (http://blog.snaky.org/)
        // you can optionally supply a password to make it harder
        // to calculate the corresponding numeric ID

        for ($n = 0; $n < strlen($index); $n++) $i[] = substr($index, $n, 1);

        $passhash = hash('sha256', $passKey);
        $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

        for ($n = 0; $n < strlen($index); $n++) $p[] = substr($passhash, $n, 1);

        array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
    }

    $base = strlen($index);

    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = pow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) $out -= pow($base, $pad_up);
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) $in += pow($base, $pad_up);
        }

        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = pow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }

    return $out;
}

/**
 * 验证码检查，验证完后销毁验证码
 * @param string $value 要验证的字符串
 * @param string $id    验证码的ID
 * @param bool   $reset 验证成功后是否重置
 * @return bool
 */
function cmf_captcha_check($value, $id = "", $reset = true)
{
    $captcha        = new \think\captcha\Captcha();
    $captcha->reset = $reset;
    return $captcha->check($value, $id);
}

/**
 * 切分SQL文件成多个可以单独执行的sql语句
 * @param        $file            string sql文件路径
 * @param        $tablePre        string 表前缀
 * @param string $charset         字符集
 * @param string $defaultTablePre 默认表前缀
 * @param string $defaultCharset  默认字符集
 * @return array
 */
function cmf_split_sql($file, $tablePre, $charset = 'utf8mb4', $defaultTablePre = 'cmf_', $defaultCharset = 'utf8mb4')
{
    if (file_exists($file)) {
        //读取SQL文件
        $sql = file_get_contents($file);
        $sql = str_replace("\r", "\n", $sql);
        $sql = str_replace("BEGIN;\n", '', $sql);//兼容 navicat 导出的 insert 语句
        $sql = str_replace("COMMIT;\n", '', $sql);//兼容 navicat 导出的 insert 语句
        $sql = str_replace($defaultCharset, $charset, $sql);
        $sql = trim($sql);
        //替换表前缀
        $sql  = str_replace(" `{$defaultTablePre}", " `{$tablePre}", $sql);
        $sqls = explode(";\n", $sql);
        return $sqls;
    }

    return [];
}

/**
 * 判断当前的语言包，并返回语言包名
 * @return string  语言包名
 */
function cmf_current_lang()
{
    return request()->langset();
}

/**
 * 获取惟一订单号
 * @return string
 */
function cmf_get_order_sn()
{
    return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 获取文件扩展名
 * @param string $filename 文件名
 * @return string 文件扩展名
 */
function cmf_get_file_extension($filename)
{
    $pathinfo = pathinfo($filename);
    return strtolower($pathinfo['extension']);
}

/**
 * 检查手机或邮箱是否还可以发送验证码,并返回生成的验证码
 * @param string  $account 手机或邮箱
 * @param integer $length  验证码位数,支持4,6,8
 * @return string 数字验证码
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function cmf_get_verification_code($account, $length = 6)
{
    if (empty($account)) return false;
    $verificationCodeQuery = Db::name('verification_code');
    $currentTime           = time();
    $maxCount              = 5;
    $findVerificationCode  = $verificationCodeQuery->where('account', $account)->find();
    $result                = false;
    if (empty($findVerificationCode)) {
        $result = true;
    } else {
        $sendTime       = $findVerificationCode['send_time'];
        $todayStartTime = strtotime(date('Y-m-d', $currentTime));
        if ($sendTime < $todayStartTime) {
            $result = true;
        } else if ($findVerificationCode['count'] < $maxCount) {
            $result = true;
        }
    }

    if ($result) {
        switch ($length) {
            case 4:
                $result = rand(1000, 9999);
                break;
            case 6:
                $result = rand(100000, 999999);
                break;
            case 8:
                $result = rand(10000000, 99999999);
                break;
            default:
                $result = rand(100000, 999999);
        }
    }

    return $result;
}

/**
 * 更新手机或邮箱验证码发送日志
 * @param string $account    手机或邮箱
 * @param string $code       验证码
 * @param int    $expireTime 过期时间
 * @return int|string
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_verification_code_log($account, $code, $expireTime = 0)
{
    $currentTime = time();
    $expireTime  = $expireTime > $currentTime ? $expireTime : $currentTime + 30 * 60;

    $findVerificationCode = Db::name('verification_code')->where('account', $account)->find();

    if ($findVerificationCode) {
        $todayStartTime = strtotime(date("Y-m-d"));//当天0点
        if ($findVerificationCode['send_time'] <= $todayStartTime) {
            $count = 1;
        } else {
            $count = Db::raw('count+1');
        }
        $result = Db::name('verification_code')
            ->where('account', $account)
            ->update([
                'send_time'   => $currentTime,
                'expire_time' => $expireTime,
                'code'        => $code,
                'count'       => $count
            ]);
    } else {
        $result = Db::name('verification_code')
            ->insert([
                'account'     => $account,
                'send_time'   => $currentTime,
                'code'        => $code,
                'count'       => 1,
                'expire_time' => $expireTime
            ]);
    }

    return $result;
}

/**
 * 手机或邮箱验证码检查，验证完后销毁验证码增加安全性,返回true验证码正确，false验证码错误
 * @param string  $account 手机或邮箱
 * @param string  $code    验证码
 * @param boolean $clear   是否验证后销毁验证码
 * @return string  错误消息,空字符串代码验证码正确
 * @return string
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_check_verification_code($account, $code, $clear = false)
{

    $findVerificationCode = Db::name('verification_code')->where('account', $account)->find();
    if ($findVerificationCode) {
        if ($findVerificationCode['expire_time'] > time()) {

            if ($code == $findVerificationCode['code']) {
                if ($clear) {
                    Db::name('verification_code')->where('account', $account)->update(['code' => '']);
                }
            } else {
                return "验证码不正确!";
            }
        } else {
            return "验证码已经过期,请先获取验证码!";
        }

    } else {
        return "请先获取验证码!";
    }

    return "";
}

/**
 * 清除某个手机或邮箱的数字验证码,一般在验证码验证正确完成后
 * @param string $account 手机或邮箱
 * @return boolean true：手机验证码正确，false：手机验证码错误
 * @throws \think\Exception
 * @throws \think\exception\PDOException
 */
function cmf_clear_verification_code($account)
{
    $verificationCodeQuery = Db::name('verification_code');
    $result                = $verificationCodeQuery->where('account', $account)->update(['code' => '']);
    return $result;
}

/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename)
{
    if (is_file($filename)) {
        if (APP_DEBUG) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
 * 生成用户 token
 * @param $userId
 * @param $deviceType
 * @return string 用户 token
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_generate_user_token($userId, $deviceType)
{
    $userTokenQuery = Db::name("user_token")
        ->where('user_id', $userId)
        ->where('device_type', $deviceType);
    $findUserToken  = $userTokenQuery->find();
    $currentTime    = time();
    $expireTime     = $currentTime + 24 * 3600 * 180;
    $token          = md5(uniqid()) . md5(uniqid());
    if (empty($findUserToken)) {
        Db::name("user_token")->insert([
            'token'       => $token,
            'user_id'     => $userId,
            'expire_time' => $expireTime,
            'create_time' => $currentTime,
            'device_type' => $deviceType
        ]);
    } else {
        if ($findUserToken['expire_time'] > time() && !empty($findUserToken['token'])) {
            $token = $findUserToken['token'];
        } else {
            Db::name("user_token")
                ->where('user_id', $userId)
                ->where('device_type', $deviceType)
                ->update([
                    'token'       => $token,
                    'expire_time' => $expireTime,
                    'create_time' => $currentTime
                ]);
        }

    }

    return $token;
}

/**
 * 用 token 来查找用户
 * @param $token
 * @return string 用户信息 $user 或 false
 */
function cmf_generate_token_user($token,$field){
    $token_user = Db::name("user_token")->where('token', $token)->find();
    if (empty($token_user)){
        return false;
    }
    if ($token_user['expire_time'] < time()) {
        $token = cmf_generate_user_token($token_user['user_id'],'web');
    }
    $id = $token_user['user_id'];
//    $user =  Db::name("user")->field($field)->find($id);
    $user =  UserModel::field($field)->find($id);
    return $user;
}

/**
 * 查找相对应的微信小程序AppID和AppSecret
 * @param $app_key
 * @return array|mixed
 */
function cmf_WeChatAPP($app_key){
    $wxappSettings = cmf_get_option('wxapp_settings');
    $WeChatAPP = [];
    foreach ($wxappSettings["wxapps"] as $k=>$v){
        if ($v['app_key']==$app_key){
            $WeChatAPP = $v;
        }
    }
    return $WeChatAPP;
}

/**
 * 多图公共图片处理方法
 * 需要的参数 字符串连接的图片路径
 *   $image_url = 'company_bank/20200603/489bd0c4fb07f65a2d69aa2c51eb3a81.png,company_bank/20200603/489bd0c4fb07f65a2d69aa2c51eb3a81.png'
 * 返回数组格式
 *   $array = [
 *        "https://www.lsqspace.com/upload/company_bank/20200603/489bd0c4fb07f65a2d69aa2c51eb3a81.png"
 *   ]
 */
function cmf_get_image_url_more($image_url)
{
    $imagearr = array();
    $image_url = explode(',',$image_url);
    foreach ($image_url as $k=>$v){
        $imagearr[] = cmf_get_image_url($v);
    }
    return $imagearr;
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string  $name    字符串
 * @param integer $type    转换类型
 * @param bool    $ucfirst 首字母是否大写（驼峰规则）
 * @return string
 */
function cmf_parse_name($name, $type = 0, $ucfirst = true)
{
    return Loader::parseName($name, $type, $ucfirst);
}

/**
 * 判断字符串是否为已经序列化过
 * @param $str
 * @return bool
 */
function cmf_is_serialized($str)
{
    return ($str == serialize(false) || @unserialize($str) !== false);
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function cmf_is_ssl()
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}

/**
 * 获取CMF系统的设置，此类设置用于全局
 * @param string $key 设置key，为空时返回所有配置信息
 * @return array|bool|mixed
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function cmf_get_cmf_settings($key = "")
{
    $cmfSettings = cache("cmf_settings");
    if (empty($cmfSettings)) {
        $objOptions = new \app\admin\model\OptionModel();
        $objResult  = $objOptions->where("option_name", 'cmf_settings')->find();
        $arrOption  = $objResult ? $objResult->toArray() : [];
        if ($arrOption) {
            $cmfSettings = json_decode($arrOption['option_value'], true);
        } else {
            $cmfSettings = [];
        }
        cache("cmf_settings", $cmfSettings);
    }

    if (!empty($key)) {
        if (isset($cmfSettings[$key])) {
            return $cmfSettings[$key];
        } else {
            return false;
        }
    }
    return $cmfSettings;
}

/**
 * @deprecated
 * 判读是否sae环境
 * @return bool
 */
function cmf_is_sae()
{
    if (function_exists('saeAutoLoader')) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv  是否进行高级模式获取（有可能被伪装）
 * @return string
 */
function get_client_ip($type = 0, $adv = true)
{
    return request()->ip($type, $adv);
}

/**
 * 生成base64的url,用于数据库存放 url
 * @param $url    路由地址，如 控制器/方法名，应用/控制器/方法名
 * @param $params url参数
 * @return string
 */
function cmf_url_encode($url, $params)
{
    // 解析参数
    if (is_string($params)) {
        // aaa=1&bbb=2 转换成数组
        parse_str($params, $params);
    }

    return base64_encode(json_encode(['action' => $url, 'param' => $params]));
}

/**
 * CMF Url生成
 * @param string       $url    路由地址
 * @param string|array $vars   变量
 * @param bool|string  $suffix 生成的URL后缀
 * @param bool|string  $domain 域名
 * @return string
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function cmf_url($url = '', $vars = '', $suffix = true, $domain = false)
{
    global $CMF_GV_routes;

    if (empty($CMF_GV_routes)) {
        $routeModel    = new \app\admin\model\RouteModel();
        $CMF_GV_routes = $routeModel->getRoutes();
    }

    if (false === strpos($url, '://') && 0 !== strpos($url, '/')) {
        $info = parse_url($url);
        $url  = !empty($info['path']) ? $info['path'] : '';
        if (isset($info['fragment'])) {
            // 解析锚点
            $anchor = $info['fragment'];
            if (false !== strpos($anchor, '?')) {
                // 解析参数
                list($anchor, $info['query']) = explode('?', $anchor, 2);
            }
            if (false !== strpos($anchor, '@')) {
                // 解析域名
                list($anchor, $domain) = explode('@', $anchor, 2);
            }
        } elseif (strpos($url, '@') && false === strpos($url, '\\')) {
            // 解析域名
            list($url, $domain) = explode('@', $url, 2);
        }
    }

    // 解析参数
    if (is_string($vars)) {
        // aaa=1&bbb=2 转换成数组
        parse_str($vars, $vars);
    }

    if (isset($info['query'])) {
        // 解析地址里面参数 合并到vars
        parse_str($info['query'], $params);
        $vars = array_merge($params, $vars);
    }

    if (!empty($vars) && !empty($CMF_GV_routes[$url])) {

        foreach ($CMF_GV_routes[$url] as $actionRoute) {
            $sameVars = array_intersect_assoc($vars, $actionRoute['vars']);

            if (count($sameVars) == count($actionRoute['vars'])) {
                ksort($sameVars);
                $url  = $url . '?' . http_build_query($sameVars);
                $vars = array_diff_assoc($vars, $sameVars);
                break;
            }
        }
    }

    if (!empty($anchor)) {
        $url = $url . '#' . $anchor;
    }

//    if (!empty($domain)) {
//        $url = $url . '@' . $domain;
//    }

    return Url::build($url, $vars, $suffix, $domain);
}

/**
 * 判断 cmf 核心是否安装
 * @return bool
 */
function cmf_is_installed()
{
    static $cmfIsInstalled;
    if (empty($cmfIsInstalled)) {
        $cmfIsInstalled = file_exists(CMF_ROOT . 'data/install.lock');
    }
    return $cmfIsInstalled;
}

/**
 * 替换编辑器内容中的文件地址
 * @param string  $content     编辑器内容
 * @param boolean $isForDbSave true:表示把绝对地址换成相对地址,用于数据库保存,false:表示把相对地址换成绝对地址用于界面显示
 * @return string
 */
function cmf_replace_content_file_url($content, $isForDbSave = false)
{
    //import('phpQuery.phpQuery', EXTEND_PATH);
    \phpQuery::newDocumentHTML($content);
    $pq = pq(null);

    $storage       = Storage::instance();
    $localStorage  = new cmf\lib\storage\Local([]);
    $storageDomain = $storage->getDomain();
    $domain        = request()->host();

    $images = $pq->find("img");
    if ($images->length) {
        foreach ($images as $img) {
            $img    = pq($img);
            $imgSrc = $img->attr("src");

            if ($isForDbSave) {
                if (preg_match("/^\/upload\//", $imgSrc)) {
                    $img->attr("src", preg_replace("/^\/upload\//", '', $imgSrc));
                } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $imgSrc)) {
                    $img->attr("src", $localStorage->getFilePath($imgSrc));
                } elseif (preg_match("/^http(s)?:\/\/$storageDomain\//", $imgSrc)) {
                    $img->attr("src", $storage->getFilePath($imgSrc));
                }

            } else {
                $img->attr("src", cmf_get_image_url($imgSrc));
            }

        }
    }

    $links = $pq->find("a");
    if ($links->length) {
        foreach ($links as $link) {
            $link = pq($link);
            $href = $link->attr("href");

            if ($isForDbSave) {
                if (preg_match("/^\/upload\//", $href)) {
                    $link->attr("href", preg_replace("/^\/upload\//", '', $href));
                } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $href)) {
                    $link->attr("href", $localStorage->getFilePath($href));
                } elseif (preg_match("/^http(s)?:\/\/$storageDomain\//", $href)) {
                    $link->attr("href", $storage->getFilePath($href));
                }

            } else {
                if (!(preg_match("/^\//", $href) || preg_match("/^http/", $href))) {
                    $link->attr("href", cmf_get_file_download_url($href));
                }

            }

        }
    }

    $content = $pq->html();

    \phpQuery::$documents = null;


    return $content;

}

/**
 * 获取后台风格名称
 * @return string
 */
function cmf_get_admin_style()
{
    $adminSettings = cmf_get_option('admin_settings');
    return empty($adminSettings['admin_style']) ? 'simpleadmin' : $adminSettings['admin_style'];
}

/**
 * curl get 请求
 * @param $url
 * @return mixed
 */
function cmf_curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    if ($SSL) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    }
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

/**
 * 用户操作记录
 * @param string $action 用户操作
 * @throws \think\Exception
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @throws \think\exception\PDOException
 */
function cmf_user_action($action)
{
    $userId = cmf_get_current_user_id();

    if (empty($userId)) {
        return;
    }

    $findUserAction = Db::name('user_action')->where('action', $action)->find();

    if (empty($findUserAction)) {
        return;
    }

    $changeScore = false;

    if ($findUserAction['cycle_type'] == 0) {
        $changeScore = true;
    } elseif ($findUserAction['reward_number'] > 0) {
        $findUserScoreLog = Db::name('user_score_log')->order('create_time DESC')->find();
        if (!empty($findUserScoreLog)) {
            $cycleType = intval($findUserAction['cycle_type']);
            $cycleTime = intval($findUserAction['cycle_time']);
            switch ($cycleType) {//1:按天;2:按小时;3:永久
                case 1:
                    $firstDayStartTime = strtotime(date('Y-m-d', $findUserScoreLog['create_time']));
                    $endDayEndTime     = strtotime(date('Y-m-d', strtotime("+{$cycleTime} day", $firstDayStartTime)));
//                    $todayStartTime        = strtotime(date('Y-m-d'));
//                    $todayEndTime          = strtotime(date('Y-m-d', strtotime('+1 day')));
                    $findUserScoreLogCount = Db::name('user_score_log')->where([
                        'user_id'     => $userId,
                        'create_time' => [['gt', $firstDayStartTime], ['lt', $endDayEndTime]]
                    ])->count();
                    if ($findUserScoreLogCount < $findUserAction['reward_number']) {
                        $changeScore = true;
                    }
                    break;
                case 2:
                    if (($findUserScoreLog['create_time'] + $cycleTime * 3600) < time()) {
                        $changeScore = true;
                    }
                    break;
                case 3:

                    break;
            }
        } else {
            $changeScore = true;
        }
    }

    if ($changeScore) {
        Db::name('user_score_log')->insert([
            'user_id'     => $userId,
            'create_time' => time(),
            'action'      => $action,
            'score'       => $findUserAction['score'],
            'coin'        => $findUserAction['coin'],
        ]);

        $data = [];
        if ($findUserAction['score'] > 0) {
            $data['score'] = Db::raw('score+' . $findUserAction['score']);
        }

        if ($findUserAction['score'] < 0) {
            $data['score'] = Db::raw('score-' . abs($findUserAction['score']));
        }

        if ($findUserAction['coin'] > 0) {
            $data['coin'] = Db::raw('coin+' . $findUserAction['coin']);
        }

        if ($findUserAction['coin'] < 0) {
            $data['coin'] = Db::raw('coin-' . abs($findUserAction['coin']));
        }

        Db::name('user')->where('id', $userId)->update($data);

    }


}

function cmf_api_request($url, $params = [])
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:1314/api/' . $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);

    $token = session('token');

    curl_setopt($curl, CURLOPT_HTTPHEADER, ["XX-Token: $token"]);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据

    return json_decode($data, true);
}

/**
 * 判断是否允许开放注册
 */
function cmf_is_open_registration()
{

    $cmfSettings = cmf_get_option('cmf_settings');

    return empty($cmfSettings['open_registration']) ? false : true;
}

/**
 * XML编码
 * @param mixed  $data     数据
 * @param string $root     根节点名
 * @param string $item     数字索引的子节点名
 * @param string $attr     根节点属性
 * @param string $id       数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function cmf_xml_encode($data, $root = 'think', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
{
    if (is_array($attr)) {
        $_attr = [];
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr = trim($attr);
    $attr = empty($attr) ? '' : " {$attr}";
    $xml  = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml  .= "<{$root}{$attr}>";
    $xml  .= cmf_data_to_xml($data, $item, $id);
    $xml  .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function cmf_data_to_xml($data, $item = 'item', $id = 'id')
{
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if (is_numeric($key)) {
            $id && $attr = " {$id}=\"{$key}\"";
            $key = $item;
        }
        $xml .= "<{$key}{$attr}>";
        $xml .= (is_array($val) || is_object($val)) ? cmf_data_to_xml($val, $item, $id) : $val;
        $xml .= "</{$key}>";
    }
    return $xml;
}

/**
 * 检查手机格式，中国手机不带国家代码，国际手机号格式为：国家代码-手机号
 * @param $mobile
 * @return bool
 */
function cmf_check_mobile($mobile)
{
    if (preg_match('/(^(13\d|14\d|15\d|16\d|17\d|18\d|19\d)\d{8})$/', $mobile)) {
        return true;
    } else {
        if (preg_match('/^\d{1,4}-\d{5,11}$/', $mobile)) {
            if (preg_match('/^\d{1,4}-0+/', $mobile)) {
                //不能以0开头
                return false;
            }

            return true;
        }

        return false;
    }
}

/**
 * 文件大小格式化
 * @param $bytes 文件大小（字节 Byte)
 * @return string
 */
function cmf_file_size_format($bytes)
{
    $type = ['B', 'KB', 'MB', 'GB', 'TB'];
    for ($i = 0; $bytes >= 1024; $i++)//单位每增大1024，则单位数组向后移动一位表示相应的单位
    {
        $bytes /= 1024;
    }
    return (floor($bytes * 100) / 100) . $type[$i];//floor是取整函数，为了防止出现一串的小数，这里取了两位小数
}

/**
 * 计数器增加
 * @param     $name 计数器英文标识
 * @param int $min  计数器最小值
 * @param int $step 增加步长
 * @return mixed
 */
function cmf_counter_inc($name, $min = 1, $step = 1)
{
    $id = cache('core_counter_' . $name);
    if (empty($id)) {
        $id = Db::name('core_counter')->where('name', $name)->value('id');

        if (empty($id)) {
            $id = Db::name('core_counter')->insertGetId([
                'name'  => $name,
                'value' => 0
            ]);
        }
        cache('core_counter_' . $name, $id);
    }

    Db::startTrans();
    try {
        $value = Db::name('core_counter')->where('id', $id)->lock(true)->value('value');

        if ($min > $value) {
            $value = $min;
        } else {
            $value += $step;
        }

        Db::name('core_counter')->where('id', $id)->update(['value' => $value]);

        Db::commit();
    } catch (\Exception $e) {
        Db::rollback();
        $value = false;
    }

    return $value;
}

/**
 * 获取ThinkPHP版本
 * @return string
 */
function cmf_thinkphp_version()
{
    return THINK_VERSION;
}

/**
 * 获取ThinkCMF版本
 * @return string
 */
function cmf_version()
{
    return THINKCMF_VERSION;
}

/**
 * 获取ThinkCMF核心包目录
 */
function cmf_core_path()
{
    return __DIR__ . DIRECTORY_SEPARATOR;
}

/**
 * 微信通知接口
 */
function wxinform($user_id,$data,$conf)
{
    $user = UserModel::field('wxgzh_openid,push_message_num')->where('id',$user_id)->find();
    //拼接请求数组
    $openid   = $user['wxgzh_openid'];
    $keyword1 = $data['keyword1'];
    $keyword2 = date('Y-m-d H:i:s');
    $keyword3 = $data['item'];
    $keyword4 = $data['num'];
    $url      = $data['url'];

    $remark = $data['remark']."\n\n".'若您不想接收消息请前往 "我的" 中 "提醒设置", "接收新商机通知" 选择 "关闭" 谢谢!';

    //跳转小程序还是网页端 --- 小程序页码判断
    $pagepath = '';
    $appid    = '';
//    if (empty($data['page'])){
//        $pagepath = '';
//        $appid    = '';
//    }else{
//        $pagepath = $data['page'];         // todo 替换 跳转小程序 页码
//        $appid    = $data['appid'];       // todo 替换 跳转小程序appid
//    }
    $template = 'oTe0wdgb-Pr264vjbvbQXA_7I77YqzchwyMq7VZaAnM'; // todo 替换 客户跟进提醒 模板ID

    //获取网站信息
    $ds = cmf_get_site_info();
    // todo 替换 客户跟进提醒 模板ID 的数据结构
    //微信发送消息模板
    $arr = [
        'first' => ['value' => $ds['site_name'].' - '.$keyword3, 'color' => '#173177'],
        'keyword1' => ['value' => $keyword1, 'color' => '#173177'],
        'keyword2' => ['value' => $keyword2, 'color' => '#173177'],
        'keyword3' => ['value' => $keyword3, 'color' => '#173177'],
        'keyword4' => ['value' => $keyword4, 'color' => '#173177'],
        'remark' => ['value' => $remark, 'color' => '#696969'],
    ];
    $s = new WeChatClass();
//    $msg = $s->sendMsg($conf,$openid, $template, $arr,$miniprogram=['appid'=>$appid,'pagepath' =>$pagepath], $url);
    $msg = $s->sendMsg($conf,$openid, $template, $arr,$miniprogram=['appid'=>$appid,'path' =>$pagepath], $url);
    $resmsg = json_decode($msg,true);
    return $resmsg;
}

//递归数据
function cmf_get_tree($data, $pId)
{
    $tree = array();
    foreach($data as $k => $v)
    {
        if($v['parent_id'] == $pId)
        {
            //父亲找到儿子
            $tmp = cmf_get_tree($data, $v['id']);
            if($tmp){
                $v['sub_button'] = $tmp;
            }
            $tree[] = $v;
        }
    }
    $tree = to_array($tree);
    return $tree;
}

/**
 * 对象转换成数组
 * @param $obj
 */
function to_array($obj)
{
    return json_decode(json_encode($obj), true);
}

/**
 * 分支树显示无限分类
 * @param $arr  需要处理的数组
 * @param $key  id名称
 * @param $pkey 父类id名称
 * @param $pid  父id
 * @return $list 返回的数组
 */
function cmf_recursion_tree($arr = [], $key = 'id', $pkey = 'parent_id', $pid = 0)
{
    $list = array();
    foreach($arr as $val){
        if($val[$pkey] == $pid){
            $tmp = cmf_recursion_tree($arr,$key,$pkey,$val[$key]);
            if($tmp){
                $val['child'] = $tmp;
            }
            $list[] = $val;
        }
    }
    return $list;
}

function cmf_check_out_trade_no()
{
    $out_trade_no = date('YmdHis').mt_rand(1000,9999);
    $checkres = BillLogModel::get(['order_number'=>$out_trade_no]);
    if ($checkres){
        $out_trade_no = cmf_check_out_trade_no();
    }
    return $out_trade_no;
}

/**
 * 产生随机字符串
 * @param int $length 指定字符长度
 * @param string $str 字符串前缀
 * @return string
 */
function cmf_create_noncestr($length = 32, $str = "")
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * 分支树显示无限分类
 * @param $arr  需要处理的数组
 * @param $key  id名称
 * @param $pkey 父类id名称
 * @param $pid  父id
 * @return $list 返回的数组
 */
function cmf_cat_tree_ids($arr = [], $pid = 0)
{
    $list = array();
    $ids = array();
    foreach($arr as $val){
        if($val['parent_id'] == $pid){
            $tmp = cmf_cat_tree_ids($arr,$val['id']);
            if (count($tmp)>0){
                foreach ($tmp as $k=>$v){
                    $list[] = $v;
                }
            }
            $list[] = $val['id'];
        }
    }
    return $list;
}



/**
 * a.合成图片信息 复制一张图片的矩形区域到另外一张图片的矩形区域
 * @param [type] $bg_image   [目标图]
 * @param [type] $sub_image  [被添加图]
 * @param [type] $add_x      [目标图x坐标位置]
 * @param [type] $add_y      [目标图y坐标位置]
 * @param [type] $src_x      [添加图x坐标位置]
 * @param [type] $src_y      [添加图y坐标位置]
 * @param [type] $src_w      [添加图宽度区域]
 * @param [type] $src_h      [添加图高度区域]
 * @param [type] $out_image  [输出图路径]
 * @param [type] $pct        [添加图透明度]
 * @return [type] $out_image [图片合成成功返回新图片路径,否则返回空]
 */
function cmf_image_copy_image($bg_image,$sub_image,$add_x,$add_y,$src_x,$src_y,$src_w,$src_h,$out_image,$pct=100){
    if($sub_image){
        $bg_image_c = imagecreatefromstring(file_get_contents($bg_image));
        $sub_image_c = imagecreatefromstring(file_get_contents($sub_image));
        imagecopymerge ($bg_image_c, $sub_image_c, $add_x, $add_y, $src_x, $src_y, $src_w, $src_h, $pct);
        //保存到out_image
        $res = imagejpeg($bg_image_c, $out_image, 80);
        imagedestroy($sub_image_c);
        imagedestroy($bg_image_c);
        if ($res) {
            return $out_image;
        }else{
            return '';
        }
    }
}
/**
 * b.生成文字图片并插入广告图中
 * @param [type] $filename [背景路径]
 * @param [type] $text     [文字内容]
 * @param [type] $font     [文字大小]
 * @param [type] $size     [文字画布的宽]
 * @param [type] $width_f  [预设宽度]
 * @param [type] $red      [红]
 * @param [type] $grn      [绿]
 * @param [type] $blu      [蓝]
 */
function cmf_create_text($filename,$text,$font,$size,$width_f,$red,$grn,$blu){
    $rot = 0; // 旋转角度
    $width = 0; //宽度
    $height = 0; //高度
    $offset_x = 0; //x偏移
    $offset_y = 0; //y偏移
    $bounds = array();
    $text = cmf_autowrap($size, 0, $font, $text,$width_f); // 自动换行处理
    /** [字体大小] [角度] [字体名称] [字符串] [预设宽度] */
    // 确定边框高度.
    $bounds = ImageTTFBBox($size, $rot, $font, "W");
    if ($rot < 0) {
        $font_height = abs($bounds[7]-$bounds[1]);
    } else if ($rot > 0) {
        $font_height = abs($bounds[1]-$bounds[7]);
    } else {
        $font_height = abs($bounds[7]-$bounds[1]);
    }
    // 确定边框高度.
    $bounds = ImageTTFBBox($size, $rot, $font, $text);
    if ($rot < 0) {
        $width = abs($bounds[4]-$bounds[0]);
        $height = abs($bounds[3]-$bounds[7]);
        $offset_y = $font_height;
        $offset_x = 0;
    } else if ($rot > 0) {
        $width = abs($bounds[2]-$bounds[6]);
        $height = abs($bounds[1]-$bounds[5]);
        $offset_y = abs($bounds[7]-$bounds[5])+$font_height;
        $offset_x = abs($bounds[0]-$bounds[6]);
    } else {
        $width = abs($bounds[4]-$bounds[6]);
        $height = abs($bounds[7]-$bounds[1]);
        $offset_y = $font_height;
        $offset_x = 0;
    }
    $bg = imagecreatetruecolor($width + 20,$height + 20); // 创建画布
    $color=imagecolorallocatealpha($bg , 0 , 0 , 0 ,127);//拾取一个完全透明的颜色
    imagealphablending($bg ,false);//关闭混合模式，以便透明颜色能覆盖原画布
    imagefill($bg , 0 , 0, $color);//填充
    imagesavealpha($bg ,true);//设置保存PNG时保留透明通道信息
    $textImg = imagecolorallocate($bg, $red, $grn, $blu); // 创建白色
    ImageTTFText($bg, $size, 0, 10, $size + 10, $textImg, $font, $text);
    // imagestring ( $bg, $font, $x, $y, $text, $col );
    imagepng($bg,$filename);
}
/**
 * 文字自动换行
 * @param [type] $fontsize [字体大小]
 * @param [type] $angle    [角度]
 * @param [type] $fontface [字体名称]
 * @param [type] $string   [字符串]
 * @param [type] $width    [预设宽度]
 */
function cmf_autowrap($fontsize, $angle, $fontface, $string, $width) {
    $content = "";
    // 将字符串拆分成一个个单字 保存到数组 letter 中
    preg_match_all("/./u", $string, $arr);
    $letter = $arr[0];
    foreach ($letter as $l) {
        $teststr = $content." ".$l;
        $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
        // 判断拼接后的字符串是否超过预设的宽度
        if (($testbox[2] > $width) && ($content !== "")) {
            $content .= PHP_EOL;
        }
        $content .= $l;
    }
    return $content;
}

