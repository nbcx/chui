<?php
/**
 * !add
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/8 上午10:20
 */
namespace model;

use bin\Config;
use nb\Request;
use nb\Pool;
use nb\Model;

class System extends Model {

    protected static function __config() {
        return ['system sys', 'name'];
    }

    public static function init(){
        if($conf = Pool::get(get_class())) {
            return $conf;
        }
        $conf = self::dao()->kv('name,value');
        return Pool::value(
            get_class(),
            $conf
        );
    }


    protected function _mail() {
        return unserialize($this->_row['mail']);
    }

    protected function _route() {
        return unserialize($this->_row['route']);
    }

    /**
     * 站点地址
     */
    protected function _url() {
        return Request::driver()->domain;
    }


    /**
     * 登陆地址
     * @return string
     */
    protected function _loginUrl() {
        return '/login';
    }

    /**
     * 登陆地址
     * @return string
     */
    protected function _logoutUrl() {
        return '/login/out';
    }


    protected function _theme() {
        return Theme::id($this->themes);
    }

    /**
     * 插件父目录URL
     * @return string
     */
    protected function _pluginUrl() {
        return '/plugin/';
    }

    /**
     * 发送私信接口地址
     */
    protected function _letterUrl() {
        return '/message/send';
    }

    /**
     * 当前系统时间戳
     * @return int
     */
    protected function _timestamp() {
        return intval(Request::driver()->requestTime);
    }

    /**
     * 验证码接口地址
     * @return string
     */
    protected function _captchaUrl() {
        return '/action/captcha';
    }

    /**
     * 注册地址
     * @return string
     */
    protected function _registerUrl() {
        return '/register';
    }

    /**
     * 重置密码地址
     */
    protected function _resetpwPostUrl() {
        return '/resetpwd';
    }

    /**
     * 节点主页地址
     */
    protected function _nodeUrl() {
        return '/node';
    }

    /**
     * 上传文件的根路径URL
     * @return string
     */
    protected function _uploadUrl() {
        return '/uploads/';
    }


    /**
     * 发帖地址
     * @return string
     */
    protected function _postedPost() {
        return '/posted/post';
    }


}