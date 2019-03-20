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

use nb\Request;
use util\Model;

class Menu extends Model {

    public $current;

    protected static function __config() {
        return ['menu', 'id'];
    }

    public static function zTree() {
        $tree = self::dao(false)
            ->field('id,pid pId,name,icon,level')
            ->orderby('sort asc,id asc')
            ->fetchAll();
        foreach ($tree as &$t) {
            if($t['pId'] == 0) {
                $t['open'] = true;
                $t['drag'] = false;
                $t['dropInner'] = false;
                $t['dropPrev'] = false;
                $t['icon'] = '/public/img/menu/16/'.$t['icon'];
            }
            else {
                $t['drag'] = true;
                $t['dropRoot'] = false;
                $t['dropInner'] = false;
            }
        }
        return $tree;
    }

    public static function nav($path=null) {
        $menu = self::fetchs('level=0');
        //获取当前的请求
        $menu->current = $path?:self::find('link=?',Request::ins()->pathinfo);
        return $menu;
    }

    /**
     * 是否请求当前菜单
     */
    public function _isCurrent() {
        $path = $this->current;//
        if($this->level == 0) {
            if($path->level == 2) {
                return $this->id == $path->parent->parent->id;
            }
            else {
                return $this->id == $path->parent->id;
            }
        }
        if($path->level == $this->level) {
            return $path->id == $this->id;
        }
        return $this->id == $path->parent->id;
    }


    protected function _parent() {
        $parent = self::findId($this->pid);
        $parent->current = $this->current;
        return $parent;
    }

    /**
     * @return $this
     */
    protected function _childs() {
        $childs =  self::fetchs('pid=?',$this->id);
        $childs->current = $this->current;//
        return $childs;
    }

    protected function _icon16() {

    }

    protected function _icon32() {
        return '/public/img/menu/32/'.$this->icon;
    }

}
