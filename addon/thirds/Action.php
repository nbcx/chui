<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/20 下午4:46
 */

namespace plugin\thirds\controller;

use plugin\thirds\Login;

class Action {

    public function index() {
        echo 'thirds';
    }

    public function login() {
        e($_GET);
        // 配置可以移到框架的配置文件里面
        $config = [
            'proxy' => [
                'dns' => '127.0.0.1:1080',
                'type' => CURLPROXY_SOCKS5
            ],
            'weibo' => [
                'key' => '111111111',
                'secret' => 'xxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=weibo',
                'scope' => 'email'
            ],
            'qq' => [
                'key' => '111111111',
                'secret' => 'xxxxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=qq',
            ],
            'google' => [
                'key' => 'xxxxxxxxxxxxxx',
                'secret' => 'xxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=google',
                'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                'enableProxy' => true,
            ],
            'github' => [
                'key' => 'xxxxxxxxxxxx',
                'secret' => 'xxxxxxxxxxxxx',
                'redirectUrl' => '/user/socialLogin?type=github',
            ],
        ];
        try {
            $obj = new Login($config);
            $socialInfo = $obj->authAndGetUserInfo();
        }
        catch (\Exception $e) {
            echo $e->getMessage(), '具体原因：' . print_r($obj->getLastError(), true);
            exit;
        }
        ed($socialInfo);
    }

}