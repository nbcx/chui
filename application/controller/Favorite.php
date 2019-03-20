<?php
namespace controller;

use util\Auth;
use util\Controller;


class Favorite extends Controller {


    public function __before(){
        $pass = parent::__before();
        if(Auth::init()->isNotLogin) {
            show_message('请登录后再查看', site_url('login'));
        }
        return $pass;
    }

    public function index($page = 1) {
        $data['title'] = '贴子收藏';
        $uid = Auth::init()->id;
        //分页
        $rows = 10;
        /*
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = true;
        $config['base_url'] = site_url('favorites/index');
        */

        $favorite =  \model\Favorite::find('uid=?',$uid);

        $data['topics'] = [];
        if ($favorite->total > 0) {
            $topics = $favorite->topicIds(20, $page);
            $data['topics'] = new \model\Topic($topics);
        }
        $data['user'] = Auth::init();
        $this->assign($data);
        $this->display('user/favorite');

    }

    public function add() {
        $this->middle(true,'add',function ($topic_id){
            $topic = \model\Topic::findId($topic_id);
            redirect($topic->url);
        });
    }

    public function del() {

        $this->middle(true,'del',function ($topic_id){
            $topic = \model\Topic::findId($topic_id);
            redirect($topic->url);
        });
    }


}
