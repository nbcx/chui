<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/4/13 下午12:32
 */

namespace controller\admin;


use util\Administrator;

class Group extends Administrator {

    public function index($gid = '', $group_name='', $commit_edit='') {
        $data['title'] = '用户组管理';
        //$data['act'] = 'group' . $act;

        $data['group_list'] = \model\Group::fetchs();//  GroupDao::obj()->group_list();
        //$data['group_info'] = GroupDao::obj()->get_group_info($gid);

        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();

        $this->assign($data);
        $this->display();
    }

    public function add($gid = '',$group_name='') {
        $data['group_list'] = GroupDao::obj()->group_list();
        $data['group_info'] = GroupDao::obj()->get_group_info($gid);
        $check_group = GroupDao::obj()->check_group($group_name);

        $data['title'] = '添加用户组';
        if ($group_name) {
            if ($check_group) {
                show_message('用户组已存在', site_url('admin/users/group/add'));
            }
            $str = array(
                'group_name' => $this->input->post('group_name', true),
                'group_type' => 2
            );
            if ($this->db->insert('user_groups', $str)) {
                show_message('添加用户组成功', site_url('admin/users/group/index'), 1);
            }
        }
    }

    public function edit($gid = '',$group_name='',$commit_edit='') {
        $data['group_list'] = GroupDao::obj()->group_list();
        $data['group_info'] = GroupDao::obj()->get_group_info($gid);
        $check_group = GroupDao::obj()->check_group($group_name);

        $data['title'] = '编辑用户组';
        if ($commit_edit) {
            if ($check_group) {
                show_message('用户组已存在', site_url('admin/users/group/edit/' . $gid));
            }
            $str = [
                'group_name' => $group_name
            ];
            if ($this->db->where('gid', $gid)->update('user_groups', $str)) {
                show_message('修改用户组成功', site_url('admin/users/group/index'), 1);
            }
        }
    }

    public function del( $gid = '',$group_name='', $commit_edit='') {
        $data['group_list'] = GroupDao::obj()->group_list();
        $data['group_info'] = GroupDao::obj()->get_group_info($gid);
        $check_group = GroupDao::obj()->check_group($group_name);
        if (@$data['group_info']['group_type'] > 1) {
            if ($this->db->where('gid', $gid)->delete('user_groups')) {
                show_message('删除用户组成功', site_url('admin/users/group/index'), 1);
            }
        }
        else {
            show_message('无法删除系统用户组', site_url('admin/users/group/index'));
        }
    }

}