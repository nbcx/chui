<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/25 下午3:15
 */
namespace service;

use nb\Service;
use util\Controller;
use model\Conf;
use model\Stats;
use nb\Middle;
use util\Auxiliary;

class Gateway extends Service {

    public function login() {

        //登陆开始插件钩子
        $this->hook()->loginStart($this,$this->controller);
        if($this->status === false) {
            return false;
        }

        //验证码
        if(Auxiliary::captcha('login') === false) {
            $this->fail = '验证码错误!';
            return false;
        }

        //登陆的插件点
        $user = $this->hook()->trigger($signal)->loginCheck($this,$this->controller);
        if($signal === false) {

            //如果没有手动传用户信息，则自动从控制器里获取
            list($username,$password) = $this->input(
                'username',
                'password'
            );

            $user = \model\User::find('username=?',$username);

            if (!$user) {
                $this->fail = '用户名不存在!';
                return false;
            }

            $pass = password_dohash($password, $user->salt);

            if ($user['password'] != $pass) {
                $this->fail = '密码错误!';
                return false;
            }

            $user = login($username);
        }

        $this->hook()->trigger($signal)->loginSuccess($this,$this->controller);
        if($signal === false) {
            if($user && time() - $user['lastlogin'] > 86400) {
                $data['credit'] = $user['credit'] + Conf::init()->credit_login;
                \model\User::updateId($user->uid,$data);
            }
        }

        //登陆结束插件钩子
        $this->hook()->loginEnd($username,$password);

        return true;
    }


    /**
     * 会员注册
     * @param Controller $that
     */
    public function register() {
        list($username,$password,$email) = $this->input(
            'username',
            'password',
            'email'
        );

        //检测验证码码

        //检测不允许注册的用户名

        //入库前的插件点
        $user = $this->hook()->trigger($signal)->insert($username,$password,$email);
        if($signal === false) {
            $user = \model\User::find(
                'username=? or email=?',
                [$username,$email]
            );
            if($user) {
                $this->fail = $user->username==$username?'用户名已经存在':'邮箱已经使用';
                return false;
            }
            $user = register($username,$password,$email,2,3,1);
        }

        if(!$user) {
            $this->fail = '当前注册人数太多，请稍后再注册！';
            return false;
        }

        $conf = Conf::init();

        //发送注册邮件
        if ($conf->mail_reg == 'on') {
            $subject = '欢迎加入' . $conf->site_name;
            $message = '欢迎来到 ' . $conf->site_name . ' 论坛<br/>请妥善保管这封信件。您的帐户信息如下所示：<br/>----------------------------<br/>用户名：' . $username . '<br/>论坛链接: ' . site_url() . '<br/>----------------------------<br/><br/>感谢您的注册！<br/><br/>-- <br/>' . $conf->site_name;
            sendmail($email, $subject, $message);
        }

        Stats::update(['value'=>$user->uid],'name=?','last_uid');
        Stats::update('value=value+1','name=?','total_users');

        //注册成功后的插件点
        $this->hook()->trigger($signal)->end($user);

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