<?php
namespace model;

use nb\Dao;
use nb\Model;

class Tag extends Model {

    protected static function __config() {
        return ['tag', 'id'];
    }

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

    public static function byTopic($topic_id) {
        $dao = Model::dao(false,['tag_topic', 'tagid']);
        $tag = $dao->kv('tagid','topicid=?',$topic_id);
        return new self($tag);
    }

    public static function page($rows=0, $start=1) {
        $db = self::dao()->driver->orderby('id desc');
        return $db->limit($rows,$start)->paginate();
    }

    public static function more($rows=0, $start=1,$sort='id desc') {
        $db = self::dao()->driver->orderby($sort);
        $db->limit($rows,$start);
        return $db->fetchAll();
    }

    /**
     * 获取热门的Tag
     * 目前只是简单的获取，后期完善
     *
     * @param int $node_id
     * @param int $max
     * @return array|bool
     */
    public static function hot($node_id=0, $max=30) {
        $db = self::dao(false)->driver->orderby('id desc');
        $db->limit($max,0);
        return $db->kv('name');
    }

    public static function renew($tags,$topic_id) {
        $tags_exist_array = self::dao(false)->driver->in('name',$tags)->field('id,name')->fetchAll();

        $tags_exist_array = $tags_exist_array?:[];

        $tags_id = array_column($tags_exist_array, 'id');
        $tags_exist = array_column($tags_exist_array, 'name');
        $new_tags = tagsDiff($tags,$tags_exist);
        //新建不存在的标签
        if ($new_tags) {
            $timestamp = Conf::init()->timestamp;
            foreach ($new_tags as $name) {
                $id = self::insert([
                    'name'=>$name,
                    'followers'=>0,
                    'icon'=>0,
                    'description'=>null,
                    'topics'=>1,
                    'ut'=>$timestamp,
                    'ct'=>$timestamp
                ]);
                $tags_id[] = $id;
            }
        }
        $tags_arr = array_merge($tags_exist,$new_tags);
        Topic::updateId($topic_id,['tag'=>implode("|", $tags_arr)]);


        //记录标签与TopicID的对应关系
        $ttDao  = new Dao('tag_topic');
        foreach ($tags_id as $id) {
            $ttDao->insert([
                'tag_id'=>$id,
                'topic_id'=>$topic_id
            ]);
        }

        //更新标签统计数据
        $timestamp = System::init()->timestamp;
        self::dao()->driver->in('name',$tags_exist)->update("topics=topics+1,ut={$timestamp}");

    }


    public function _url() {
        return '/tag/show?name='.$this->name;
    }

}
