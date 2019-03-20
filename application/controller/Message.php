<?php
namespace controller;

use util\Auth;
use util\Controller;
use model\Talk;

class Message extends Controller {

    public function __before(){
        $pass = parent::__before();
        if(!Auth::init()->islogin) {
            show_message('请登录后再查看', site_url('login'));
        }
        return $pass;
    }


    public function index($page = 1) {
        $data['title'] = '私信列表';

        //分页
        $rows = 20;

        $uid = Auth::init()->uid;
        list($total,$messages) = \model\Message::page($uid,$rows, $page);

        $data['total']    = $total;
        $data['messages'] = $messages;

        $this->assign($data);
        $this->display('message/index');
    }

    public function show($id, $page = 1) {

        $data['message']= \model\Message::findId($id);//$this->db->where('id', $id)->get('message_dialog')->row_array();
        if (!$data['message']) {
            show_message('不存在的会话');
        }

        $limit = 30;
        list($total,$talk) = Talk::paginate($limit,$page,['msg_id=?',$id],'ct desc');

        $data['total'] = $total;
        $data['talks'] = $talk;

        $data['title'] = '私信对话';
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->assign($data);
        $this->display('message/show');
    }

    public function send($receiver_uid,$content) {
        $auth = Auth::init();
        $uid = $auth->uid;
        if ($uid == $receiver_uid) {
            exit;
        }

        $content = htmlentities(trim($content));
        $talk = \model\Message::hasTalk($uid, $receiver_uid); //$this->message_m->get_dialog_by_uid($uid, $receiver_uid);

        if ($talk) {
            $msg_id = $talk['id'];
            $msg_data = [
                'sender_uid' => $uid,
                'receiver_uid' => $receiver_uid,
                'last_content' => $content,
                'receiver_read' => 0,
                'ut' => time(),
                'messages' => $talk['messages'] + 1
            ];
            \model\Message::updateId($msg_id,$msg_data);
            //$this->db->where('id', $dialog_id)->update('message_dialog', $dialog_data);
            if ($talk['receiver_read'] == 1) {
                \model\User::updateId($receiver_uid,'messages_unread=messages_unread+1');
                //$this->db->set('messages_unread', 'messages_unread+1', false)->where('uid', $receiver_uid)->update('users');
            }
        }
        else {
            $msg_data = [
                'sender_uid' => $uid,
                'receiver_uid' => $receiver_uid,
                'last_content' => $content,
                'ct' => time(),
                'ut' => time(),
                'messages' => 1
            ];
            $msg_id = \model\Message::insert($msg_data);
            \model\User::updateId($receiver_uid,'messages_unread=messages_unread+1');
            //$this->db->insert('message_dialog', $dialog_data);
            //$dialog_id = $this->db->insert_id();
            //$this->db->set('messages_unread', 'messages_unread+1', false)->where('uid', $receiver_uid)->update('users');
        }
        $talk_data = [
            'msg_id' => $msg_id,
            'sender_uid' => $uid,
            'receiver_uid' => $receiver_uid,
            'content' => $content,
            'ct' => time()
        ];
        if (Talk::insert($talk_data)) {
            $callback = [
                'dialog_id' => $msg_id,
            ];
            echo json_encode($callback);
        }
    }


}
