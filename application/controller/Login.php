<?php
/*
 * This file is part of the NB Framework package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Login {

    public function index() {
        //记录referer

        $this->title('用户登录');

        list($openid,$type) = $this->input('openid','type');
        if($openid && $type) {
            $action = $this->safe("/login/post?openid={$openid}&type={$type}");
        }
        else {
            $action = $this->safe('/login/post');
        }

        $this->assign('captcha',\sdk\Captcha::url());
        $this->assign('action',$action);
        $this->display('login');
    }

    public function post() {
        $this->protect();

        $run = Gateway::run('login',function ($msg) {
            $this->back($msg);

        });

        SdkAuth::cookieLogin($run->data->stack());
        $this->redirect();

    }
}