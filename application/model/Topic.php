<?php
namespace model;

use util\Auth;
use nb\Collection;
use nb\Model;

class Topic extends Model  {

    /**
     * Iterator
     */
    public function current() {
        $this->row = current($this->stack);

        if ($this->row) {
            $this->row = is_array($this->row)?$this->row:self::dao(false)->findId($this->row);
            return $this;
        }
        return false;
    }

    protected static function __config() {
        return ['topic', 'id'];
    }

    public static function page($rows=30, $start=1, $node_id=0) {
        $db = self::dao()->where('status=?', 0);
        $node_id and $db->where('nid=?', $node_id);
        $db->orderby('ord desc');
        $db->limit($rows,$start);
        return $db->paginate();
    }

    public static function more($rows=30, $start=1, $node_id=0) {
        $db = self::dao()->where('status=?', 0);
        $node_id and $db->where('nid=?', $node_id);
        $db->orderby('ord asc');
        $db->limit($rows,$start);
        return $db->fetchAll();
    }

    public static function rank($rows=30, $start=1, $node_id=0) {
        $db = self::dao()->where('status=?', 0);
        $node_id and $db->where('nid=?', $node_id);
        $db->orderby('ord desc');
        $db->limit($rows,$start);
        return $db->fetchAll();
    }

    public static function byUid($uid, $rows = 0, $start = 1) {
        $where = [
            'status=0 and uid=?',
            $uid
        ];
        return self::finds($where,$rows,$start,'ord desc');
    }

    public static function byTag($tagName, $rows=10, $start=1) {
        $tag = \model\Tag::find('name=?',$tagName);
        //$tag = $this->db->select('tag_id')->where('tag_title', $tag_title)->get('tags')->row_array();
        if (!$tag) {
            return new Collection();
            /*
            $this->db->select('a.topic_id, a.title, a.comments, a.is_top, a.updatetime, b.uid, b.username, b.avatar')
                ->from('topics a')
                ->join('users b', 'a.uid=b.uid')
                ->join('tags_relation c', 'a.topic_id=c.topic_id')
                ->join('tags d', 'c.tag_id=d.tag_id')
                ->where('d.tag_id', $tag['tag_id'])
                ->limit($limit, $page);
            $query = $this->db->get();
            return $query->result_array();
            */
        }
        $topics = self::dao()->field('topic.*')
            ->left('tag_topic c', 'topic.id=c.topicid')
            ->where('c.tagid=?',$tag->id)
            ->limit($rows,$start)
            ->paginate();
        return $topics;

    }

    //置顶及更新
    public static function setTop($topic_id, $is_top, $update = 0) {
        $arr = [];
        if ($update == 0) {
            $arr['is_top'] = ($is_top == 0) ? 1 : 0;
            $arr['ord'] = (3 - 2 * $is_top) * time();
        }
        if ($update == 1) {
            $arr['ord'] = (2 * $is_top + 1) * time();
        }
        $arr['ut'] = time();
        return self::updateId($topic_id,$arr);
    }

    public static function setStatus($tid,$uid){
        return self::updateId($tid,'comments=comments+1,lastreply=?,ruid=?',[
            time(),
            $uid
        ]);
    }

    protected function _ruser() {
        return User::findId($this->ruid?:0);
    }

    protected function _author() {
        return User::findId($this->uid);
    }

    protected function _node() {
        return Node::findId($this->nid?:0);
    }

    protected function _replyUrl() {
        return $this->url . '#reply';
    }

    //帖子最后膝盖时间
    protected function _updatedate() {
        return friendly_date($this->row['ut']);
    }

    //帖子发表时间
    protected function _date() {
        return friendly_date($this->row['ct']);
    }

    //最后回复时间
    protected function _lastdate() {
        return friendly_date($this->row['lastreply']);
    }

    public function _content() {
        return stripslashes($this->row['content']);
    }

    //查看帖子的地址
    protected function _url() {
        return url('show', ['topic_id'=>$this->id]);
    }

    //编辑此帖子页面地址
    protected function _editUrl() {
        return '/posted?id='.$this->id;
    }

    //编辑此帖子的数据接口
    protected function _editPost() {
        return '/posted/post?id='.$this->id;
    }

    //删除此帖子的数据接口
    protected function _delPost() {
        return '/topic/del?id='.$this->id;
    }

    //显示评论页面
    protected function _commentUrl() {
        return '/comment?id='.$this->id;
    }

    //评论帖子的接口
    protected function _commentPost() {
        return '/comment/post?topicid='.$this->id;
    }

    //当前登录用户是否是此帖子的楼主
    protected function _isAuthor() {
        return Auth::init()->id == $this->author->id;
    }

    //当前登录用户是否是此帖子的版主
    //是，则返回对此帖子对权限数组
    //不是返回false
    protected function _isMaster() {
        return false;
    }

    /**
     * 判断是不是已被收藏
     * @return bool
     */
    public function _isFavorite() {
        $uid = Auth::init()->id;
        if (!$uid) {
            return false;
        }
        $favorite= \model\Favorite::find('uid=?',$uid);

        if ($favorite && $favorite['content']) {
            if (strpos(' ,' . $favorite['content'] . ',', ',' . $this->id . ',')) {
                return true;
            }
        }
        return false;
    }

    /**
     * 收藏帖子的接口地址
     *
     * @return string
     */
    public function _favoriteUrl() {
        return '/favorite/add?topic_id='.$this->id;
    }

    /**
     * 取消收藏帖子的接口地址
     * @return string
     */
    public function _favoriteDelUrl() {
        return '/favorite/del?topic_id='.$this->id;
    }


    public function _description() {
        return sb_substr(cleanhtml($this->current['content']), 200);
    }

    public function _tags() {
        return Tag::dao()->left('tag_topic tt','tt.tagid=tag.id')
            ->where('tt.topicid=?',$this->id)
            ->field('tag.*')
            ->fetchAll();
    }

}
