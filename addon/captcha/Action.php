<?php
/*
 * This file is part of the NB Framework package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace addon\captcha\controller;
use addon\captcha\Captcha;
use nb\Request;

/**
 * Action
 *
 * @package addon\captcha
 * @link https://nb.cx
 * @author: collin <collin@nb.cx>
 * @date: 2019/3/27
 */
class Action {

    public function show() {
        $captcha = new Captcha();
        $captcha->show();
    }

    /**
     * 检测验证码是否正确
     * @param string $type
     * @return bool
     */
    public static function captcha($type = 'show_captcha') {
        //$type = 'show_captcha';
        $on = 'show_captcha';
        if(Conf::init()->$on != 'on') {
            return true;
        }
        $captcha = Request::input('captcha');

        if(session('yzm') == strtolower($captcha)){
            return true;
        }
        return false;
    }

}