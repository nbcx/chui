<?php
namespace controller;

use util\Controller;
use model\Conf;

class Register extends Controller {

    public function index() {
        $this->title('新用户注册');
        //if ($this->auth->islogin) {
        //    $this->tips('已登录，请退出再注册');
        //}

        $this->assignSafe('action','/register/post');
        $this->assignSafe('unique','/register/unique');

        $this->assign('captcha',\sdk\Captcha::url());
        //显示注册界面
        $this->display('register');
    }


    public function unique() {
        $this->protect();

        $form = $this->form('post');
        foreach ($form as $k=>$v) {
            $name=$k;
            $value = $v;
            break;
        }

        $unique = \model\User::find("{$name}=?",$value);
        $this->json(['valid'=>!$unique->have]);
    }

    public function post() {
        $this->protect();

        $run = \service\Auth::run('register',function ($msg) {
            ed($msg);
        });

        $this->redirect('/login');
    }


}