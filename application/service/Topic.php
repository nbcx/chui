<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/25 下午3:18
 */
namespace service;

use model\System;
use model\Tag;
use nb\Dao;
use util\Auth;
use model\Conf;
use model\Node;
use model\Stats;
use nb\Middle;
use util\Service;

class Topic extends Service {

    public function publish() {

        $id = $this->input('id');

        return $id?$this->edit($id):$this->add();
    }

    public function add() {
        $nid = $this->input('nid')?:0;

        //if (!Auth::init()->user_permit($nid)) {//权限
        //    $this->fail = '您无权在此节点发表话题!请重新选择节点';
        //    return false;
        //}

        $user = Auth::init();
        $sys = System::init();
        $now = time();

        $uid = $user->id;
        if ($now - $user->lastpost < $sys->timespan) {
            $this->msg = '发帖最小间隔时间是' . $sys->timespan . '秒!';
            return false;
        }

        //标签
        $tags = TagsDiff($this->input('tag'), []);

        if($sys['must_has_tag'] && count($tags)<1) {
            $this->msg = '请至少选择一个话题吧';
            return false;
        }

        list($title,$content) = $this->input('title','content');

        if(!$title || !$content) {
            $this->msg = '帖子标题和内容不能为空！';
            return false;
        }

        //开启审核时
        if ($sys->is_approve == 'on') {
            $topic['is_hidden'] = 1;
        }

        // 内容过滤系统
        $title = filter($title);
        $content = filter($content);
        $gag_time = ($title['gag_time'] > $content['gag_time']) ? $title['gag_time'] : $content['gag_time'];

        $prohibited = $title['prohibited'] | $content['prohibited'];
        if($prohibited) {
            if($gag_time) {
                //禁言$gag_time分钟
            }
            $this->msg = '发表了不当言行！';
            return false;
        }

        $topic['title']   = $title['content'];
        //$new_topic['content'] = format_content($content['content']);
        $topic['content'] = $content['content'];
        //补充帖子信息
        $topic['nid'] = $nid;//节点ID
        $topic['uid'] = $uid;//发帖人ID
        $topic['views'] = 0;//帖子浏览量
        $topic['ct'] = $topic['ut'] = $topic['lastreply'] =$topic['ord'] = $now;

        //开启事务
        \model\Topic::dao()->beginTransaction();
        try {
            $new_topic_id = \model\Topic::insert($topic);
            $topic['id'] = $new_topic_id;
            $this->data = new \model\Topic($topic);

            //标记附件所对应的帖子标签
            //archive
            \model\Attach::archive($uid,$new_topic_id,0,'topic');

            //更新tag，板块，全局和用户的数据统计
            $this->tag($tags,$topic)->node($topic)->stats()->user($topic);

            \model\Topic::dao()->commit();

            return true;
        }
        catch (\Exception $e) {
            \model\Topic::dao()->rollback();
            $this->msg = $e->getMessage();
            return false;
        }
    }

    public function edit($id) {
        list($nid) = $this->input('nid');
        $topic = \model\Topic::findId($id);

        //完成修改
        list($title,$content) = $this->input('post',['title','content']);

        // 内容过滤系统
        $title = filter($title);
        $content = filter($content);
        $gag_time = ($title['gag_time'] > $content['gag_time']) ? $title['gag_time'] : $content['gag_time'];

        $prohibited = $title['prohibited'] | $content['prohibited'];
        if($prohibited) {
            if($gag_time) {
                //禁言$gag_time分钟
            }
            $this->fail = '发表了不当言行！';
            return false;
        }

        $title   = $title['content'];
        $content = $content['content'];

        \model\Topic::updateId($id,[
            'title'=>$title,
            'content'=>$content,
            'ut'=>time()
        ]);

        //标记附件所对应的帖子标签
        //archive
        \model\Attach::archive(Auth::init()->id,$id,0,'topic');

        //判断板块是否变化
        $this->changeNode($topic,$nid);

        //判断标签的变化
        $this->changeTag();

        return true;
    }

    /**
     * 删除帖子
     */
    public function del() {
        $id = $this->input('id');
        $topic = \model\Topic::findId($id);


        //对应标签-1
        //删除标签与帖子的对应关系
        $tags = $topic->tags;
        $timestamp = System::init()->timestamp;
        foreach ($tags as $v) {
            $tCount[] = $v->id;
        }
        Tag::dao()->driver->in('id',$tCount)->update("topics=topics-1,ut={$timestamp}");
        $ttDao  = new Dao('tag_topic');
        $ttDao->delete('topicid=?',$id);


        //对应用户发帖-1
        $credit = System::init()->credit_post;
        \model\User::updateId($topic->uid,[
            "credit=credit+{$credit},topics=topics-1"
        ]);

        //对应节点帖子数-1
        Node::updateId($topic->nid,'count=count-1');

        //将对应附件设置为删除状态

        //将帖子置为删除状态

    }

    //发布帖子时，整理对应板块信息
    private function node($topic) {
        $nid = $topic['nid'];
        $node = Node::findId($topic['nid']);
        //板块最后更新时间为是否为今日
        if(date('Y-m-d',$node['ut']) == date('Y-m-d',time())) {
            Node::updateId($nid,'count=count+1,today=today+1,ut=?',time());
        }
        else {
            Node::updateId($nid,'count=count+1,today=1,ut=?',time());
        }
        return $this;
    }

    //修改帖子时，整理对应板块信息
    private function changeNode(\model\Topic $old, $nid) {
        if($old->nid == $nid) {
            return $this;
        }

        Node::updateId($old->nid,'count=count-1');
        Node::updateId($nid,'count=count+1');
        return $this;
    }

    private function tag($tags,$topic) {
        $topicid = $topic['id'];
        $tags_exist_array = Tag::dao(false)->driver->in('name',$tags)->field('id,name')->fetchAll();

        $tags_exist_array = $tags_exist_array?:[];

        $tags_id = array_column($tags_exist_array, 'id');
        $tags_exist = array_column($tags_exist_array, 'name');
        $new_tags = tagsDiff($tags,$tags_exist);
        //新建不存在的标签
        if ($new_tags) {
            $timestamp = System::init()->timestamp;
            foreach ($new_tags as $name) {
                $id = Tag::insert([
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
        //$tags_arr = array_merge($tags_exist,$new_tags);
        //\model\Topic::updateId($topicid,['tag'=>implode("|", $tags_arr)]);

        //记录标签与TopicID的对应关系
        $ttDao  = new Dao('tag_topic');
        foreach ($tags_id as $id) {
            $ttDao->insert([
                'tagid'=>$id,
                'topicid'=>$topicid
            ]);
        }

        //更新标签统计数据
        $timestamp = System::init()->timestamp;
        Tag::dao()->driver->in('name',$tags_exist)->update("topics=topics+1,ut={$timestamp}");
        return $this;
    }

    private function changeTag() {

    }

    private function stats() {
        $now = time();

        Stats::update(
            'value=value+1',
            'name=?',
            'total_topics'
        );

        $stats = Stats::findId('today_topics');
        if (!is_today($stats['ut'])) {
            Stats::update(
                ['value'=>$stats['value'],'ut'=>$now],
                'name=?',
                'yesterday_topics'
            );
            Stats::update(
                ['value'=>1,'ut'=>$now],
                'name=?',
                'today_topics'
            );
        }
        else {
            Stats::update(
                "value =value+1,ut={$now}",
                'name=?',
                'today_topics'
            );
        }
        return $this;
    }

    private function user($topic) {
        $uid = $topic['uid'];
        $now = time();
        $credit = System::init()->credit_post;
        \model\User::updateId(
            $uid,
            "credit=credit+{$credit},lastpost={$now},topics=topics+1"
        );
        return $this;
    }
}