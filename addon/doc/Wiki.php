<?php
namespace plugin\doc;

use nb\Collection;
use nb\Model;

class Wiki extends Model {

    public $current;

    protected static function __config() {
        return ['wiki', 'id'];
    }

    /**
     * 获取指定父类PID的所有节点
     * @param $pid
     */
    public static function pid($pid=0) {
        return self::fetchs('pid=?',$pid,'*','sort asc');
    }

    /**
     * 节点地址
     */
    protected function _url() {
        if($this->alias) {
            return "/{$this->alias}.htm";
        }
        return "/{$this->id}";
    }

    /**
     * 是否为当前导航项
     * @return bool
     */
    protected function _active() {
        $current = $this->current;
        if(!$current) {
            return false;
        }
        if($current->id == $this->id) {
            return true;
        }
        if($this->id == $current->pid) {
            return true;
        }
        return false;
    }

    /**
     * 子类
     */
    protected function _child() {
        return self::pid($this->id)?:new Collection();
    }

    protected function _hasChild() {
        return $this->child->count();
    }

}
