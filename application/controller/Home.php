<?php
namespace controller;

use util\Controller;
use model\Link;
use model\Stats;

class Home extends Controller {

    public function index($page=1) {
        $home_page_num = 20;//($this->set->home_page_num) ? $this->set->home_page_num : 20;

        //获取列表
        list($total,$topics) = \model\Topic::page($home_page_num,$page); //TopicDao::obj()->get_topics_list_nopage($this->home_page_num);

        $data['total'] = $total;
        $data['topics'] = $topics;
        $data['page'] = $page;
        $data['size'] = $home_page_num;

        //置顶帖子
        $data['top'] = \model\Topic::rank(5);

        //节点
        $data['catelist'] = \model\Node::pid();// NodeDao::obj()->get_all_cates();

        //统计
        $data['stats'] = Stats::kv();

        //links
        //$data['links'] = Link::all();

        //action
        $data['action'] = 'home';

        //最新会员列表
        $data['new_users'] = \model\User::rank(15);

        $this->assign($data);
        $this->display('index');
    }

    public function search($page = 1) {
        $keyword = $this->input->post('keyword', true);
        $data['title'] = urldecode($keyword) . '搜索结果';
        $data['keyword'] = urldecode($keyword);
        //echo $this->db->last_query();

        //分页
        $limit = 10;
        $start = ($page - 1) * $limit;
        $data['search_list'] = $this->topic_m->get_search_list($start, $limit, $data['keyword']);
        $data['topic_num'] = count($data['search_list']);

        $config['uri_segment'] = 4;
        $config['use_page_numbers'] = TRUE;
        $config['base_url'] = site_url('search');
        $config['total_rows'] = $data['topic_num'];
        $config['per_page'] = $limit;
        $config['first_link'] = '首页';
        $config['last_link'] = '尾页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '尾页';
        $config['num_links'] = 10;

        $this->load->library('pagination');
        $this->pagination->initialize($config);


        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('search', $data);
    }

}