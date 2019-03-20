<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/22 下午4:00
 */
namespace controller;

use util\Controller;

class Mail extends Controller {

    public function code() {
        $this->middle(true,'code',function($msg) {
            $this->success($msg);
        });
    }

    public function update() {
        $this->middle($this->isPost,'update',function($msg) {
            $this->success($msg);
        });
        $this->display('user/mail/update');
    }

    public function bind() {
        $this->middle($this->isPost,'bind',function($msg) {
            $this->success($msg);
        });
        $this->display('user/mail/bind');
    }

    public function activate() {
        $this->middle($this->isPost,'activate',function($msg) {
            $this->success($msg);
        });
        $this->display('user/mail/activate');
    }

}