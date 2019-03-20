<?php
namespace controller\admin;

use util\Administrator;
use util\Pagination;
use daos\LinkDao;

class Links extends Administrator  {

    public function index($page = 1) {
        $data['title'] = '链接管理';
        //分页
        $rows = 20;

        list($total,$links) =  LinkDao::obj()->get_all_links($rows, $page);

        $page = new Pagination($rows,$total);
        $data['pagination'] = $page->fetch();

        $data['links'] = $links;

        $this->assign($data);

        $this->display('links');
    }

    public function del($id) {
        $data['title'] = '删除链接';
        //删除链接
        if (LinkDao::obj()->del_link($id)) {
            show_message('删除链接成功！', site_url('admin/links'), 1);
        }
        show_message('删除链接失败！', site_url('admin/links'));
    }

    public function edit($id) {
        $data['title'] = '修改链接';
        if ($_POST) {
            $str = [
                'name' => $this->input->post('name'),
                'url' => $this->input->post('url'),
                'is_hidden' => $this->input->post('is_hidden')
            ];
            if ($this->link_m->update_link($id, $str)) {
                show_message('修改链接成功！', site_url('admin/links'), 1);
            }

        }
        $data['link'] = $this->link_m->get_link_by_id($id);

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->load->view('link_edit', $data);
    }

    public function add() {
        $data['title'] = '增加链接';
        if ($_POST) {
            $str = array(
                'name' => $this->input->post('name'),
                'url' => $this->input->post('url'),
                'is_hidden' => $this->input->post('is_hidden')
            );
            if ($this->link_m->add_link($str)) {
                show_message('增加链接成功！', site_url('admin/links'), 1);
            }

        }
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->load->view('link_add', $data);

    }


}