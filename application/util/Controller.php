<?php
/*
 * This file is part of the NullBB package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace util;

use bin\Config;
use model\Conf;
use model\System;
use nb\Middle;

/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/7 下午8:12
 */
class Controller extends Base {

    /**
     * 当前登陆的用户信息
     * @var array
     */
    protected $user;


    public function __before(){

        $system = System::init();

        //判断关闭
        if ($system->site_close == 'off') {
            show_error($system->site_close_msg, 500, '网站关闭');
        }

        $auth = Auth::init();

        $this->view->config([
            'view_suffix' => 'html',
            'view_path' =>__APP__ . "template/{$system->themes}/",
            'taglib_build_in' => 'util\Label,nb\view\tag\Cx',
            'tpl_replace_string' => [
                '_pub_' => '/public/',
                '_uploads_' => '/uploads/',
                '_url_' => '/',
                '_theme_' => "/template/{$system->themes}/"
            ]
        ]);

        //在模版里读取配置
        $data['conf'] = Config::$o;

        $data['auth'] = $auth;

        $data['system'] = $system;

        //输出到模版
        $this->assign($data);

        return true;
    }

    /**
     * 设置页面标题
     * @param $title
     */
    protected function title($title,$hou='NBCX') {
        $title = "{$title}-{$hou}";
        $this->assign('title',$title);
    }

    public function __after($content) {
        if($content) {
            echo $content;
        }
    }

    public function __error($msg,$args) {
        $this->fail($msg);
    }

    protected function success($msg=null,$url=false,$time=10) {
        b('success-$url',$url);
        $this->jump($msg,$url,0,$time);
    }

    /**
     * 请求失败后的反馈，此函数会终止后续程序的运行
     *
     * @param null $msg 错误提示
     * @param int $code 错误代码
     * @param null $url 提示过后跳转的页面，默认为上一级页面
     * @throws \Exception
     */
    protected function fail($msg=null,$url=false,$time=10) {
        $this->jump($msg,$url,false,$time);
    }

    protected function jump($msg,$url=false,$status=false,$time=5) {
        //检查是否为ajax，是则返回json
        if($this->isAjax) {
            $this->json([
                'status'=>$status,
                'msg'=>$msg,
                'url'=>$url
            ]);
        }
        $msg = $msg?:($status?'Success':'Error');
        //检查是否存在错误提示模版，是则显示错误模版
        $file = theme_file_exist('tips.html');

        //显示默认错误模版
        $file = $file?:__APP__ . 'application/view/tips.html';

        //渲染模版
        $this->assign('status',$status);
        $this->assign('msg',$msg);
        $this->assign('time',$time*1000);
        $this->assign('url',$url);
        $this->display($file);
        //结束程序运行
        quit();
    }

    protected function tips($hint) {
        $this->assign('msg',$hint);
        $this->display('hint');
        quit();
    }

    protected function redirect($url, $http_response_code=302) {
        //检查是否存在referer
        redirect($url,$http_response_code);
        quit();
    }


}