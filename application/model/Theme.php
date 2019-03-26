<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/22 下午10:05
 */

namespace model;

use nb\Collection;
use nb\Model;
use util\Plugin;

class Theme extends Model {

    protected static function __config() {
        return ['theme', 'folder'];
    }

    public static function id($id) {
        if(!is_file(__APP__."themes/{$id}/theme.json")){
            return new Collection();
        }
        $conf = self::dao(false)->findId($id);
        $info['folder'] = $id;
        if($conf) {
            $conf = json_decode($conf['config'],true);
            $info = array_merge($conf,$info);
        }
        return new self($info);
    }

    public static function all() {
        $use = System::init()->themes;
        //已经安装过的主题
        $install = self::dao(false)->kv('folder,config');

        //所有主题
        $theme = glob(__APP__.'themes/*/');
        //重新排序，将已安装的主题置入首部
        $yes = [];
        $no = [];
        $act = [];
        foreach ($theme as $v) {
            $temp = explode('/',$v);
            $v = $temp[count($temp)-2];

            $info = ['folder'=>$v];

            if(isset($install[$v])) {
                $conf = json_decode($install[$v],true);
                $info = array_merge($conf,$info);
                $info['isInstall'] = true;
                $v == $use?$act[]=$info:$yes[] = $info;
            }
            else {
                $info['isInstall'] = false;
                $no[] = $info;
            }
        }
        $theme = array_merge($act,$yes,$no);
        return new self($theme,true);
    }

    /**
     * 主题信息
     * @return Collection
     */
    public function _info() {
        $json = __APP__.'themes/'.$this->folder.'/'.'theme.json';
        $info = [];
        if(is_file($json)) {
            $info = file_get_contents($json);
            $info = json_decode($info,true);
        }
        return new Collection($info);
    }

    /**
     * 主题是否激活状态
     */
    public function _isActivate() {
        return $this->folder == Conf::init()->themes;
    }

    public function _screenshot() {
        return "/themes/{$this->folder}/screenshot.png";
    }


    /**
     *
     */
    public function _configUrl() {
        $json = __APP__.'themes/'.$this->folder.'/conf/'.'config.php';
        $info = [];
        if(is_file($json)) {
            $info = file_get_contents($json);
            $info = json_decode($info,true);
        }
    }

    /**
     * 激活禁用地址
     */
    public function _activate() {
        if($this->isActivate) {
            return "/admin/theme/activate?id={$this->folder}";
        }
        return "/admin/theme/activate?id={$this->folder}";
    }

    /**
     * 安装地址
     */
    public function _install() {
        if($this->isInstall) {
            return "/admin/theme/uninstall?id={$this->folder}";
        }
        return "/admin/theme/install?id={$this->folder}";
    }
}