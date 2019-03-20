<?php
/**
 * 插件处理类
 *
 * @category typecho
 * @package Plugin
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
namespace model;

use util\Auth;
use nb\Collection;
use nb\Model;

class Plugin extends Model {

    protected static function __config() {
        return ['plugin p', 'folder'];
    }

    /**
     * 获取非全局插件，此函数不会获取未安装的插件
     *
     * @param int $uid
     *
     *  如果$uid为0，则获取所有非全局插件
     *
     *  如果$uid不为0，
     *  $activate为null，获取指定用户所有的可用插件
     *  $activate为0，获取指定用户所有的可用且未激活的插件
     *  $activate为1，获取指定用户所有的可用且激活的插件
     *
     * @param null $activate
     * @return mixed
     */
    public static function personal($uid=0,$activate=null) {
        $db = self::dao()->driver;
        if($uid) {
            $db->left('user_plugin up','up.pname=p.folder');
            $db->where('up.uid=?',$uid);

            $activate !== null and $db->where('up.activate=?',$activate);
        }
        else {
            $db->where('overall=0');
        }
        return $db->fetchAll();
    }

    /**
     * 获取用户已经启用的插件handle
     * @param Auth $auth
     */
    public static function handle($auth) {

        $db = self::dao(false)->field('handle');

        //$uid = $auth->id;
        if($auth->id) { //$auth instanceof Auth &&
            $db->left('user_plugin up','up.pname=p.folder');
            $db->where('((up.uid=? and up.activate=1) or p.overall=1) and p.activate=1',$auth->id);
        }
        else {
            $db->where('activate=? and overall=1',1);
        }

        $handles = $db->fetchAll();
        $hand = [];
        foreach ($handles as &$v) {
            $v= json_decode($v['handle'],true);
            $hand = array_merge($hand,$v);
        }
        return $hand;
    }

    public static function id($id) {
        $info = self::dao(false)->findId($id);
        if($info) {
            $info['isInstall'] = true;
        }
        else {
            $info['folder'] = $id;
            $info['isInstall'] = false;
        }
        return new self($info);
    }


    /**
     * 获取已经启用的插件列表
     * @param int $uid
     * @return Plugin
     */
    public static function enable($uid=0) {
        if(!$uid) {
            list($activate,$unactivate,$uninstall) = self::resolve();
            return new self($activate);
        }
    }

    /**
     * 获取没有启用的插件列表
     * @param int $uid
     * @param bool $needInstall
     * @return Plugin
     */
    public static function unenable($uid=0,$needInstall=true) {
        list($activate,$unactivate,$uninstall) = self::resolve();
        if($needInstall) {
            return new self(array_merge(
                $unactivate,
                $uninstall
            ));
        }
        return new self($unactivate);
    }


    /**
     * 获取所有插件
     * @return Plugin
     */
    public static function all() {
        list($activate,$unactivate,$uninstall) = self::resolve();
        return new self(array_merge(
            $activate,
            $unactivate,
            $uninstall
        ));
    }

    /**
     * 分类各种插件
     * @return array
     */
    protected static function resolve() {

        //已经安装过的插件
        $install = self::dao(false)->kv('folder,*');

        //所有主题
        $plugin = glob(__APP__.'plugin/*/');

        $activate = [];//已经激活
        $unactivate = [];//未激活
        $uninstall = [];//未安装
        foreach ($plugin as $v) {
            $temp = explode('/',$v);
            $v = $temp[count($temp)-2];

            $info = ['folder'=>$v];

            if(isset($install[$v])) {
                $conf = $install[$v];
                $info = array_merge($conf,$info);
                $info['isInstall'] = true;
                $info['activate']?$activate[] = $info : $unactivate[] = $info;
            }
            else {
                $info['activate'] = 0;
                $info['isInstall'] = false;
                $uninstall[] = $info;
            }
        }
        return [$activate,$unactivate,$uninstall];
    }


    /**
     * 插件信息
     * @return Collection
     */
    public function _info() {
        $json = __APP__.'plugin/'.$this->folder.'/'.'config.json';
        $info = [];
        if(is_file($json)) {
            $info = file_get_contents($json);
            $info = json_decode($info,true);
        }
        return new Collection($info);
    }

    /**
     * 插件是否存在
     * @return bool
     */
    public function _isHave() {
        $json = __APP__.'plugin/'.$this->folder.'/'.'config.json';
        if(is_file($json)) {
            return true;
        }
        return false;
    }

    /**
     * 插件是否不存在
     * @return bool
     */
    public function _isEmpty() {
        return !$this->isHave;
    }

    public function _isActivate() {
        return $this->activate==1;
    }

    /**
     * 插件是否没有激活
     * @return bool
     */
    public function _isDeactivate() {
        return !$this->isActivate;
    }

    /**
     * 获取插件安装前的确认配置
     * @return null|string
     */
    public function _installConf() {
        $install = __APP__.'plugin/'.$this->folder.'/conf/'.'install.php';
        if(is_file($install)) {
            return $install;
        }
        return null;
    }

    /**
     * 获取插件卸载前的确认配置
     * @return null|string
     */
    public function _uninstallConf() {
        $uninstall = __APP__.'plugin/'.$this->folder.'/conf/'.'uninstall.php';
        if(is_file($uninstall)) {
            return $uninstall;
        }
        return null;
    }

    /**
     * 插件自定义设置接口地址
     */
    public function _configUrl() {
        $conf = __APP__.'plugin/'.$this->folder.'/conf/'.'config.php';
        if(is_file($conf)) {
            return '/admin/plugin/config?id='.$this->folder;
        }
        return false;
    }

    /**
     * 插件安装接口地址
     * @return string
     */
    public function _installUrl() {
        return '/admin/plugin/install?id='.$this->folder;
    }

    /**
     * 插件卸载接口地址
     * @return string
     */
    public function _uninstallUrl() {
        return '/admin/plugin/uninstall?id='.$this->folder;
    }

    /**
     * 禁用插件接口地址
     * @return string
     */
    public function _activateUrl() {
        return '/admin/plugin/activate?id='.$this->folder;
    }

    /**
     * 禁用插件接口地址
     * @return string
     */
    public function _installActivateUrl() {
        return '/admin/plugin/activate?id='.$this->folder;
    }

    /**
     * 禁用插件接口地址
     * @return string
     */
    public function _deactivateUrl() {
        return '/admin/plugin/deactivate?id='.$this->folder;
    }

    /**
     * 插件用户接口
     */
    public function _userUrl() {
        if($this->overall) {
            return '/admin/plugin/user?id='.$this->folder;
        }
        return '#';
    }

}
