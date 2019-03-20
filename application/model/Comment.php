<?php
namespace model;

use util\Auth;
use nb\Model;

class Comment extends Model {

    protected static function __config() {
        return ['comment comment', 'id'];
    }

    public static function topic($topic_id, $start, $rows=0,  $order = 'ct asc') {
        $db = self::dao()->where('topicid=?',$topic_id);
        $db->orderby($order);
        $db->limit($rows, $start);
        return $db->paginate();
    }

    public static function byUid($uid,$num) {
        return self::dao()->where('uid=?',$uid)
            ->orderby('ct desc')
            ->limit($num)
            ->fetchAll();
    }

    /**
     * 输出源格式
     */
    public function _original() {
        if($this->_row['original']) {
            return $this->_row['original'];
        }
        return $this->content;
    }

    /**
     * 回贴的帖子信息
     * @return $this
     */
    public function _topic() {
        return Topic::findId($this->topicid);
    }

    /**
     * 回帖人信息
     * @return $this
     *
     */
    public function _user() {
        if($this->uid) {
            return User::findId($this->uid);
        }
    }

    public function _avatar() {
        return $this->user->avatar;
    }


    protected function _date() {
        return friendly_date($this->ct);
    }

    /**
     * @return bool
     */
    public function _isMaster() {
        return false;
    }

    public function _isAuthor() {
        return Auth::init()->id == $this->user->id;
    }

    /**
     * 是否可以删除
     * @return bool
     */
    public function _isDel() {
        return $this->permit();
    }

    /**
     * 是否可以编辑
     * @return bool
     */
    public function _isEdit() {
        return $this->permit();
    }

    private function permit() {
        if(Auth::init()->isAdmin) {
            return true;
        }
        if($this->isMaster) {
            return true;
        }
        if($this->isAuthor == false) {
            return false;
        }
        return true;
    }

    protected function _delPost() {
        return '/comment/del?id='.$this->id;
    }

}

	
