<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/21 上午11:38
 */

namespace util;


use nb\Cache;

class Mail {

    public function mail() {
        list($do,$mail) = $this->input('do','email');

        $user = Auth::init();
        //修改,发送确认修改邮件
        if($do == 'update') {
            //链接有效时间,暂定为半个小时
            $expire = time() + 60*30;
            $uid = $user->id;

            $x = md5($uid . '+' . $expire) .$user->password;
            $string = base64_encode($uid . '.' . $x . '.' . $expire);

            $subject = '修改安全邮箱';
            $resetUrl = Conf::init()->resetPwdUrl.'?p=' . $string;
            $message = '尊敬的用户' . $user->username . ':<br/>你使用了本站提供的安全邮箱修改功能，如果你确认此功能是你启用的，请点击下面的链接，按流程进行安全邮箱修改。<br/><a href="' . $resetUrl . '">' . $resetUrl . '</a><br/>如果不能打开链接，请复制链接到浏览器中。<br/>如果本次安全邮箱修改请求不是由你发起，你可以安全地忽略本邮件。';
        }
        else if($do == 'update'){

        }
        //激活安全邮箱
        else if($do == 'activate'){

        }
        //绑定安全邮箱
        else {

        }
        $email = $user->email;
        if (sendmail($email, $subject, $message,$error)) {
            $this->success = '密码重置链接已经发到您邮箱:' . $email . ',请注意查收！';
            return true;
        }
        $this->fail = '邮件发送失败{'.$error.'}!';
    }

    /**
     * 发送邮件
     * 有频率限制
     *
     * @param $condition 用户ID或者客户端IP或其它唯一标示
     * @param $address
     * @param null $config
     * @return bool
     */
    public static function sendmail($address,$condition,$config=null) {
        $num = func_num_args();
        if($num == 2) {
            $config = $condition;
            $condition = $address;
        }

        $condition = 'auxiliary/mail:'.md5($condition);
        //时间间隔限制
        $time = Cache::get($condition,0);//上次发送邮件时间
        //设置的发送邮件间隔
        //暂时写死 3 分钟，后续从系统设置里读取
        $send_mail_rate = 180;//单位是秒

        $value = $time + $send_mail_rate - time();
        if($value > 0) {
            return '你的邮件发送过于频繁，请等待'.$value.'秒后在发送！';
        }

        //记录本次发送邮件时间间隔
        Cache::set($condition,time());
        return sendmail($address,$config);
    }


    /**
     * 验证邮箱验证码是否正确
     * @param $address
     * @return bool
     */
    public static function mailVerify($mail,$code) {
        $scode = Cache::get("auxiliary/{$mail}:code");
        b($mail,$scode);
        return $code==$scode;
    }

    /**
     * 生成一个邮箱验证码，并记录进缓存
     * @param $mail
     * @param string $type
     * @return int
     */
    public static function mailCode($mail) {
        $code = code('number');
        Cache::set("auxiliary/{$mail}:code",$code);
        return $code;
    }

}