<?php
/**
 * !add
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/8 上午11:08
 */

namespace model;

use common\Db;
use nb\Model;

class Stats extends Model {

    public function __construct(array $data = []) {
        parent::__construct($data, false);
    }

    protected static function __config() {
        return ['stats', 'name'];
    }

    public static function kv($condition = NULL, $params = NULL){
        return self::dao()->kv('name,value',$condition,$params);
    }

    public static function test() {
        return 'hello';
    }


    /**
     *
     */
    public function luser() {
        return User::findId($this->last_uid);
    }

}