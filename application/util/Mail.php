<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/6/21 上午11:38
 */

namespace util;


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

}