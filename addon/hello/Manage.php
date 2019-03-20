<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/18 下午2:02
 */

namespace plugin\hello\controller;

use util\Administrator;

class Manage extends Administrator {


    public function index($hello='index') {
        echo "\nManage {$hello}\n";
    }

    public function index2($hello='index') {
        $this->display('tables');
    }


}