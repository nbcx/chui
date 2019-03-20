<?php
namespace model;

use util\Auth;
use nb\Model;

class Message extends Model {

    protected static function __config() {
        return ['message', 'id'];
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

    public static function page($uid=0, $rows, $start=0) {
        return self::paginate(
            $rows,
            $start,
            ['sender_uid=? or receiver_uid=?',[$uid,$uid]]
        );
    }

    /*
    public function ___sender() {
        return User::findId($this->sender_uid);
    }

    public function ___receiver() {
        return User::findId($this->receiver_uid);
    }
    */
    public function _fromer() {
        if($this->receiver_uid == Auth::init()->uid) {
            return User::findId($this->sender_uid);
        }
        return User::findId($this->receiver_uid);
    }

    public function _url() {
        return site_url('message/' . $this->id);
    }

    public function _utFmt() {
        return date('H:i:s',$this->ut);
    }

    public function _utFriendly() {
        return friendly_date($this->ut);
    }

    /**
     * 是否阅读了此对话
     */
    public function _isRead() {
        return $this->receiver_uid == Auth::init()->uid && $this->receiver_read == 0;
    }


    ////////////////////////////////////

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
