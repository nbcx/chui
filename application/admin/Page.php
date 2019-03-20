<?php
namespace controller\admin;

use util\Administrator;
use daos\PageDao;

class Page extends Administrator  {


    public function index($page = 1) {
        $data['title'] = '单页面管理';
        $data['page_list'] = PageDao::obj()->get_page_list();

        $this->assign($data);
        $this->display('page');
    }

    public function del($pid) {
        $data['title'] = '删除页面';
        //$this->myclass->notice('alert("确定要删除此页面吗！");');
        //删除链接
        if ($this->page_m->del_page($pid)) {
            show_message('删除页面成功！', site_url('admin/page'), 1);
        }

    }

    public function edit($pid) {
        $data['title'] = '修改页面';
        if ($_POST) {
            $str = array(
                'title' => $this->input->post('title'),
                'content' => nl2br($this->input->post('content')),
                'go_url' => $this->input->post('go_url'),
                'is_hidden' => $this->input->post('is_hidden'),
                'add_time' => time()
            );
            if ($this->page_m->update_page($pid, $str)) {
                show_message('修改页面成功！', site_url('admin/page'), 1);
            }

        }
        $data['page'] = $this->page_m->get_page_content($pid);
        $this->load->helper('br2nl');
        $data['page']['content'] = br2nl($data['page']['content']);
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->load->view('page_edit', $data);
    }

    public function add() {
        $data['title'] = '增加页面';
        if ($_POST) {
            $arr = $this->form('title','content','go_url','is_hidden');
            $arr['add_time'] = time();
            /*
            $str = array(
                'title' => $this->input->post('title'),
                'content' => nl2br($this->input->post('content')),
                'go_url' => $this->input->post('go_url'),
                'is_hidden' => $this->input->post('is_hidden'),
                'add_time' => time()
            );
            */
            if (PageDao::obj()->add_page($arr)) {
                show_message('添加页面成功！', site_url('admin/page'), 1);
            }

        }
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display('page_add');
    }


}