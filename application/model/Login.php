<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/25 下午3:15
 */
namespace service;

use sdk\Captcha;
use sdk\Mail;
use sdk\Util;
use util\Controller;
use model\Conf;
use model\Stats;
use util\Auxiliary;
use util\Service;

class Login extends Service {

    public function login() {

        //如果没有手动传用户信息，则自动从控制器里获取
        list($login,$password,$captcha) = $this->input(
            'login',
            'password',
            'captcha'
        );

        //验证码
        if(Captcha::verify($captcha) == false) {
            $this->msg = '验证码错误!';
            return false;
        }

        //是邮箱
        if(strpos($login,'@') !==false){
            $where = 'mail=?';
        }
        //是手机号
        elseif(is_numeric($login)){
            $where = 'phone=?';
        }
        //是用户名
        else {
            $where = 'username=?';
        }

        $user = \model\User::find($where,$login);

        if (!$user) {
            $this->msg = '用户名错误!';
            return false;
        }

        if(!password_verify($password, $user->password)) {
            $this->msg = '密码错误!';
            return false;
        }

        //SdkAuth::cookieLogin($user->stack());
        //生成登陆后的token
        //$taken = md5(time().$password);

        //如果选自动登陆，则设置永不过期
        //否则随浏览器关闭而过期
        //Cookie::set('_s',$taken);

        //\model\User::updateId($user['id'],['token'=>$taken]);
        $this->data = $user;

        $this->bind();
        return true;
    }


    /**
     * 会员注册
     * @param Controller $that
     */
    public function register() {
        list($username,$password,$mail,$mcode,$captcha) = $this->input(
            'username',
            'password',
            'mail',
            'mcode',
            'captcha'
        );

        //检测图形验证码码
        if(Captcha::verify($captcha) == false) {
            $this->code = 20001;
            $this->msg = '图形验证码错误!';
            return false;
        }

        //检测邮箱验证码
        if(Mail::verify($mail,$mcode) == false) {
            $this->code = 20002;
            $this->msg = '邮箱验证码错误!';
            return false;
        }

        //检测不允许注册的用户名

        //检查用户名和邮箱是否存在
        $user = \model\User::find(
            'username=? or mail=?',
            [$username,$mail]
        );

        if($user->have) {
            $this->code = 30003;
            $this->msg = $user->username==$username?'用户名已经存在':'邮箱已经被使用';
            return false;
        }

        $data = [
            'username' => strip_tags($username),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'mail' => $mail,
            'acmail' => 1,
            'nbid' => Util::nbid(),
        ];
        $id = \model\User::add($data);

        if(!$id) {
            $this->code = 10500;
            $this->msg = '系统繁忙！';
            return false;
        }

        $this->data = \model\User::findId($id);

        $this->bind();

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

    public function bind() {
        list($openid,$type) = $this->input(
            'openid',
            'type'
        );

        if(!$openid && !$type) {
            return false;
        }

        $third = \model\Third::find('openid=? and type=?',[$openid,$type]);
        if($third->hasBind) {
            $this->code =30002;
            return false;
        }

        \model\Third::updateId($openid,[
            'uid'=> $this->data->id
        ]);

        return true;
    }



}