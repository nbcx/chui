<?php
namespace model;

use nb\Model;

class Notify extends Model {

    protected static function __config() {
        return ['notify', 'id'];
    }

    //@提醒someone
    public static function at($topic_id, $suid, $nuid, $ntype) {
        $notics = [
            'topic_id' => $topic_id,
            'suid' => $suid,
            'nuid' => $nuid,
            'ntype' => $ntype,
            'ntime' => time()
        ];
        return self::insert($notics);
    }

    public function get_notifications_list($nuid, $num) {
        $db = $this->field("notify.*,b.title,c.username, c.avatar");
        $db->where('notify.nuid=?', $nuid);
        $db->left('topics b', 'b.topic_id = notify.topic_id');
        $db->left('users c', 'c.uid = notify.suid');
        $db->limit($num)->orderby('notify.ntime desc');

        return $db->fetchAll();
    }


}
