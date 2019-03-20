<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/5 下午3:44
 */
namespace plugins\links\controller;

use util\Administrator;

class Index extends Administrator {

    public function index() {
        $this->display('index');
    }

}