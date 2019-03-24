<?php
namespace controller;

use nb\Cookie;
use util\Controller;

class Login extends Controller {

    public function index() {
        //记录referer

        $this->title('用户登录');

        list($openid,$type) = $this->input('openid','type');
        if($openid && $type) {
            $action = $this->safe("/login/post?openid={$openid}&type={$type}");
        }
        else {
            $action = $this->safe('/login/post?action=login');
        }
        //$this->assign('captcha',\sdk\Captcha::url());
        $this->assign('action',$action);
        $this->display('login');
    }

    public function post($action) {
        $this->protect();

        $run = \service\Auth::run($action,function ($msg) {
            ed($msg);
        });

        Cookie::set('_user',$run->data->token);
        $this->redirect('/');
    }
}