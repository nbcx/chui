<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/20 下午2:51
 */

namespace controller;


use util\Controller;
use model\Conf;

class Gateway extends Controller {

    /**
     * 代帮开门
     */
    public function proxy() {

        \nb\Hook::pos('controller\Gateway')->trigger($signal)->proxy($this);

        if($signal === false) {
            ed('非法请求');
        }
    }

    /**
     * 开门
     */
    public function login() {
        $data['title'] = '用户登录';

        $this->middle($this->isPost,'login',function (){
            redirect();
        });

        //$data['csrf_name'] = $this->security->get_csrf_token_name();
        //$data['csrf_token'] = $this->security->get_csrf_hash();
        $this->assign($data);
        $this->display('login');
    }


    /**
     * 配钥匙
     */
    public function register() {

        $data['title'] = '注册新用户';

        if ($this->auth->islogin) {
            show_message('已登录，请退出再注册', site_url());
        }

        //处理注册请求
        $this->middle($this->isPost,'register',function ($user) {
            $this->success(Conf::init()->loginUrl);
        });

        //显示注册界面
        //$data['csrf_name'] = $this->security->get_csrf_token_name();
        //$data['csrf_token'] = $this->security->get_csrf_hash();
        $this->display('register');
    }

    /**
     * 关门
     * @throws \ReflectionException
     */
    public function logout() {
        $this->middle(true,'logout');
        redirect(Conf::init()->loginUrl);
    }


    /**
     * 找回钥匙
     * @throws \ReflectionException
     */
    public function findpwd() {

        $this->middle($this->isPost,'findpwd',function ($msg){
            $this->success($msg,Conf::init()->loginUrl);
        });

        $data['title'] = '找回密码';
        //$data['csrf_name'] = $this->security->get_csrf_token_name();
        //$data['csrf_token'] = $this->security->get_csrf_hash();
        $this->assign($data);
        $this->display('user/findpwd');
    }

    /**
     * 换锁
     * @throws \ReflectionException
     */
    public function resetpwd() {

        $this->middle($this->isPost,'resetpwd',function (){
            $this->success(
                '密码重置成功，即将进入登陆界面！',
                Conf::init()->loginUrl,
                3
            );
        });

        $data['title'] = '设置新密码';
        $data['p'] = $this->input('p');//$_GET['p'];
        $this->assign($data);
        $this->display('user/resetpwd');
    }

    public function _check_username($username) {
        if (!preg_match('/^(?!_)(?!.*?_$)[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', $username)) {
            return false;
        }
        else {
            return true;
        }
    }

}