<?php

namespace model;

use nb\Model;

class Dialog extends Model {

    protected static function __config() {
        return ['message_dialog dialog', 'id'];
    }

    public static function page($uid=0, $rows, $start=0) {
        return self::finds(
            ['dialog.sender_uid=? or dialog.receiver_uid=?',[$uid,$uid]],
            $rows,
            $start
        );
        /*
        $field = 'dialog.*,b.username as sender_username,b.avatar as sender_avatar,c.username as receiver_username,c.avatar as receiver_avatar';
        $db = $this->where('dialog.sender_uid=? or dialog.receiver_uid=?',[$uid,$uid]);
        $db->left('users b', 'b.uid = dialog.sender_uid');
        $db->left('users c', 'c.uid = dialog.receiver_uid');
        //$db->orderby('message.update_time desc');
        //$db->limit($rows,$start);
        return $this->fetsPage($rows,$start,'dialog.update_time desc',$field);
        */
    }

    public function ___sender() {
        return User::findId($this->sender_uid);
    }

    public function ___receiver() {
        return User::findId($this->receiver_uid);
    }

    public function ___url() {
        return site_url('message/show/' . $this->id);
    }

    //////////////////////////////////////

    public function get_dialog_by_uid($sender_uid, $receiver_uid) {
        $where = "(sender_uid='{$sender_uid}' and receiver_uid='{$receiver_uid}') OR (sender_uid='{$receiver_uid}' AND receiver_uid='{$sender_uid}')";
        $this->db->where($where);
        $query = $this->db->get('message_dialog');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        else {
            return false;
        }
    }

    public function get_message_list($dialog_id, $start, $limit) {
        $this->db->select("a.*,b.username as sender_username,b.avatar as sender_avatar,c.username as receiver_username,c.avatar as receiver_avatar");
        $this->db->from('message a');
        $this->db->where('a.dialog_id', $dialog_id);
        $this->db->join('users b', 'b.uid = a.sender_uid', 'LEFT');
        $this->db->join('users c', 'c.uid = a.receiver_uid', 'LEFT');
        $this->db->order_by('a.create_time', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }




}
