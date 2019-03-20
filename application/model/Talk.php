<?php
namespace model;

use nb\Model;

class Talk extends Model {

    protected static function __config() {
        return ['message_talk', 'id'];
    }

    public function _ctFriendly() {
        return friendly_date($this->ct);
    }

    public function _sender() {
        return User::findId($this->sender_uid);
    }

    public function _receiver() {
        return User::findId($this->receiver_uid);
    }

    /**
     * 判断两个用户间是否存在对话
     * @param $sender_uid
     * @param $receiver_uid
     */
    public static function hasTalk($sender_uid, $receiver_uid) {
        return self::find("(sender_uid=? and receiver_uid=?) OR (sender_uid=? AND receiver_uid=?)",[
            $sender_uid,
            $receiver_uid,
            $receiver_uid,
            $sender_uid
        ]);
    }


    ////////////////////////////////////
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

    public function get_dialog_list($uid, $rows, $start) {
        $field = 'message.*,b.username as sender_username,b.avatar as sender_avatar,c.username as receiver_username,c.avatar as receiver_avatar';
        $db = $this->where('message.sender_uid=? or message.receiver_uid=?',[$uid,$uid]);
        $db->left('users b', 'b.uid = message.sender_uid');
        $db->left('users c', 'c.uid = message.receiver_uid');
        //$db->orderby('message.update_time desc');
        //$db->limit($rows,$start);
        return $this->fetsPage($rows,$start,'message.update_time desc',$field);
    }


}
