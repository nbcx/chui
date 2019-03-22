<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/25 下午3:15
 */
namespace service;

use model\System;
use util\Controller;
use model\Conf;
use model\Stats;
use util\Auxiliary;
use util\Service;

class Auth extends Service {

    public function login() {

        //验证码
        //if(Auxiliary::captcha('login') === false) {
        //    $this->msg = '验证码错误!';
        //    return false;
        //}

        list($username,$password) = $this->input(
            'login',
            'password'
        );

        $user = \model\User::find('username=?',$username);

        if ($user->empty) {
            $this->msg = '用户名不存在!';
            return false;
        }

        $pass = password_dohash($password, $user->salt);

        if ($user['password'] != $pass) {
            $this->msg = '密码错误!';
            return false;
        }
        $token  = login($user);
        if($token === false) {
            $this->msg = '登录失败!';
            return false;
        }
        $user['token'] = $token;
        $this->data = $user;
        return true;
    }


    /**
     * 会员注册
     * @param Controller $that
     */
    public function register() {
        list($username,$password,$mail) = $this->input(
            'username',
            'password',
            'mail'
        );

        $user = \model\User::find(
            'username=? or mail=?',
            [$username,$mail]
        );

        if($user->have) {
            $this->msg = $user->username==$username?'用户名已经存在':'邮箱已经使用';
            return false;
        }

        $user = register($username,$password,$mail,2,3,1);

        if(!$user) {
            $this->msg = '当前注册人数太多，请稍后再注册！';
            return false;
        }

        $conf = System::init();

        //发送注册激活邮件邮件
        if ($conf->mail_reg == 'on') {
            $subject = '欢迎加入' . $conf->site_name;
            $message = '欢迎来到 ' . $conf->site_name . ' 论坛<br/>请妥善保管这封信件。您的帐户信息如下所示：<br/>----------------------------<br/>用户名：' . $username . '<br/>论坛链接: ' . site_url() . '<br/>----------------------------<br/><br/>感谢您的注册！<br/><br/>-- <br/>' . $conf->site_name;
            sendmail($mail, $subject, $message);
        }

        Stats::update(['value'=>$user->uid],'name=?','last_uid');
        Stats::update('value=value+1','name=?','total_users');

        $this->success = $user;
        return true;
    }

    /**
     * 退出登陆状态
     */
    public function logout() {
        $result = $this->hook()->trigger($signal)->logout();
        if($signal === false) {
            return logout();
        }
        return $result;
    }

    public function findpwd() {
        list($username,$mail,$code,$password) = $this->input(
            'username','mail','code','password'
        );

        //检测code是否有效
        if(!Auxiliary::mailVerify($mail,$code)) {
            $this->fail = '验证码错误';
            return false;
        }


        $user = \model\User::findName($username);

        if($user->email != $mail) {
            $this->fail = '用户名和邮箱不匹配';
            return false;
        }

        $salt = get_salt();
        $password = password_dohash($password, $salt);
        $rows = \model\User::updateId($user->id,[
            'password' => $password,
            'salt' => $salt
        ]);
        return $rows;
    }



}