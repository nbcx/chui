<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/29 下午3:59
 */

namespace util;

use model\Conf;

class Auxiliary {

    /**
     * 根据时间和随机数，生成定长的唯一标示
     *
     * @param string $number
     * @return string
     */
    public static function unique() {
        $id = time() . rand(10000,99999);
        return base_convert($id,10,16);
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




}