<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace cmf\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Config;

class ApiBaseController extends Controller
{

    protected $userInfo = [];
    /**
     * 构造函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        parent::__construct();

        if (is_null($request)) {
            $request = Request::instance();
        }

        $this->request = $request;

        //$this->_initializeView();
        //$this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));


        // 控制器初始化
        $this->_initialize();

        // 前置操作方法
        if ($this->beforeActionList) {
            foreach ($this->beforeActionList as $method => $options) {
                is_numeric($method) ?
                    $this->beforeAction($options) :
                    $this->beforeAction($method, $options);
            }
        }
    }


    /**
     *  检查后台用户访问权限
     * @param int $userId 后台用户id
     * @return boolean 检查通过返回true
     */
    protected function checkAccess($userId)
    {
        // 如果用户id是1，则无需判断
        if ($userId == 1) {
            return true;
        }

        $module     = $this->request->module();
        $controller = $this->request->controller();
        $action     = $this->request->action();
        $rule       = $module . $controller . $action;

        $notRequire = ["adminIndexindex", "adminMainindex"];
        if (!in_array($rule, $notRequire)) {
            return cmf_auth_check($userId);
        } else {
            return true;
        }
    }

    /**
     * 返回统一json数据
     * @param int $code
     * @param string $msg
     * @param array $data
     * @param string $type
     * @param array $header
     */
    public function result($code = 0, $msg = '', $data=[], $type = '', array $header = [])
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
            'time' => Request::instance()->server('REQUEST_TIME'),
        ];
        die(json_encode($result));
    }

}