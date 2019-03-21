<?php
namespace controller;

use util\Controller;
use model\Conf;

class Register extends Controller {


    public function index() {

        $data['title'] = '注册新用户';

        if ($this->auth->islogin) {
            show_message('已登录，请退出再注册', site_url());
        }

        //处理注册请求
        $this->middle($this->isPost,'register',function ($user) {
            $this->success(Conf::init()->loginUrl);
        });
        $this->assign('captcha',\sdk\Captcha::url());
        //显示注册界面
        $this->display('register');
    }


}