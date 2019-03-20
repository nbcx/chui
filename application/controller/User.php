<?php
namespace controller;

use util\Controller;
use model\Upload;

class User extends Controller {

    public function index() {
        $data['title'] = '用户';

        $data['newusers'] = \model\User::rank(30,'id desc');
        $data['hotusers'] = \model\User::rank(30,'lastlogin desc');

        //action
        $data['action'] = 'user';

        $this->assign($data);
        $this->display('user/index');
    }

    public function profile($uid) {
        $data['user'] = \model\User::findId($uid);

        if (!$data['user']) {
            $this->fail('用户不存在');
            //show_message('用户不存在', site_url('/'));
        }
        //用户大头像
        $upload = new Upload();
        //$data['big_avatar'] = $upload->get_avatar_url($uid, 'big');
        //此用户发贴
        $data['topics'] = \model\Topic::byuid($uid, 5);
        //此用户回贴
        $data['comments'] = \model\Comment::byUid($uid, 5);

        //是否被关注
        //$data['is_followed'] = \model\Follow::isFollow(Auth::init()->uid,$uid);

        $data['title'] = $data['user']['username'];
        $this->assign($data);
        $this->display('user/profile');
    }

    public function topic($id) {
        $data['user'] = \model\User::findId($id);

        $data['topics'] = \model\Topic::byUid($id);

        $this->assign($data);
        $this->display('user/topic');
    }

    public function comment($id) {
        $data['user'] = \model\User::findId($id);
        $data['comments'] = \model\Comment::byUid($id, 5);
        $this->assign($data);
        $this->display('user/comment');
    }


}