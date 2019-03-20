<?php
namespace service;

use nb\Service;
use util\Auth;
use model\Conf;
use nb\Middle;

class Follow extends Service {

    public function add() {

        //被关注者
        $follow_uid = $this->input('uid');

        //关注操作(需要优化)
        $uid = Auth::init()->id;//$this->session->userdata('uid');

        if($uid == $follow_uid) {
            $this->fail = '你不能关注你自己';
            return false;
        }

        //检查是否关注
        $is_followed = \model\Follow::find('uid=? and follow_uid=?',[$uid,$follow_uid]);
        if($is_followed) {
            $this->fail = '你已经关注了这个用户';
            return false;
        }

        $data = [
            'uid' => $uid,
            'follow_uid' => $follow_uid,
            'ct' => Conf::init()->timestamp
        ];

        \model\Follow::insert($data);

        \model\User::updateId($uid,'follows=follows+1');
        //插入数据

        //更新被关注者会员积分
        //此功能交给插件做吧
        \model\User::updateId($follow_uid,'fans=fans+1');

        $this->success = $follow_uid;
        return true;
    }

    public function del() {
        //被关注者
        $follow_uid = $this->input('uid');

        //关注操作(需要优化)
        $uid = Auth::init()->id;//$this->session->userdata('uid');

        //检查是否关注
        $follow = \model\Follow::find('uid=? and follow_uid=?',[$uid,$follow_uid]);
        if(!$follow) {
            $this->fail = '不能取消没有关注的用户';
            return false;
        }

        \model\Follow::deleteId($follow->id);
        \model\User::updateId($follow_uid,'fans=fans-1');
        \model\User::updateId($uid,'follows=follows-1');
        return true;
    }


}
