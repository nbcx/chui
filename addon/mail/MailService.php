<?php
namespace service;

use nb\Service;
use util\Auth;
use nb\Middle;
use util\Auxiliary;

class MailService extends Service  {

    protected $subject = [
        'update'=>'修改安全邮箱',
        'bind'=>'绑定安全邮箱',
        'activate'=>'激活安全邮箱',
        'findpwd'=>'找回密码'
    ];

    /**
     * 发送邮件验证码
     * mail/code?type=bind&username=cloklo
     */
    public function code() {
        $type = $this->input('type');

        if(!isset($this->subject[$type])) {
            $this->fail = '类型错误';
            return false;
        }

        $user = Auth::init();
        if($user->isLogin) {
            if($user->email) {
                $mail = $user->email;
            }
            else {
                $mail = $this->input('mail');
            }
        }
        else {
            list($username,$mail) = $this->input('username','mail');
            $user = \model\User::findName($username);
            if($user->email != $mail) {
                $this->fail = '安全邮箱和用户名不匹配';
                return false;
            }
        }

        $subject = $this->subject[$type];

        $result = Auxiliary::sendmail($mail,[
            'subject' => $subject,
            'code'=> Auxiliary::mailCode($mail),
            'username'=>$username
        ]);
        if($result) {
            $this->fail = $result;
            return false;
        }
        $this->success = $subject.'验证码已经发到您邮箱:' . $mail . ',请注意查收！';
        return true;
    }

    /**
     * 修改安全邮箱
     */
    public function update() {
        list($mail,$code) = $this->input('mail','code');

        $user = Auth::init();

        if(!$user->email) {
            $this->fail = '你还没绑定过安全邮箱，无法执行此操作！';
            return false;
        }

        if($user->email == $mail) {
            $this->fail = '更换的安全邮箱不能和原来的邮箱相同！';
            return false;
        }

        if(!Auxiliary::mailVerify($mail,$code)) {
            $this->fail = '验证码错误！';
            return false;
        }

        if(\model\User::find('email=?',$mail)) {
            $this->fail = '此邮箱已经被使用，请重新设置！';
            return false;
        }

        \model\User::updateId($user->id,[
            'email'=>$mail
        ]);

        $this->success = '更换安全邮箱成功';
        return true;
    }

    /**
     * 绑定安全邮箱
     */
    public function bind() {
        list($mail,$code) = $this->input('mail','code');

        $user = Auth::init();

        if($user->email) {
            $this->fail = '你已经绑定过安全邮箱了！';
            return false;
        }

        if(!Auxiliary::mailVerify($mail,$code)) {
            $this->fail = '验证码错误！';
            return false;
        }

        if(\model\User::find('email=?',$mail)) {
            $this->fail = '此邮箱已经被使用，请重新设置！';
            return false;
        }

        \model\User::updateId($user->id,[
            'email'=>$mail,
            'is_active'=>1
        ]);
        $this->success = '绑定安全邮箱成功';
        return true;
    }

    /**
     * 激活邮箱
     * @return bool
     */
    public function activate() {
        $code = $this->input('code');

        $user = Auth::init();

        if(!$user->email) {
            $this->fail = '你还没设置安全邮箱，无法执行此操作！';
            return false;
        }

        if(!Auxiliary::mailVerify($user->email,$code)) {
            $this->fail = '验证码错误！';
            return false;
        }

        \model\User::updateId($user->id,['is_active'=>1]);
        $this->success = '激活安全邮箱成功';
        return true;
    }


}
