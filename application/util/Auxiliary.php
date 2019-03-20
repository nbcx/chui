<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/29 下午3:59
 */

namespace util;

use util\Auth;
use model\Conf;
use nb\Cache;
use nb\Request;

class Auxiliary {

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

    /**
     * 处理@会员功能
     * @param $content
     * @return mixed
     */
    public static function at($content,$topic_id,$uid=null) {
        //$comment = $content;
        $uid = $uid?:Auth::init()->id;
        $pattern = "/@([^@^\\s^:]{1,})([\\s\\:\\,\\;]{0,1})/";
        preg_match_all($pattern, $content, $matches);
        $at = array_unique($matches[1]);
        if(!$at) {
            return $content;
        }
        foreach ($at as $u) {
            if ($u) {
                $u =  \model\User::findName($u);
                if (!$u) {
                    continue;
                }
                $search [] = '@' . $u->username;
                $replace [] = '<a target="_blank" href="' . $u->url . '" >@' . $u->username . '</a>';
                if ($uid != $u['uid']) {
                    //@提醒someone
                    Notify::at($topic_id, $uid, $u['uid'], 1);
                    //更新接收人的提醒数
                    \model\User::updateId($u['uid'],"notices=notices+1");
                }
            }
        }
        return str_replace(@$search, @$replace, $content);
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