<?php
namespace model;


use nb\Model;

class Follow extends Model {

    protected static function __config() {
        return ['follow follow', 'id'];
    }

    public static function page($uid,$rows=30, $start=1) {
        $db = self::dao()->where('uid=?',$uid);
        //$db->orderby('ord desc');
        $db->limit($rows,$start);
        return $db->paginate();
    }

    public static function byUid($uid, $num =30) {
        return self::fetchs('uid=?',$uid);
        /*
        $db = $this->field('follow.follow_uid,follow.addtime, b.username, b.avatar');
        $db->left('users b', 'b.uid=follow.follow_uid');
        $db->where('follow.uid=?',$uid);
        $db->orderby('follow.addtime desc');
        $db->limit($num,1);
        return $db->fetchAll();
        */

    }


    /**
     * 关注用户的信息
     * @return $this
     */
    public function _user() {
        return User::findId($this->follow_uid);
    }

    ///////////////////////////////////////


    //@提醒someone
    public function notice_insert($topic_id, $suid, $nuid, $ntype) {
        $notics = [
            'topic_id' => $topic_id,
            'suid' => $suid,
            'nuid' => $nuid,
            'ntype' => $ntype,
            'ntime' => time()
        ];
        $this->db->insert('notifications', $notics);
    }





    //public function get_notifications_list($nuid,$num)
    //{
    //	$this->db->select("a.*,b.title,c.username, c.avatar");
    //	$this->db->from('notifications a');
    //	$this->db->where('a.nuid',$nuid);
    //	$this->db->join('topics b','b.topic_id = a.topic_id','LEFT');
    //	$this->db->join('users c','c.uid = a.suid','LEFT');
    //	$this->db->order_by('a.ntime','desc');
    //	$this->db->limit($num);
    //	$query = $this->db->get();
    //	if($query->num_rows() > 0){
    //	return $query->result_array();
    //	}
    //}


}
