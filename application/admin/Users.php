<?php
namespace controller\admin;

use util\Administrator;
use util\Pagination;
use daos\GroupDao;
use daos\UserDao;
use model\User;

class Users extends Administrator  {

    public function ace() {
        $this->layout(false);
        $this->display('ace');
    }

    public function login() {
        $this->layout(false);
        $this->display('login');
    }

    public function index($page = 1) {
        $data['title'] = '用户管理';
        $data['act'] = 'index';
        //分页
        $rows = 10;

        list($total,$user) = User::page($rows, $page);//UserDao::obj()->get_all_users($rows, $page);

        //$page = new Pagination($rows,$total);
        //$data['page'] = $page->fetch();

        $data['users'] = $user;

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }

    public function more($page=1) {
        if(!isset($_SERVER['HTTP_X_PJAX'])) {
           return redirect('/admin/users/index');
        }
        //分页
        $rows = 10;

        list($total,$user) = User::page($rows, $page);//UserDao::obj()->get_all_users($rows, $page);

        //$page = new Pagination($rows,$total);
        //$data['page'] = $page->fetch();

        $data['users'] = $user;

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }


    public function edit($uid) {
        $data['title'] = '修改用户信息';
        $data['user'] = UserDao::obj()->getId($uid);

        if ($_POST) {
            $arr = $this->form(
                'username','email','homepage','location','qq','gid',
                'signature','introduction','credit'
            );
            $password = $this->input('password');
            $arr['password'] = $password?md5($password):$data['user']['password'];

            $group_info = GroupDao::obj()->getId($arr['gid']);
            $arr['group_type'] = $group_info['group_type'];

            if (UserDao::obj()->setId($uid, $arr)) {
                show_message('修改用户成功', site_url('admin/users/index'), 1);
            }
        }

        $data['groups'] = GroupDao::obj()->group_list();

        $data['group'] = GroupDao::obj()->getId($data['user']['gid']);// $this->db->get_where('user_groups', array('gid' => $data['user']['gid']))->row_array();
        //加载form类，为调用错误函数,需view前加载
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display('users_edit');
    }

    public function group($act, $gid = '', $group_name='', $commit_edit='') {

    }

    public function del() {
        $uid = (int)$this->uri->segment(4);
        $user = $this->user_m->get_user_by_uid($uid);
        if (!$user) {
            show_message('用户uid不能为空', site_url('admin/users/index'));
        }
        else {
            $this->db->set('value', 'value-1', false)->where('item', 'total_users')->update('site_stats');
            $this->db->set('value', 'value-' . @$user['topics'], false)->where('item', 'total_topics')->update('site_stats');
            $this->db->set('value', 'value-' . @$user['replies'], false)->where('item', 'total_comments')->update('site_stats');
            $this->user_m->del($uid);
            //更新栏目中的数据
            $this->load->model('cate_m');
            $nodes = $this->cate_m->get_node_ids();
            foreach ($nodes as $k => $v) {
                $data[$k]['node_id'] = @$v['node_id'];
                $data[$k]['listnum'] = $this->db->where('node_id', @$v['node_id'])->count_all_results('topics');
            }
            $this->db->update_batch('nodes', $data, 'node_id');
            if ($user['avatar'] != 'uploads/avatar/default/') {
                @unlink(FCPATH . $user['avatar'] . 'big.png');
                @unlink(FCPATH . $user['avatar'] . 'normal.png');
                @unlink(FCPATH . $user['avatar'] . 'small.png');
            }
            show_message('删除用户成功', site_url('admin/users/index'), 1);
        }
    }

    public function search() {
        //查找用户
        $data['title'] = '用户搜索';
        $data['act'] = 'search';
        if ($_POST) {
            $data['users'] = $this->user_m->search_user_by_username($this->input->post('username'));
        }
        $this->load->view('users', $data);
    }


}