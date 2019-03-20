<?php
namespace controller;

use util\Auth;
use util\Controller;
use nb\Request;

class Follow extends Controller {

    public function __before(){
        $pass = parent::__before();
        $this->authLogin();
        return $pass;
    }

    public function index() {
        $data['title'] = '我关注的用户';
        list($total,$follows) = \model\Follow::page(Auth::init()->id,20);

        $data['follows'] = $follows;
        /*
        //获取ids数据
        if (is_array($data['follow_list'])) {
            foreach ($data['follow_list'] as $v) {
                $ids[] = $v['follow_uid'];
            }
        }
        //获取关注用户的贴子
        if (isset($ids)) {
            //$data['follow_user_topics'] =TopicDao::obj()->get_topics_by_uids($ids, 15);
        }
        */
        $data['user'] = Auth::init();
        $this->assign($data);
        $this->display('user/follow');
    }

    public function add() {

        $this->middle(true,'add',function (){
            redirect(Request::referer());
        });

    }

    public function del() {

        $this->middle(true,'del',function (){
            redirect(Request::referer());
        });
    }


}
