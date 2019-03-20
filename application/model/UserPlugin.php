<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/7/8 下午12:28
 */

namespace model;


use nb\Model;

class UserPlugin extends Model {

    protected static function __config() {
        return ['user_plugin', 'uid'];
    }

    public function _user() {
        return User::findId($this->uid);
    }

    public function _plugin() {
        return Plugin::id($this->pname);
    }

    public function _status() {
        return $this->activate?'已启用':'未使用';
    }




}