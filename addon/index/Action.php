<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/18 下午2:02
 */
namespace addon\index\controller;

use util\Controller;

class Action extends Controller {


    public function index() {
        $this->display('index');
    }

    public function index2() {
        $this->display('index-will');
    }

    public function will() {
        $this->display('will');
    }


}