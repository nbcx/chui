<?php
namespace controller;

use util\Auth;
use util\Controller;
use model\Stats;
use util\Pagination;

class Node extends Controller {

    public function index() {
        $this->title('版块列表');

        //获取版块列表
        $data['catelist'] = \model\Node::pid();

        //最新会员列表
        $data['new_users'] = \model\User::rank(9);

        //最新贴子列表
        $data['new_topics'] = \model\Topic::dao()->driver->orderby('ct desc')->limit(5)->fetchAll();

        //action
        $data['action'] = 'node';

        //统计
        $data['stats'] = Stats::kv();

        $this->assign($data);
        $this->display('node');
    }

    public function show($node_id, $page = 1) {
        //权限
        if (!Auth::init()->user_permit($node_id)) {
            $this->fail('您无限访问此节点!');
        }

        $rows = 30;

        $topic = \model\Topic::page($rows, $page, $node_id);
        //ed($topic);
        //分页
        $data['page'] = [
            'rows'=>$rows,
            'total'=>$topic[0],
        ];

        //获取列表
        $data['topics'] = $topic[1];  //TopicDao::obj()->get_topics_list($rows, $page, $node_id);

        //暂时不知道这个方法为什么不能调用 BY collin
        $data['node'] = \model\Node::findId($node_id);// NodeDao::obj()->get_category_by_node_id($node_id);

        $data['title'] = strip_tags($data['node']['cname']);

        //获取分类
        //$this->load->model('cate_m');
        $data['catelist'] = \model\Node::pid();// NodeDao::obj()->get_all_cates();

        $this->assign($data);
        $this->display('topic');
    }

}
