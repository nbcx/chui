<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 17/9/9 上午11:00
 */
namespace controller\admin;

use util\Administrator;
use daos\SitestatsDao;
use daos\UserDao;
use model\Stats;

class Dashboard extends Administrator  {

    public function index() {
        $data['title'] = '管理后台';

        $data['stat'] = Stats::kv();
        $this->assign($data);
        //$this->layout('layout');
        $this->display('dashboard');
    }

    public function test() {
        $data['title'] = '管理后台';
        //统计
        //$stats = $this->db->get('site_stats')->result_array();
        //$data['stats'] = array_column($stats, 'value', 'item');
        //$data['last_user'] = $this->db->select('username')->where('uid', @$data['stats']['last_uid'])->get('users')->row_array();
        //$data['stats']['last_username'] = @$data['last_user']['username'];
        $data['stat'] = Stats::items();// SitestatsDao::obj()->get_all_status();
        //$data['last_user'] = UserDao::obj()->row(@$data['stats']['last_uid']);
        //$data['stats']['last_username'] = @$data['last_user']['username'];

        $this->assign($data);
        $this->display('dashboard');
    }

}