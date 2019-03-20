<?php
namespace model;

use nb\Collection;
use nb\Model;

class Node extends Model {

    protected static function __config() {
        return ['node', 'id'];
    }

    public static function all() {
        $query = self::fetchs();// $this->fets('node_id,pid,cname,ico,content,listnum,master','pid desc');
        return $query;
    }

    /**
     * 获取指定父类PID的所有节点
     * @param $pid
     */
    public static function pid($pid=0) {
        return self::fetchs('pid=?',$pid);
    }

    /**
     * 节点地址
     */
    protected function _url() {
        return "/node/show/{$this->id}";
    }

    protected function _ico() {
        return '/'.$this->row['ico'];
    }

    protected function _postUrl() {
        return 'topic/add?id='.$this->id;
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

    //获取今日帖子数
    public function _today() {
        if(date('Y-m-d',$this->ut) == date('Y-m-d',time())) {
            return $this->row['today'];
        }
        self::updateId($this->id,'today=0,ut=?',time());
        return 0;
    }

}
