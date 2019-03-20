<?php
namespace controller\admin;

use util\Administrator;
use util\Pagination;
use daos\CommentDao;
use daos\TopicDao;
use model\Topic;

class Topics extends Administrator  {

    public function index($page = 1) {
        $data['title'] = '话题管理';
        //分页
        $rows = 5;

        list($total,$topics) =Topic::page($rows, $page); //TopicDao::obj()->get_all_topics($rows, $page);

        //$page = new Pagination($rows,$total);
        //$data['pagination'] = $page->fetch();

        $data['topics'] = $topics;

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }


    public function more($page = 1) {
        if(!isset($_SERVER['HTTP_X_PJAX'])) {
            return redirect('/admin/topics/index');
        }
        $data['title'] = '话题管理';
        //分页
        $rows = 5;

        list($total,$topics) =Topic::page($rows, $page); //TopicDao::obj()->get_all_topics($rows, $page);

        //$page = new Pagination($rows,$total);
        //$data['pagination'] = $page->fetch();

        $data['topics'] = $topics;

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }

    public function del($topic_id, $uid, $node_id=0) {
        $data['title'] = '删除贴子';
        //$this->myclass->notice('alert("确定要删除此话题吗！");');
        //删除贴子及它的回复
        if (TopicDao::obj()->del_topic($topic_id, $node_id, $uid)) {
            CommentDao::obj()->del_comments_by_topic_id($topic_id, $uid);
            show_message('删除贴子成功！', site_url('admin/topics'), 1);
        }

    }

    public function set_top($topic_id, $is_top) {
        TopicDao::obj()->set_top($topic_id, $is_top);
        redirect('admin/topics/');
    }

    public function batch_process() {
        $topic_ids = array_slice($this->input->post(), 0, -1);
        if ($this->input->post('batch_del')) {
            if ($this->db->where_in('topic_id', $topic_ids)->delete('topics')) {
                show_message('批量删除贴子成功！', site_url('admin/topics'), 1);
            }
        }
        if ($this->input->post('batch_approve')) {
            if ($this->db->where_in('topic_id', $topic_ids)->update('topics', array('is_hidden' => 0))) {
                show_message('批量审核贴子成功！', site_url('admin/topics'), 1);
            }
        }
    }


    public function approve($topic_id,$hidden=0) {
        $row = TopicDao::obj()->set_approve($topic_id,$hidden);
        if ($row) {
            show_message('审核贴子成功！', site_url('admin/topics'), 1);
        }
        show_message('审核贴子失败！', site_url('admin/topics'));
    }


}