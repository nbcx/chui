<?php
namespace service;

use bin\Config;
use model\System;
use util\Auth;
use model\Conf;
use model\Notify;
use model\Stats;
use nb\Middle;
use util\Auxiliary;
use util\Service;

class Comment extends Service {

    public function publish() {
        $from = $this->form('post',
            ['resolver','comment']
        );
        $system = System::init();
        $auth = Auth::init();

        $time = time();
        if ($time - $auth->lastreply < $system->timespan) {
            $this->msg = '发帖最小间隔时间是' . $system->timespan . '秒!';
            return false;
        }

        $topic_id = $this->input('topicid');

        //数据返回
        $topic = \model\Topic::findId($topic_id);

        //数据提交
        $data = [
            'resolver' => $from['resolver'],
            'content' => $from['comment'],
            'topicid' => $topic_id,
            'uid' => $auth->id,
            'ct' => $time,
            'ut' => $time,
            'floor' => $topic['comments'] + 1
        ];

        $data = $this->hook()->trigger($signal)->insert($data);
        //无插件的处理
        if(!$signal) {
            //$data['content'] = filter_check($data['content']);
            $data['content'] = format_content($data['content']);
        }

        //处理@会员功能
        $this->hook()->trigger($signal)->at($data,$this);
        $signal or $data['content'] = Auxiliary::at($data['content'],$topic_id);

        //入库
        $id = \model\Comment::insert($data);

        //更新用户的回复数/最后发贴时间
        $this->user($data['uid'],$time);

        //更新帖子统计信息
        $this->topic($topic_id);

        //回复提醒作者
        $this->at($topic_id);

        //更新统计
        $this->stats();
        $this->data = $id;//评论记录ID
        $this->msg = '评论发表成功！';
        return true;
    }

    //删除回复
    public function del() {
        $id = $this->input('id');
        $comment = \model\Comment::findId($id);

        if($comment->isDel == false) {
            $this->fail = '非管理员或非本版块版主不能操作';
            return false;
        }

        //\model\Comment::dao()->beginTransaction();
        \model\Comment::updateId($id,['status'=>1]);//将评论置为删除状态
        //\model\Comment::deleteId($id);

        //更新贴子回复数
        //\model\Topic::updateId($comment->topic_id,'comments=comments-1');

        //更新用户的回复数
        \model\User::updateId($comment->uid,'replies=replies-1');

        //更新统计
        //Stats::update('value=value-1','name=?','total_comments');
        //\model\Comment::dao()->commit();
        $this->success = $comment->topicid;
        return true;

    }

    //编辑回复
    public function edit() {
        $id = $this->input('id');
        $comment = \model\Comment::findId($id);

        if($comment->isEdit == false) {
            //show_message('非本人或管理员或本版块版主不能操作', site_url('topic/show/' . $topic_id));
            $this->fail = '非本人或管理员或本版块版主不能操作';
            return false;
        }
        //无编辑器时的处理
        //if($this->config->item('show_editor')=='off'){
        //	$data['comment']['content'] = filter_check($data['comment']['content']);
        //	$this->load->helper('format_content');
        //	$data['comment']['content'] = format_content($data['comment']['content']);
        //	$data['comment']['content'] =br2nl($data['comment']['content'] );
        //}
        list($content,$resolver) = $this->input('comment','resolver');

        $update['resolver'] = $resolver;
        if($resolver && $resolver != 'html') {
            $update['original'] = $content;
        }

        $content = $this->hook()->trigger($signal)->edit($content);
        //无插件的处理
        if(!$signal) {
            $content = filter_check($content);
            $content = format_content($content);
            $content = br2nl($content);
        }

        $time = Conf::init()->timestamp;

        //数据处理
        $update['ut'] = $time;
        $update['content'] = $content;

        //处理@会员功能
        $this->hook()->trigger($signal)->at($update['content'],$this);
        $signal or $update['content'] = Auxiliary::at($update['content'],$comment->topic_id);

        //$this->db->where('id', $id)->update('comments', $comment)
        if (\model\Comment::updateId($id,$update)) {
            //更新贴子回复时间
            \model\Topic::updateId($comment->topic_id,'lastreply=?', [$time]);
            $this->success = $update;
            return true;
        }
        return false;
    }

    //更新用户的回复数/最后发贴时间
    protected function user($uid,$time) {
        \model\User::updateId($uid,"replies=replies+1,lastreply={$time}");
        Auth::init()->freshen();
        return $this;
    }

    //更新帖子统计信息
    //更新回复数,最后回复用户,最后回复时间,更新时间,ord时间
    protected function topic($topic_id) {
        //置顶
        //\model\Topic::setTop($topic_id, $from['is_top'], 1);
        \model\Topic::setStatus($topic_id,Auth::init()->id);
        return $this;
    }

    protected function stats() {
        Stats::update('value=value+1','name=?','total_comments');
        $stats = Stats::find('name=?','today_topics');
        if (!is_today($stats['update_time'])) {
            $value = 1;
        }
        else {
            $value = 'value+1';
        }
        return $this;
    }

    //回复提醒作者
    protected function at($topic_id) {
        //$conf = System::init();
        $uid = Auth::init()->id;
        $topic = \model\Topic::findId($topic_id);
        if ($uid != $topic['uid']) {
            Notify::at($topic_id, $this->uid, $topic['uid'], 0);
            //更新作者的提醒数
            \model\User::updateId($topic['uid'],"notices=notices+1");

            //更新会员积分
            //这个由插件来做吧
            /*
            if($conf->credit_reply > 0) {
                \model\User::updateId($uid,"credit=credit+{$conf->credit_reply}");
            }
            if($conf->credit_reply_by > 0) {
                \model\User::updateId($topic['uid'],"credit=credit+{$conf->credit_reply_by}");
            }
            */
        }
        return $this;
    }

}
