<?php
namespace controller;

use util\Auth;
use util\Controller;

class Notifications extends Controller {

    public function __before(){
        $pass = parent::__before();
        if(!Auth::$o->is_login()) {
            show_message('请登录后再查看', site_url('login'));
        }
        return $pass;
    }

    public function index() {

        $data['title'] = '提醒用户';

        $uid = Auth::$o->uid;//$this->session->userdata('uid');
        $data['notices_list'] = NotificationsDao::obj()->get_notifications_list($uid, 20);
        //删除数据
        if ($data['notices_list']) {

            //$this->db->where('nuid', $uid)->delete('notifications');
            //$this->db->where('uid', $uid)->update('users', array('notices' => 0));
            /*
            NotificationsDao::obj()->delId($uid);
            UserDao::obj()->setId($uid,['notices' => 0]);
            */
            //update session
            //$this->session->set_userdata('notices', 0);
            /*
            NSession::set('notices', 0);
            */
        }
        $this->assign($data);
        $this->display('notifications');
        //$this->load->view('notifications', $data);
    }

    public function del($topic_id) {
        $uid = $this->session->userdata('uid');
        $user_fav = $this->db->get_where('favorites', array('uid' => $uid))->row_array();
        $ids_arr = explode(",", $user_fav['content']);
        if (in_array($topic_id, $ids_arr)) {
            foreach ($ids_arr as $k => $v) {
                if ($v == $topic_id) {
                    unset($ids_arr[$k]);
                    break;
                }
            }
            $topics = count($ids_arr);
            $content = implode(',', $ids_arr);
            $data['content'] = $content;
            $data['favorites'] = $topics;
            if ($this->db->where('uid', $uid)->update('favorites', $data) && $this->db->set('favorites', 'favorites-1', FALSE)->where('topic_id', $topic_id)->update('topics')) {
                redirect($this->input->server('HTTP_REFERER'));
            }

        }
        unset($ids_arr);
    }


}
