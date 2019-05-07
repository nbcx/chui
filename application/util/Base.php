<?php
namespace util;

use bin\Config;

/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/18 下午5:04
 */
class Base extends \nb\Controller {

    /**
     * 设置布局
     * @param $name
     * @param string $replace
     */
    protected function layout($name, $replace = '') {
        $this->view->layout($name,$replace);
    }

    /**
     * 将数组转为json输出，并结束运行
     * @param $content
     * @throws \Exception
     */
    protected function json($content) {
        if(is_array($content)) {
            quit(json_encode($content,JSON_UNESCAPED_UNICODE));
        }
        quit($content);
    }

    /**
     *  验证是否登陆，没登陆将定向到登陆界面
     */
    protected function authLogin() {
        if(Auth::init()->notLogin) {
            redirect(Config::$o->login);
        }
    }

    protected function ___isPjax() {
        if(isset($_SERVER['HTTP_X_PJAX'])) {
            return true;
        }
        return false;
    }

    /**
     * 获取对象插件句柄
     *
     * @access public
     * @param string $handle 句柄
     * @return \nb\Hook
     */
    protected function hook($handle = NULL) {
        return \nb\Hook::pos(empty($handle) ? get_class($this) : $handle);
    }

    //获取安全地址
    protected function safe($path, $prefix='') {
        return Security::url($path, $prefix);
    }

    //验证地址是否安全
    protected function protect($func=null) {
        $func or $func = function (){
            tips('非法请求');
        };
        Security::protect($func);
    }

    protected function displugin() {
    }

    protected function assignSafe($var,$url, $prefix='') {
        return $this->assign($var,$this->safe($url,$prefix));
    }


    public function __after($ser=null) {
        if(!($ser  instanceof \nb\Service)) {
            return;
        }
        if($ser->code) {
            $this->fail($ser->code,$ser->msg);
        }
        $this->success($ser->msg?:'操作成功',is_array($ser->data)?$ser->data:[]);
        return;
        switch ($ser->code) {
            case 0:
                //api&ajax 显示成功信息
                //or 跳转
                break;
            case 301:
                //api&ajax 显示data
                //or 跳转URL页面
                break;
            default:
                //api&ajax 显示错误信息
                //or 跳转错误页面
                break;
        }
    }
}